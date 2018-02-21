<?php

namespace app\admin\validate;

use think\Validate;

class Link extends Validate {

    //定义验证规则
    protected $rule = [
        'group_name|分组英文标识' => 'require|alpha',
        'title|链接标题' => 'require|chsAlphaNum|unique:link',
        //'url|链接地址' => 'require',
        'picture|链接图片' => 'number',
//        'content|描述' => 'chsDash',
        'start_time|起效时间' => 'date',
        'end_time|失效时间' => 'date',
        'orders|排序' => 'require|number',
    ];

}
