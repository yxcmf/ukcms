<?php

namespace app\admin\validate;

use think\Validate;

class AdminRole extends Validate {

    //定义验证规则
    protected $rule = [
         'path|上级栏目' => 'require|regex:^[\d,]+$',
        'names|角色名称' => 'require|unique:admin_role',
//        'description|角色描述' => '',
        'menu_ids|菜单权限' => 'require',
        'orders|排序' => 'require|number',
        'status|状态' => 'require|number',
    ];

}
