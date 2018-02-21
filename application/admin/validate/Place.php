<?php

namespace app\admin\validate;

use think\Validate;

class Place extends Validate {

    //定义验证规则
    protected $rule = [
        'mid|栏目模型ID' => 'number',
        'title|推荐位名称' => 'require|chs|unique:place',
        'orders|排序' => 'require|number',
    ];

}
