<?php

namespace app\member\validate;

use think\Validate;

/**
 * 用户分组验证器
 */
class MemberGroup extends Validate {

    //定义验证规则
    protected $rule = [
        'title|等级名称' => 'require|chsAlphaNum|unique:member_group',
        'menu_ids|菜单权限' => 'require',
        'status|状态' => 'require|in:0,1',
    ];

}
