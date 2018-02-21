<?php

namespace app\admin\validate;

use think\Validate;

class Hook extends Validate {

    //定义验证规则
    protected $rule = [
        'name|钩子名称' => 'require|alphaDash|unique:hook',
        'title|钩子标题' => 'require|chsAlphaNum|unique:hook',
        'orders|排序' => 'require|number',
        'status|状态' => 'in:0,1'
    ];
    protected $scene = [
        'edit' => ['title', 'orders', 'status']
    ];

}
