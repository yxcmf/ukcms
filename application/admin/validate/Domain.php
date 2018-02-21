<?php

namespace app\admin\validate;

use think\Validate;

class Domain extends Validate {

    //定义验证规则
    protected $rule = [
        'name|域名' => 'require|regex:^[a-z-A-Z\d\.]*$|unique:domain',
        'view_directory|模板文件目录' => 'require|alphaNum',
        'title|备注' => 'chsAlphaNum',
        'status|状态' => 'in:0,1'
    ];
    protected $scene = [
        'batch' => ['name', 'view_directory']
    ];

}
