<?php

namespace app\member\controller;

use app\common\model\App;
use think\facade\Config;

class Open extends \think\Controller {

    protected function initialize() {
        parent::initialize();
        Config::set('app.dispatch_error_tmpl', APP_PATH . 'member' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'prompt_jump.html');
        if(!App::checkon('member')){
            $this->error('会员应用没有安装或开启');
        }
        Config::set('app.dispatch_success_tmpl', APP_PATH . 'member' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'prompt_jump.html');
    }

    public function login() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证数据
            $result = $this->validate($data, 'Member.login');
            if (true !== $result) {
                $this->error($result);
            }
            // 验证码
            $captcha = $data['captcha'];
            $captcha == '' && $this->error('请输入验证码');
            if (!captcha_check($captcha)) {
                //验证失败
                $this->error('验证码错误或失效');
            }
            //数据库判断
            $Member = model('Member');
            try {
                $Member->login($data['account'], $data['password']);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            //更新登录信息
            $data = [];
            $data['last_login_ip'] = get_client_ip();
            $data['last_login_time'] = time();
            $Member->where('account', $data['account'])->update($data);
            $this->redirect('member/index/index');
        } else {
            $memberAppInfo = require_once(ROOT_PATH . 'application/member/data/appInfo.php');
            $this->assign('version', $memberAppInfo['version']);
            return $this->fetch();
        }
    }

    public function register() {
        if ($this->request->isPost()) {
            
            $data = $this->request->post();
            // 验证数据
            $result = $this->validate($data, 'Member.register');
            if (true !== $result) {
                $this->error($result);
            }
            // 验证码
            $captcha = $data['captcha'];
            $captcha == '' && $this->error('请输入验证码');
            if (!captcha_check($captcha)) {
                //验证失败
                $this->error('验证码错误或失效');
            }
            //注册时默认普通会员
            $data['groupid'] = 1;

            $data['status'] = 1;
            $data['integral'] = 0;
            $data['register_ip'] = get_client_ip();
            //数据库判断
            $Member = model('Member');
            $data['password'] = $Member->setPassword($data['password']);
            try {
                $Member->allowField(['groupid', 'account', 'password', 'integral', 'nickname', 'register_ip', 'status'])->save($data);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success('注册成功~', 'member/open/login');
        } else {
            $memberAppInfo = require_once(ROOT_PATH . 'application/member/data/appInfo.php');
            $this->assign('version', $memberAppInfo['version']);
            return $this->fetch();
        }
    }

    public function loginout() {
        session(null);
        return $this->redirect('member/open/login');
    }

}
