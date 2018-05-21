<?php

namespace app\install\controller;

use think\Controller;
use think\Db;

class Index extends Controller {

    protected function initialize() {
        parent::initialize();
        if (file_exists(ROOT_PATH . 'config' . DIRECTORY_SEPARATOR . 'database.php')) {
            $this->error('已经成功安装了ukcms，若需要重新安装请先删除database.php', ROOT_URL);
        }
        $this->assign('baseUrl', $this->request->baseFile() . '/index/');
    }

    //安装首页
    public function index() {
        return $this->fetch();
    }

    //安装第一步，检测运行所需的环境设置
    public function step1() {
        session('error', false);
        //环境检测
        $env = check_env();
        //目录文件读写检测
        $dirfile = check_dirfile();
        //函数检测
        $func = check_func();

        session('step', 1);

        $this->assign('dirfile', $dirfile);
        $this->assign('env', $env);
        $this->assign('func', $func);
        return $this->fetch();
    }

    /**
     * 第二步
     * @param  array $db 配置信息
     * @param  bool $ifdemo 是否安装演示
     */
    public function step2($db = null, $acc = null, $ifdemo = null) {
        session('error') && $this->error('环境检测没有通过，请调整环境后重试！', url('step1'));
        $step = session('step');
        if ($step != 1 && $step != 2) {
            $this->redirect('step1');
        }
        if (request()->isPost()) {
            if (!is_array($acc) || !is_array($db)) {
                return $this->error('非法操作~');
            }
            //数据库配置验证
            $DB = [];
            list($DB['type'], $DB['hostname'], $DB['database'], $DB['username'], $DB['password'], $DB['hostport'], $DB['prefix']) = $db;
            $result = $this->validate($DB, [
                'type|数据库类型' => 'require|alpha',
                'hostname|数据库地址' => 'require',
                'database|数据库名' => 'require|alphaDash',
                'username|数据库用户名' => 'require|alphaDash',
//                'password|数据库密码' => 'require',
                'hostport|数据库端口' => 'require|number',
                'prefix|数据库前缀' => 'require|alphaDash',
            ]);
            if (true !== $result) {
                return $this->error($result);
            }
            //后台账户配置验证
            $ACC = [];
            list($ACC['account'], $ACC['password'], $ACC['password_confirm']) = $acc;

            $result = $this->validate($ACC, [
                'account|后台登录账户' => 'require|length:3,15|alphaDash',
                'password|后台登录密码' => 'require|length:6,20|confirm',
            ]);
            if (true !== $result) {
                return $this->error($result);
            }
            $ACC['password']=\think\helper\Hash::make($ACC['password']);
            unset($ACC['password_confirm']);
            //存储配置
            session('db_config', $DB);
            session('acc_config', $ACC);
            //安装过程
            $dbname = $DB['database'];
            unset($DB['database']);
            $dbc = Db::connect($DB);
            // 检测数据库连接
            try {
                $dbc->execute('select version()');
            } catch (\Exception $e) {
                $this->error('数据库连接失败：' . $e->getMessage());
            }
            //创建数据库
            $sql = "CREATE DATABASE IF NOT EXISTS `" . $dbname . "` DEFAULT CHARACTER SET utf8";
            try {
                $dbc->execute($sql);
            } catch (\Exception $e) {
                $this->error('创建数据库失败：' . $e->getMessage());
            }


            session('ifdemo', $ifdemo);
            session('step', 2);
            //跳转到数据库安装页面
            $this->redirect('step3');
        } else {
            return $this->fetch();
        }
    }

    //安装第三步，安装数据表，创建配置文件
    public function step3() {
        $dbconfig = session('db_config');
        $accconfig = session('acc_config');
        if (session('step') != 2 || empty($dbconfig)) {
            $this->redirect('step2');
        }
        echo $this->fetch();
        $db = Db::connect($dbconfig);
//        session('ifdemo', null);
        //创建数据表
        create_tables($db, $dbconfig['prefix'], $accconfig);
        session('step', null);
        session('error', null);
        session('acc_config', null);
        session('db_config', null);
        session('ifdemo', null);
        //创建配置文件
        write_config($dbconfig);
        \think\facade\Cache::clear();
        show_msg('<span><a class=\"am-btn am-btn-primary\" href=\"' . ROOT_URL . 'admin.php\">登录后台</a> <a class=\"am-btn am-btn-primary \" href=\"' . ROOT_URL . 'index.php\">访问首页</a></span><style>#working{display:none}</style>');
    }

}
