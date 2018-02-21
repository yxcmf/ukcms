<?php

namespace app\common\model;

use think\Db;
use think\Validate;

/**
 * 通用字段模型
 * @package app\common\model
 */
class ModelField extends \think\Model {

    protected $ext_table = '_data';
    // 自动写入时间戳
    protected $pk = 'id';
    protected $autoWriteTimestamp = true;

    public function getModelInfo($modelId, $fields = '*') {
        $ifcache = config('app_cache') ? true : false;
        $cacheKey = 'db_model_' . $modelId . $fields;
        $modeInfo = $ifcache ? cache($cacheKey) : false;
        $nowModel = request()->module();
        if (false === $modeInfo || 'admin' == $nowModel) {
            if ('*' == $fields || strpos($fields, ',') !== false) {
                $modeInfo = Db::name('model')->where('status', 1)->where('id', $modelId)->field($fields)->find();
            } else {
                $modeInfo = Db::name('model')->where('status', 1)->where('id', $modelId)->value($fields);
            }
            if (empty($modeInfo)) {
                throw new \Exception("ID为{$modelId}的模型已被冻结或不存在~");
            }
            if ($ifcache && 'admin' != $nowModel) {
                if (null === $modeInfo) {
                    $modeInfo = [];
                }
                cache($cacheKey, $modeInfo, null, 'db_model');
            }
        }
        return $modeInfo;
    }

    //查询解析模型数据用以构造from表单
    public function getFieldList($modelId, $id = null, $model = 'document', $where = "status='1'", $fields = "name,title,remark,type,value,options,ifmain,ifeditable,ifrequire,jsonrule") {
        $ifcache = config('app_cache') ? true : false;
        $cacheKey = 'db_model_field_' . $modelId . $id . $model . $where . $fields;
        $list = $ifcache ? cache($cacheKey) : false;
        $nowModel = request()->module();
        if (false === $list || 'admin' == $nowModel) {
            $list = self::where('model_id', $modelId)->where($where)->order('orders asc,id asc')->column($fields);
            if (!empty($list)) {
                //编辑信息时查询出已有信息
                if ($id) {
                    $modelInfo = Db::name('Model')->where('id', $modelId)->field('table,type')->find();
                    switch ($model) {
                        case 'column':
                            //查询主表信息单页栏目使用
                            $dataInfo = Db::name($modelInfo['table'])->where('cname', $id)->find();
                            break;
                        case 'document':
                            //查询主表信息列表栏目使用
                            $dataInfo = Db::name($modelInfo['table'])->where('id', $id)->find();
                            break;
                        default:
                            break;
                    }
                    //查询附表信息
                    if ($modelInfo['type'] == 2 && !empty($dataInfo)) {
                        $dataInfoExt = Db::name($modelInfo['table'] . $this->ext_table)->where('did', $dataInfo['id'])->find();
                    }
                }
                foreach ($list as &$value) {
                    if (isset($value['ifmain'])) {
                        if ($value['ifmain']) {
                            $value['fieldArr'] = 'modelField';
                            if (isset($dataInfo[$value['name']])) {
                                $value['value'] = $dataInfo[$value['name']];
                            }
                        } else {
                            $value['fieldArr'] = 'modelFieldExt';
                            if (isset($dataInfoExt[$value['name']])) {
                                $value['value'] = $dataInfoExt[$value['name']];
                            }
                        }
                    }
                    //解析字段关联规则
                    $dataRule = [];
                    if ('' != $value['jsonrule']) {
                        $dataRule = json_decode($value['jsonrule'], true);
                    }
                    if ('' != $value['options']) {
                        $value['options'] = parse_attr($value['options']);
                    } elseif (isset($dataRule['choose'])) {
                        $value['options'] = Db::name($dataRule['choose']['table'])->where($dataRule['choose']['where'])->limit($dataRule['choose']['limit'])->order($dataRule['choose']['order'])->column($dataRule['choose']['key'] . ',' . $dataRule['choose']['value']);
                    }
                    if ('' == $value['value'] && isset($dataRule['string'])) {
                        $stringArray = Db::name($dataRule['string']['table'])->where($dataRule['string']['where'])->limit($dataRule['string']['limit'])->order($dataRule['string']['order'])->column($dataRule['string']['key']);
                        if (!empty($stringArray)) {
                            $value['value'] = implode($dataRule['string']['delimiter'], $stringArray);
                        }
                    }
                    if ($value['type'] == 'checkbox') {
                        $value['value'] = (strlen($value['value']) > 2) ? substr($value['value'], 1, -1) : '';
                        $value['value'] = empty($value['value']) ? [] : explode(',', $value['value']);
                    }
                    if ($value['type'] == 'datetime') {
                        $value['value'] = empty($value['value']) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', $value['value']);
                    }
                    if ($value['type'] == 'date') {
                        $value['value'] = empty($value['value']) ? '' : date('Y-m-d', $value['value']);
                    }
                    if ($value['type'] == 'image') {
                        $value['param'] = ['dir' => 'images', 'module' => 'admin'];
                        if (isset($dataRule['thumb']['ifon'])) {
                            $value['param']['thumb'] = 1;
                            $value['param']['thumbsize'] = $dataRule['thumb']['size'];
                            $value['param']['thumbtype'] = $dataRule['thumb']['type'];
                        }
                    }
                    if ($value['type'] == 'images') {
                        $value['param'] = ['dir' => 'images', 'module' => 'admin'];
                        if (isset($dataRule['thumb']['ifon'])) {
                            $value['param']['thumb'] = 1;
                            $value['param']['thumbsize'] = $dataRule['thumb']['size'];
                            $value['param']['thumbtype'] = $dataRule['thumb']['type'];
                        }
                        if (!empty($value['value'])) {
                            $value['value'].=',';
                        }
                    }
                    if ($value['type'] == 'files') {
                        $value['param'] = ['dir' => 'files', 'module' => 'admin'];
                        if (isset($dataRule['file']['type'])) {
                            $value['param']['sizelimit'] = $dataRule['file']['size'];
                            $value['param']['extlimit'] = $dataRule['file']['type'];
                        }
                        if (!empty($value['value'])) {
                            $value['value'].=',';
                        }
                    }
                    if ($value['type'] == 'Ueditor') {
                        $value['value'] = htmlspecialchars_decode($value['value']);
                    }
                    if ($value['type'] == 'summernote') {
                        $value['value'] = htmlspecialchars_decode($value['value']);
                    }
                }
            }
            if ($ifcache && 'admin' != $nowModel) {
                cache($cacheKey, $list, null, 'db_model_field');
            }
        }
        return $list;
    }

    //添加模型内容
    public function addModelData($modeId, $data, $dataExt = []) {
        $modelTable = $this->getModelInfo($modeId, 'table');
        if (false == $modelTable) {
            throw new \Exception("ID为{$modeId}的模型不存在");
        }
        $data['uid'] = session('user_info.uid')? : 0;

        $dataAll = $this->dealModelPostData($modeId, $data, $dataExt);
        list($data, $dataExt) = $dataAll;
        if (!isset($data['create_time'])) {
            $data['create_time'] = request()->time();
        }
        if (!isset($data['update_time'])) {
            $data['update_time'] = request()->time();
        }
        Db::startTrans();
        try {
            //主表
            $id = Db::name($modelTable)->insertGetId($data);
            //附表
            if (!empty($dataExt)) {
                $dataExt['did'] = $id;
                Db::name($modelTable . $this->ext_table)->insert($dataExt);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    //编辑模型内容
    public function editModelData($modeId, $data, $dataExt = []) {
        $modelTable = $this->getModelInfo($modeId, 'table');
        if (false == $modelTable) {
            throw new \Exception("ID为{$modeId}的模型不存在");
        }
        $id = intval($data['id']);
        unset($data['id']);

        $dataAll = $this->dealModelPostData($modeId, $data, $dataExt);

        list($data, $dataExt) = $dataAll;
        if (!isset($data['update_time'])) {
            $data['update_time'] = request()->time();
        }
        Db::startTrans();
        try {
            //主表
            Db::name($modelTable)->where('id', $id)->update($data);
            //附表
            if (!empty($dataExt)) {
                Db::name($modelTable . $this->ext_table)->where('did', $id)->update($dataExt);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    //处理post提交的模型数据
    protected function dealModelPostData($modeId, $data, $dataExt = []) {
        //字段类型
        $filedTypeList = self::where('model_id', $modeId)->where('status', 1)->column('name,title,type,ifmain,ifeditable,ifrequire');
        //字段规则
        $fieldRule = Db::name('field_type')->column('name', 'vrule');
        foreach ($filedTypeList as $name => $vo) {
            $arr = $vo['ifmain'] ? 'data' : 'dataExt';
            if (!isset(${$arr}[$name])) {
                switch ($vo['type']) {
                    // 开关
                    case 'switch':
                        ${$arr}[$name] = 0;
                        break;
                    case 'checkbox':
                        ${$arr}[$name] = '';
                        break;
                }
            } else {
                if (is_array(${$arr}[$name])) {
                    ${$arr}[$name] = ',' . implode(',', ${$arr}[$name]) . ',';
                }
                switch ($vo['type']) {
                    // 开关
                    case 'switch':
                        ${$arr}[$name] = 1;
                        break;
                    // 日期+时间
                    case 'datetime':
                        if ($vo['ifeditable']) {
                            ${$arr}[$name] = strtotime(${$arr}[$name]);
                        }
                        break;
                    // 日期
                    case 'date':
                        ${$arr}[$name] = strtotime(${$arr}[$name]);
                        break;
                    case 'images':
                        if (!empty(${$arr}[$name])) {
                            $imageArr = explode(',', substr(${$arr}[$name], 0, -1));
                            $uniqueImageArr = array_unique($imageArr);
                            ${$arr}[$name] = implode(',', $uniqueImageArr);
                        }
                        break;
                    case 'files':
                        if (!empty(${$arr}[$name])) {
                            $fileArr = explode(',', substr(${$arr}[$name], 0, -1));
                            $uniqueFileArr = array_unique($fileArr);
                            ${$arr}[$name] = implode(',', $uniqueFileArr);
                        }
                        break;
                    // 百度编辑器
                    case 'Ueditor':
                        ${$arr}[$name] = htmlspecialchars(stripslashes(${$arr}[$name]));
                        break;
                    // 简洁编辑器
                    case 'summernote':
                        ${$arr}[$name] = htmlspecialchars(stripslashes(${$arr}[$name]));
                        break;
                }
            }
            //数据必填验证
            if ($vo['ifrequire'] && empty(${$arr}[$name])) {
                throw new \Exception("'" . $vo['title'] . "'必须填写~");
            }
            //数据格式验证
            if (!empty($fieldRule[$vo['type']]) && !empty(${$arr}[$name]) && !Validate::{$fieldRule[$vo['type']]}(${$arr}[$name])) {
                throw new \Exception("'" . $vo['title'] . "'格式错误~");
                //安全过滤
            } else {
                
            }
        }
        return[$data, $dataExt];
    }

    //删除模型内容
    public function deleteModelData($modeId, $ids) {
        $modelInfo = $this->getModelInfo($modeId, 'table,type');
        if (false == $modelInfo) {
            return false;
        }
        if (is_array($ids)) {
            Db::startTrans();
            try {
                Db::name($modelInfo['table'])->where('id', 'in', $ids)->delete();
                if (2 == $modelInfo['type']) {
                    Db::name($modelInfo['table'] . $this->ext_table)->where('did', 'in', $ids)->delete();
                }
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                throw new \Exception($e->getMessage());
            }
        } else {
            Db::startTrans();
            try {
                Db::name($modelInfo['table'])->where('id', $ids)->delete();
                if (2 == $modelInfo['type']) {
                    Db::name($modelInfo['table'] . $this->ext_table)->where('did', $ids)->delete();
                }
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                throw new \Exception($e->getMessage());
            }
        }
    }

}
