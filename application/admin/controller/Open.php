<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Config;

class Open extends Controller {

    public function login() {
        Config::set('app.dispatch_error_tmpl',  APP_PATH . 'admin' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'prompt_jump.html');
        $ifcaptcha = config('captcha_signin');
        if ($this->request->isPost()) {
            $data = $this->request->post(); 
            // 验证数据
            $result = $this->validate($data, 'AdminUser.login');
            if (true !== $result) {
                $this->error($result);
            }
            // 验证码
            if ($ifcaptcha) {
                $captcha = $data['captcha'];
                $captcha == '' && $this->error('请输入验证码');
                if (!captcha_check($captcha)) {
                    //验证失败
                    $this->error('验证码错误或失效');
                }
            }
            //数据库判断
            $AdminUser = model('AdminUser');
            try {
                $uid = $AdminUser->login($data['username'], $data['password']);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $groupid = $AdminUser->checkLogin();
            $adminMenu = model('AdminMenu');
            $adminMenu->setGroupid($groupid);
            $url = $adminMenu->getFirstUrl();
            $this->redirect($url);
        } else {
            $this->assign('ifcaptcha', $ifcaptcha);
            return $this->fetch();
        }
    }

    public function loginout() {
        session(null);
        return $this->redirect('login');
    }

}
