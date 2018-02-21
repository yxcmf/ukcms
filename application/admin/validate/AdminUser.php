<?php

namespace app\admin\validate;

use think\Validate;

/**
 * 用户验证器
 */
class AdminUser extends Validate {

    //定义验证规则
    protected $rule = [
        'groupid|角色分组' => 'require|number',
        'username|账户' => 'require|alphaNum|unique:admin_user',
        'head_pic|头像' => 'number',
        'password|密码' => 'require|length:6,20|confirm',
        'realname|姓名' => 'require|chs',
        'mobile|手机号' => 'mobile|unique:admin_user',
        'email|邮箱' => 'email|unique:admin_user',
        'orders|排序' => 'require|number',
        'status|状态' => 'require|number',
    ];
    //定义验证提示
    protected $message = [
        'username.require' => '请输入账户名',
        'email.require' => '邮箱不能为空',
        'email.email' => '邮箱格式不正确',
        'email.unique' => '该邮箱已存在',
        'password.require' => '密码不能为空',
        'password.length' => '密码长度6-20位',
        'mobile.regex' => '手机号不正确',
    ];
    //定义验证场景
    protected $scene = [
        //登录
        'login' => ['username' => 'require|token', 'password' => 'require'],
        'update' => ['groupid', 'username', 'password' => 'length:6,20', 'realname', 'mobile', 'email', 'status', 'orders'],
        'updateSelf' => ['head_pic', 'password' => 'length:6,20|confirm', 'realname', 'mobile', 'email'],
        'updateSelfSuper' => ['username', 'head_pic', 'password' => 'length:6,20|confirm', 'realname', 'mobile', 'email']
    ];

}
