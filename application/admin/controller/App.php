<?php

namespace app\admin\controller;

use think\Db;
use think\facade\Cache;

class App extends Common {

    public function index() {
        $apps = [];
        $appName = [];
        $dirs = ROOT_PATH . 'application';
        if (is_dir($dirs)) {
            $dir = opendir($dirs);
            while (false != $file = readdir($dir)) {
                if ($file != '.' && $file != '..' && is_dir($dirs . "/" . $file) && !in_array($file, ['admin', 'home', 'install', 'common', 'error'])) {
                    $apps[$file] = [];
                    $appName[] = $file;
                }
            }
            closedir($dir);
        }
        $dbApps = $dbAppName = Db::name('app')->column('name,status,installstate');
        if ([] !== $apps) {
            foreach ($apps as $key => &$vo) {
                $appInfoFile = $dirs . '/' . $key . '/data/appInfo.php';
                if (is_file($appInfoFile)) {
                    $vo = require_once($appInfoFile);
                }
                $vo['status'] = isset($dbApps[$key]['status']) ? $dbApps[$key]['status'] : 0;
                $vo['installstate'] = isset($dbApps[$key]['installstate']) ? $dbApps[$key]['installstate'] : 0;
            }
            //构建数据库中应用名数组用于同步应用于数据库记录
            $dbAppName = [];
            if (!empty($dbApps)) {
                foreach ($dbApps as $k => $v) {
                    $dbAppName[] = $k;
                }
            }
            //没有入库的应用
            $noDbApp = array_diff($appName, $dbAppName);
            if (!empty($noDbApp)) {
                $data = [];
                foreach ($noDbApp as $v) {
                    $data[] = ['name' => $v, 'status' => 0, 'installstate' => 0];
                }
                Db::name('app')->insertAll($data);
            }
            //入库但被删除的应用
            $noExistApp = array_diff($dbAppName, $appName);
            if (!empty($noExistApp)) {
                Db::name('app')->delete($noExistApp);
            }
        } else {
            Db::name('app')->delete(true);
        }
        $this->assign('list', $apps);
        return $this->fetch();
    }

    public function setstate($app, $ifwork) {
        $result = $this->validate(['app' => $app, 'ifwork' => $ifwork], [
            'app|应用' => 'require|alphaNum',
            'ifwork|应用状态' => 'require|number',
        ]);
        if (true !== $result) {
            return $result;
        }
        if (Db::name('app')->where('name', $app)->update(['status' => $ifwork])) {
            return true;
        } else {
            return '设置失败';
        }
    }

    public function install($app) {
        $result = $this->validate(['app' => $app], [
            'app|应用' => 'require|alphaNum',
        ]);
        if (true !== $result) {
            $this->error($result);
        }
        //数据库安装
        $installSql = ROOT_PATH . 'application/' . $app . '/data/database.sql';
        if (is_file($installSql)) {
            $sql = file_get_contents($installSql);
            $sql = str_replace("\r", "\n", $sql);
            $sql = explode(";\n", $sql);
            $sql = str_replace(" `uk_", " `" . config('database.prefix'), $sql);
            foreach ($sql as $value) {
                $value = trim($value);
                if (empty($value)) {
                    continue;
                }
                Db::execute($value);
            }
        }
        //行为安装
        $this->installBehavior($app);
//        exit();
        //后台菜单安装
        $adminMenuPhp = ROOT_PATH . 'application/' . $app . '/data/adminMenu.php';

        if (is_file($adminMenuPhp)) {
            $adminMenu = require_once($adminMenuPhp);
            if ([] != $adminMenu) {
                $this->installAdminMenu($adminMenu, 0);
                Cache::clear('db_admin_menu_tree');
            }
        }
        //修改应用安装状态
        Db::name('app')->where('name', $app)->update(['status' => 1, 'installstate' => 1]);
        $this->success($app . '应用安装成功~');
    }

    public function uninstall($app) {
        $result = $this->validate(['app' => $app], [
            'app|应用' => 'require|alphaNum',
        ]);
        if (true !== $result) {
            $this->error($result);
        }

        //删除后台菜单
        $adminMenuPhp = ROOT_PATH . 'application/' . $app . '/data/adminMenu.php';
        if (is_file($adminMenuPhp)) {
            $adminMenu = require_once($adminMenuPhp);
            if ([] != $adminMenu) {
                foreach ($adminMenu as $vo) {
                    $pid = Db::name('admin_menu')->where('pid', 0)->where('title', $vo['title'])->value('id');
                    $this->unstallAdminMenu($pid);
                }
                Cache::clear('db_admin_menu_tree');
            }
        }
        //行为卸载
        $this->uninstallBehavior($app);
        //删除数据表
        $apInfoPhp = ROOT_PATH . 'application/' . $app . '/data/appInfo.php';
        if (is_file($adminMenuPhp)) {
            $apInfo = require_once($apInfoPhp);
            if (!empty($apInfo['dbTables'])) {
                $prefix = config('database.prefix');
                foreach ($apInfo['dbTables'] as $key => $value) {
                    $table_name = $prefix . $key;
                    Db::execute("DROP TABLE IF EXISTS `{$table_name}`");
                }
            }
        }
        //修改应用安装状态
        Db::name('app')->where('name', $app)->update(['status' => 0, 'installstate' => 0]);
        $this->success($app . '应用卸载成功~');
    }

    //应用行为安装
    protected function installBehavior($app) {
        $behaviorInfoPhp = ROOT_PATH . 'application/' . $app . '/data/behaviorInfo.php';
        if (is_file($behaviorInfoPhp)) {
            $behaviorInfo = require_once($behaviorInfoPhp);
            if (is_array($behaviorInfo['behavior']) && [] != $behaviorInfo['behavior']) {
                //行为安装
                $data['create_time'] = $data['update_time'] = time();
                $data['ifalone'] = 0;
                $data['orders'] = 0;
                $data['status'] = 1;
                foreach ($behaviorInfo['behavior'] as $vo) {
                    $data['name'] = $vo;
                    if (true === $this->validate($data, 'Behavior') && class_exists($vo . '\Behavior')) {
                        Db::name('behavior')->insert($data);
                    }
                }
            }
            unset($data);
            //钩子与行为安装
            if (is_array($behaviorInfo['hookBehavior']) && [] != $behaviorInfo['hookBehavior']) {
                $data['create_time'] = $data['update_time'] = time();
                $data['status'] = 1;
                foreach ($behaviorInfo['hookBehavior'] as $vo) {
                    if (isset($vo[2])) {
                        $ifexit = Db::name('hook_behavior')->where('hook', $vo[0])->where('behavior', $vo[1])->column('id');
                        if (!$ifexit && class_exists($vo[1] . '\Behavior')) {
                            $data['hook'] = $vo[0];
                            $data['behavior'] = $vo[1];
                            $data['orders'] = $vo[2];
                            Db::name('hook_behavior')->insert($data);
                        }
                    }
                }
            }
            cache('hook_behaviors', null);
            cache('hooks', null);
        }
    }

    //应用行为卸载
    protected function uninstallBehavior($app) {
        $behaviorInfoPhp = ROOT_PATH . 'application/' . $app . '/data/behaviorInfo.php';
        if (is_file($behaviorInfoPhp)) {
            $behaviorInfo = require_once($behaviorInfoPhp);
            //行为卸载
            if (is_array($behaviorInfo['behavior']) && [] != $behaviorInfo['behavior']) {
                Db::name('behavior')->where('name', 'in', $behaviorInfo['behavior'])->delete();
            }
            //钩子与行为关系卸载
            if (is_array($behaviorInfo['hookBehavior']) && [] != $behaviorInfo['hookBehavior']) {
                foreach ($behaviorInfo['hookBehavior'] as $vo) {
                    if (isset($vo[1])) {
                        Db::name('hook_behavior')->where('hook', $vo[0])->where('behavior', $vo[1])->delete();
                    }
                }
            }
            cache('hook_behaviors', null);
            cache('hooks', null);
        }
    }

    //递归安装后台菜单
    protected function installAdminMenu($menu, $pid) {
        foreach ($menu as $vo) {
            $vo = array_merge(['pid' => $pid, 'url_type' => 1, 'url_target' => '_self', 'ifsystem' => 0], $vo);
            $result = $this->validate($vo, 'AdminMenu');
            if (true !== $result) {
                return $this->error($result);
            }
            if (isset($vo['children'])) {
                $children = $vo['children'];
                unset($vo['children']);
                $this->installAdminMenu($children, Db::name('admin_menu')->insertGetId($vo));
            } else {
                Db::name('admin_menu')->insert($vo);
            }
        }
    }

    //递归删除后台菜单
    protected function unstallAdminMenu($id) {
        Db::name('admin_menu')->where('id', $id)->delete();
        $list = Db::name('admin_menu')->where('pid', $id)->column('id');
        if (!empty($list)) {
            foreach ($list as $vo) {
                $this->unstallAdminMenu($vo);
            }
        }
    }

}
