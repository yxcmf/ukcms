<?php

namespace app\common\behavior;

use app\common\model\Config as ConfigModel;
use think\facade\Config as systemConfig;

/**
 * 初始化配置信息行为
 * 将系统配置信息合并到本地配置
 * @package app\common\behavior
 */
class Config {

    /**
     * 执行行为 run方法是Behavior唯一的接口
     * @access public
     * @param mixed $params  行为参数
     * @return void
     */
    public function run($params) {
        if (defined('BIND_MODULE') && BIND_MODULE === 'install')
            return;
        // 读取系统配置
        $system_config = cache('system_config');
        if (empty($system_config)) {
            $system_config = ConfigModel::getConfig();
            cache('system_config', $system_config);
        }

        // 设置配置信息
        if (!empty($system_config)) {
            foreach ($system_config as $key => $value) {
                systemConfig::set($key, $value);
            }
        }
    }

}
