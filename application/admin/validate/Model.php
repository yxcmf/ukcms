<?php

namespace app\admin\validate;

use think\Validate;

class Model extends Validate {

    //定义验证规则
    protected $rule = [
        'purpose|模型用途' => 'require|alpha',
        'title|模型名称' => 'require|chs|unique:model',
        'table|表名' => 'require|alphaNum|notIn:add,edit,delete,setstate,changeorder|unique:model',
        'type|模型类型' => 'require|number',
        'orders|排序' => 'require|number',
        'ifsub|是否允许投稿' => 'in:0,1',
        'status|状态' => 'in:0,1'
    ];
    //定义验证场景
    protected $scene = [
        'edit' => ['title', 'table', 'orders', 'ifsub', 'status']
    ];

}
