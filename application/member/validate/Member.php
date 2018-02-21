<?php

namespace app\member\validate;

use think\Validate;

/**
 * 用户验证器
 */
class Member extends Validate {

    //定义验证规则
    protected $rule = [
        'groupid|角色分组' => 'require|number',
        'account|账户' => 'require|alphaNum|unique:member',
        'password|密码' => 'require|length:6,20|confirm',
        'nickname|昵称' => 'require|chs',
        'email|邮箱' => 'email|unique:member',
        'telephone|手机号' => 'mobile|unique:member',
        'headpic|头像' => 'number',
        'status|状态' => 'require|in:0,1',
    ];
    //定义验证场景
    protected $scene = [
        //登录
        'login' => ['account' => 'require|token', 'password' => 'require'],
        'register' => ['account' => 'require|token', 'password', 'nickname'],
        'update' => ['groupid', 'password' => 'length:6,20', 'nickname', 'telephone', 'email', 'status'],
        'updateSelf' => ['nickname', 'telephone', 'email'],
        'passwordself' => [ 'password']
    ];

}
