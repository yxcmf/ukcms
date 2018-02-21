<?php

namespace app\admin\validate;

use think\Validate;

class Tag extends Validate {

    //定义验证规则
    protected $rule = [
        'mid|栏目模型ID' => 'number',
        'title|TAG内容' => 'require|chsAlphaNum|unique:tag',
//        'url|链接地址' => 'regex:^[a-zA-Z\/\:\&\=\?\d]*$',
        'weight|权重' => 'number'
    ];

}
