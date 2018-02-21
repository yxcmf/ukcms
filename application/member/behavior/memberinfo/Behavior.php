<?php

namespace app\member\behavior\memberinfo;

use app\member\model\Member;
use app\common\model\App;

/**
 * 获取会员信息
 */
class Behavior {

    /**
     * 执行行为 run方法是Behavior唯一的接口
     * @access public
     * @param mixed $params  行为参数
     * @return void
     */
    public function run($params) {
        //判断应用状态
        if (!App::checkon('member')) {
           return false;
        }
        //获取登录状态模板信息
        $account = Member::checkLogin();
        if ($account) {
            return [
                'view' => ['memberInfo' => Member::where('account', $account)->value('nickname')]
            ];
        } else {
            return [
                'view' => ['memberInfo' => false]
            ];
        }
    }

    public static function info() {
        return[
            'title' => '会员登录状态调用',
            'version' => '1.0.1',
            'author' => '路过',
            'dbTables' => [
//                '表名' => '介绍'
            ]
        ];
    }

    public static function getSqlPath() {
//        return dirname(__FILE__) . '/database.sql';
        return '';
    }

}
