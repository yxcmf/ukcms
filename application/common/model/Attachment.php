<?php

namespace app\common\model;

/**
 * 附件模型
 * @package app\admin\model
 */
class Attachment extends \think\Model {

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    static function getFileInfo($id = '', $field = 'path', $ifstatus = false) {
        if ('' == $id) {
            return '';
        }
        $isIds = strpos($id, ',') !== false;
        $isfields = strpos($field, ',') !== false;
        $ifcache = config('app_cache') && 'admin' != request()->module() ? true : false;
        if ($isIds) {
            $ids = explode(',', $id);
            $result = $ifstatus ? self::where('id', 'in', $ids)->where('status', 1)->cache($ifcache)->column($field) : self::where('id', 'in', $ids)->cache($ifcache)->column($field);
        } else {
            $result = $ifstatus ? self::where('id', $id)->where('status', 1)->cache($ifcache)->field($field)->find() : self::where('id', $id)->cache($ifcache)->field($field)->find();
        }
        return !($isfields || $isIds) ? $result[$field] : $result;
    }

}
