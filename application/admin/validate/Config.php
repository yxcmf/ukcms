<?php

namespace app\admin\validate;

use think\Validate;

class Config extends Validate {

    // 定义验证规则
    protected $rule = [
        'group|配置分组' => 'require',
        'type|配置类型' => 'require|alpha',
        'name|配置名称' => 'require|regex:^[a-zA-Z]\w{0,39}$|unique:config',
        'title|配置标题' => 'require|chsAlphaNum',
    ];

}
