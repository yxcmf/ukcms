<?php

namespace app\common\behavior;

use think\Db;

class Hook {

    /**
     * 执行行为 run方法是Behavior唯一的接口
     * @access public
     * @param mixed $params  行为参数
     * @return void
     */
    public function run($params) {
        if (defined('BIND_MODULE') && BIND_MODULE === 'install')
            return;

        $hook_behaviors = Db::name('hook_behavior')->where('status', 1)->order('orders desc,id desc')->cache('hook_behaviors')->select();
        $hooks = Db::name('hook')->where('status', 1)->cache('hooks')->column('name', 'name');
        $behaviors = Db::name('behavior')->where('status', 1)->cache('behaviors')->column('name', 'name');

        if ($hook_behaviors) {
            foreach ($hook_behaviors as $value) {
                if (isset($hooks[$value['hook']]) && isset($behaviors[$value['behavior']])) {
                    \think\facade\Hook::add($value['hook'], $value['behavior'] . '\\Behavior');
                }
            }
        }
    }

}
