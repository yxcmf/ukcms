<?php

namespace app\member\model;

/**
 *  会员中心内容模型
 * @package app\member\model
 */
class Model extends \think\Model {

    // 自动写入时间戳
    protected $pk = 'id';
    protected $autoWriteTimestamp = true;

    
}
