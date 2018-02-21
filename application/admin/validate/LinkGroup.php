<?php

namespace app\admin\validate;

use think\Validate;

class LinkGroup extends Validate {

    //定义验证规则
    protected $rule = [
        'title|分组名称' => 'require|chsAlphaNum|unique:link_group',
        'name|分组英文标识' => 'require|alpha|unique:link_group',
    ];
}
