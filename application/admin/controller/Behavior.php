<?php

namespace app\admin\controller;

use think\Db;

class Behavior extends Common {

    public function index() {
        $Behavior = model('Behavior');
        $list = model('Behavior')->order('orders desc')->paginate(10);

        foreach ($list as $key => &$vo) {
            $className = $vo->name . '\Behavior';
            if (class_exists($className)) {
                if (method_exists($className, 'info')) {
                    $info = $className::info();
                    if ($vo->title != $info['title']) {
                        $Behavior->where('id', $vo->id)->update(['title' => $info['title']]);
                    }
                    $vo->title = isset($info['title']) ? $info['title'] : '';
                    $vo->version = isset($info['version']) ? $info['version'] : '';
                    $vo->author = isset($info['author']) ? $info['author'] : '';
                    $vo->dbTables = isset($info['dbTables']) ? $info['dbTables'] : '';
                } else {
                    $vo->title = $vo->version = $vo->author = '';
                    $vo->dbTables = [];
                }
            } else {
                unset($list[$key]);
            }
        }
        $this->assign('list', $list);
        $this->assign('page', $list->render());
        return $this->fetch();
    }

    public function add() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, 'Behavior');
            if (true !== $result) {
                return $this->error($result);
            }
            if (!class_exists($data['name'] . '\Behavior')) {
                $this->error('找不到' . $data['name'] . '行为类~');
            }
            $data['status'] = 0;
            $data['ifalone'] = 1;
            $Behavior = model('Behavior');
            try {
                $Behavior->allowField(['name', 'orders', 'status', 'ifalone'])->save($data);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            cache('behaviors',null);
            $this->success('行为添加成功~', url('index'));
        } else {
            return $this->fetch();
        }
    }

    public function install($id = 0) {
        if (!is_numeric($id)) {
            $this->error('行为ID参数错误~');
        }
        $info = model('Behavior')->get($id);
        $className = $info->name . '\Behavior';
        //数据库安装
        $installSql = $className::getSqlPath();
        if ('' != $installSql && is_file($installSql)) {
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
        //修改应用安装状态
        $info->status = 1;
        $info->save();
        cache('behaviors',null);
        $this->success('行为安装成功~');
    }

    public function uninstall($id = 0) {
        if (!is_numeric($id)) {
            $this->error('行为ID参数错误~');
        }
        $info = model('Behavior')->get($id);
        $className = $info->name . '\Behavior';

        //删除数据表
        $bInfo = $className::info();
        if (!empty($bInfo['dbTables'])) {
            $prefix = config('database.prefix');
            foreach ($bInfo['dbTables'] as $key => $value) {
                $table_name = $prefix . $key;
                Db::execute("DROP TABLE IF EXISTS `{$table_name}`");
            }
        }
        //修改行为状态
        $info->status = 0;
        $info->save();
        cache('behaviors',null);
        $this->success('行为卸载成功~');
    }

    public function delete($id = 0) {
        if (!is_numeric($id)) {
            return '参数错误~';
        }
        $Behavior = model('Behavior')->get($id);
        Db::name('hook_behavior')->where('behavior', $Behavior->name)->cache('hook_behaviors')->delete();
        if ($Behavior->delete()) {
            cache('behaviors',null);
            return true;
        } else {
            return '数据库操作失败';
        }
    }

    public function changeOrder($id, $num) {
        $result = $this->validate(['id' => $id, 'num' => $num], ['id|行为ID' => 'require|number', 'num|排序' => 'require|number']);
        if (true !== $result) {
            return $result;
        }
        if (model('Behavior')->where('id', $id)->cache('behaviors')->update(['orders' => $num])) {
            return true;
        } else {
            return '设置排序失败';
        }
    }

}
