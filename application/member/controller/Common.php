<?php

namespace app\member\controller;

use app\member\model\Member;
use app\common\model\App;
use think\facade\Config;

class Common extends \think\Controller {

    protected $memberInfo = [];

    protected function initialize() {
        parent::initialize();
        //登录判断
        if (!$this->request->isAjax()) {//区分是否是ajax控制器
            Config::set('app.dispatch_error_tmpl', APP_PATH . 'member' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'prompt_jump.html');
            if (!App::checkon('member')) {
                $this->error('会员应用没有安装或开启');
            }
            $account = Member::checkLogin();
            if (!$account) {
                $this->redirect('member/open/login');
            }
            $this->memberInfo = Member::where('account', $account)->find();
            Config::set('app.dispatch_error_tmpl', APP_PATH . 'member' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'prompt_jump.html');
            $this->assign('nickname', $this->memberInfo->nickname);
        } else {
            if (!App::checkon('member')) {
                exit('会员应用没有安装或开启');
            }
            $account = Member::checkLogin();
            if (!$account) {
                exit('未登陆');
            }
        }
    }

}
