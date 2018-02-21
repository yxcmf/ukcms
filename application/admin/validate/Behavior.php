<?php

namespace app\admin\validate;

use think\Validate;

class Behavior extends Validate {

    //定义验证规则
    protected $rule = [
        'name|行为类名' => 'require|regex:^[a-z-A-Z\\\]*$|unique:behavior',
        'orders|排序' => 'require|number',
        'status|状态' => 'in:0,1'
    ];

}
