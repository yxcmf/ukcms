<?php

namespace app\home\model;

use think\Db;

/**
 * 字段模型
 * @package app\index\model
 */
class ModelField extends \app\common\model\ModelField {

    public function getDataInfo($table, $where, $field, $extfield = '', $order = '', $key = 0) {
        $ifcache = config('app_cache') ? true : false;
        $cacheKey = 'db_' . $table . '_' . $where . $field . $extfield . $key;
        $dataInfo = $ifcache ? cache($cacheKey) : false;
        if (false === $dataInfo) {
            $info = Db::name('model')->where('table', $table)->field('id,type,purpose')->find();
            if ($key) {
                $where = "id='$key'";
            }
            //判断表是否是模型表
            if (empty($info)) {
                try {
                    $dataInfo = Db::name($table)->where($where)->field($field)->order($order)->find();
                } catch (\Exception $exc) {
                   return [];
                }
            } else {
                if ($info['type'] == 2 && $field && $extfield) {
                    $extTable = $table . $this->ext_table;
                    $dataInfo = Db::view($table, $field)
                            ->where($where)
                            ->order($order)
                            ->view($extTable, $extfield, $table . '.id=' . $extTable . '.did', 'LEFT')
                            ->find();
                } else {
                    $dataInfo = Db::name($table)->where($where)->field($field)->order($order)->find();
                }
                if (!empty($dataInfo)) {
                    $fieldinfo = self::where('model_id', $info['id'])->where('status', 1)->column('name,type,options,jsonrule,ifmain');
                    $dataInfo = $this->dealModelShowData($fieldinfo, $dataInfo);
                    if (isset($dataInfo['id']) && (isset($dataInfo['cname']) || 'independence' == $info['purpose'])) {
                        $dataInfo['url'] = $this->buildContentUrl($dataInfo['id'], $info['purpose'], $info['purpose'] == 'column' ? $dataInfo['cname'] : $table);
                    }
                }
            }
            if ($ifcache) {
                if (null === $dataInfo) {
                    $dataInfo = [];
                }
                cache($cacheKey, $dataInfo, null, 'db_' . $table);
            }
        }
        return $dataInfo;
    }

}
