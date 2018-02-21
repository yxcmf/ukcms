<?php

namespace app\admin\model;


/**
 * 后台配置模型
 * @package app\admin\model
 */
class FieldType extends \think\Model  {

    // 自动写入时间戳
    protected $pk = 'name';
    protected $autoWriteTimestamp = false;

}
