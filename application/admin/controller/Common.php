<?php

namespace app\admin\controller;

use app\admin\model\AdminMenu;
use app\admin\model\AdminUser;
use think\Controller;
use think\facade\Config;

class Common extends Controller {

    protected function initialize() {
        parent::initialize();
        //登录判断
        $groupid = AdminUser::checkLogin();
        //后台菜单功能
        if (!$this->request->isAjax()) {//区分是否是ajax控制器
            if (!$groupid) {
                $this->redirect('admin/open/login');
            }
            AdminMenu::setGroupid($groupid);
            Config::set('app.dispatch_success_tmpl', APP_PATH . 'admin' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'prompt_jump.html');
            Config::set('app.dispatch_error_tmpl', APP_PATH . 'admin' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'prompt_jump.html');
            Config::set('app.dispatch_dialog_tmpl', APP_PATH . 'admin' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'prompt_dialog.html');
            //获取后台菜单信息
            try {
                $menuReturn = AdminMenu::getMenuInfo();
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            list($MenuArray, $RootId, $NowMenuId, $NowMenuPath) = $menuReturn;

            $this->assign([
                'MenuJson' => json_encode($MenuArray, true),
                'RootId' => $RootId,
                'NowMenuId' => $NowMenuId,
                'NowMenuPath' => $NowMenuPath,
                'rootUrl' => ROOT_URL,
                'admin_layout' => config('admin_layout')
            ]);
        } else {
            if (!$groupid) {
                exit('未登陆');
            }
            AdminMenu::setGroupid($groupid);
            try {
                AdminMenu::ajaxCheckAccess();
            } catch (\Exception $ex) {
                exit($ex->getMessage());
            }
        }
    }

}
