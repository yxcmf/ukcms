<?php

namespace app\admin\validate;

use think\Validate;

/**
 * 节点验证器
 */
class AdminMenu extends Validate {

    //定义验证规则
    protected $rule = [
        'pid|所属节点' => 'require|number',
        'title|节点标题' => 'require|chsAlphaNum|unique:admin_menu',
        'url_type|链接类型' => 'require|in:0,1',
        'url_value|节点链接地址' => 'unique:admin_menu',
        'url_target|链接打开方式' => 'require|alphaDash',
        'icon|节点图标' => 'regex:^[a-z-\s]*$',
        'ifvisible|是否菜单显示' => 'require|in:0,1',
    ];
    //定义验证提示
    protected $message = [
        'pid.require' => '请选择所属节点',
        'title.require' => '请填写标题',
        'url_value.unique' => '节点链接地址已经存在',
        'url_type.require' => '请选择链接类型',
        'icon.regex' => '节点图标格式错误',
        'url_target.require' => '请选择链接打开方式',
        'ifvisible.require' => '请选择是否显示到后台菜单',
    ];
    protected $scene = [
        'order' => ['pid' => 'number', 'orders' => 'require|number'],
    ];

}
