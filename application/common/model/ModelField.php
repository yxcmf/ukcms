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

    public function getDataList($table, $where, $field, $extfield, $order, $limit = '', $page = null, $cid = 0, $place = '') {
        $ifcache = config('app_cache') ? true : false;
        //去除时间对缓存key的影响
        $timePlace = strpos($where, 'create_time');
        if ($timePlace !== false) {
            $kwhere = str_replace(substr($where, $timePlace + 13, 10), '', $where);
        } else {
            $kwhere = $where;
        }
        $cacheKey = 'db_' . $table . '_' . $kwhere . $field . $extfield . $order . $limit . $cid . $place;
        if (null !== $page) {
            $listRows = $page[0];
            $simple = $page[1];
            $config = $page[2];
            $cacheKey.=$listRows . $config['page'];
        } else {
            $cacheKey.=$page;
        }
        $datalist = $ifcache ? cache($cacheKey) : false;
        if (false === $datalist) {
            $info = Db::name('model')->where('table', $table)->field('id,type,purpose')->find();
            //判断表是否是模型表
            if (empty($info)) {
//                if(false == Db::query("SHOW TABLES LIKE '".config('database.prefix').$table."'")){
//                    return [];
//                }
                try {
                    $datalist = null == $page ?
                        Db::name($table)->where($where)->field($field)->order($order)->limit($limit)->select() :
                        Db::name($table)->where($where)->field($field)->order($order)->limit($limit)->paginate($listRows, $simple, $config)
                ;
                } catch (\Exception $exc) {
                   return [];
                }
            } else {
                //推荐条件
                if (!empty($place) && $info['purpose'] == 'column') {
                    if (false !== strpos($place, ',')) {
                        $placeArr = explode(',', $place);
                        $placeWhere = '';
                        foreach ($placeArr as $vo) {
                            if ('' != trim($vo)) {
                                $placeWhere.= "places like '%," . $vo . ",%' or ";
                            }
                        }
                        if ('' != $placeWhere) {
                            $placeWhere = '(' . substr($placeWhere, 0, -4) . ')';
                            $where.=$where ? ' and ' . $placeWhere : $placeWhere;
                        }
                    } else {
                        $where.=$where ? " and places like '%," . $place . ",%'" : "places like '%," . $place . ",%'";
                    }
                }
                //栏目条件
                if ($cid && $info['purpose'] == 'column') {
                    $cnamesArr = $this->getColumnNames($cid, $info['id'], $ifcache);
                    $cnamesWhere = '';
                    foreach ($cnamesArr as $vo) {
                        $cnamesWhere.= "cname ='$vo' or ";
                    }
                    if ('' != $cnamesWhere) {
                        $cnamesWhere = '(' . substr($cnamesWhere, 0, -4) . ')';
                        $where.=$where ? ' and ' . $cnamesWhere : $cnamesWhere;
                    } else {
                        $where = "1=2";
                    }
                }
//            echo $where;
                if ($info['type'] == 2 && $field && $extfield) {
                    $extTable = $table . $this->ext_table;
                    $datalist = null == $page ?
                            Db::view($table, $field)
                                    ->where($where)
                                    ->view($extTable, $extfield, $table . '.id=' . $extTable . '.did', 'LEFT')
                                    ->order($order)
                                    ->limit($limit)
                                    ->select() :
                            Db::view($table, $field)
                                    ->where($where)
                                    ->view($extTable, $extfield, $table . '.id=' . $extTable . '.did', 'LEFT')
                                    ->order($order)
                                    ->limit($limit)
                                    ->paginate($listRows, $simple, $config)
                    ;
                } else {
                    $datalist = null == $page ?
                            Db::name($table)->where($where)->field($field)->order($order)->limit($limit)->select() :
                            Db::name($table)->where($where)->field($field)->order($order)->limit($limit)->paginate($listRows, $simple, $config)
                    ;
                }

                //数据格式化处理
                if (!empty($datalist)) {
                    $fieldinfo = self::where('model_id', $info['id'])->where('status', 1)->column('name,type,options,jsonrule,ifmain');
                    foreach ($datalist as $key => $vo) {
                        $vo = $this->dealModelShowData($fieldinfo, $vo);
                        if (isset($vo['id']) && (isset($vo['cname']) || 'independence' == $info['purpose'])) {
                            $vo['url'] = $this->buildContentUrl($vo['id'], $info['purpose'], $info['purpose'] == 'column' ? $vo['cname'] : $table);
                        }
                        $datalist[$key] = $vo;
                    }
                }
            }
            if ($ifcache) {
                cache($cacheKey, $datalist, null, 'db_' . $table);
            }
        }
        return $datalist;
    }
    
    //获取同模型栏目以及子栏目名称
    protected function getColumnNames($cid, $mid, $ifcache = false) {
        if (false !== strpos($cid, ',')) {
            $cidArr = explode(',', $cid);
            $cwhere = '';
            foreach ($cidArr as $vo) {
                $cwhere.="path like '%," . $vo . ",%' or ";
            }
            $cwhere = substr($cwhere, 0, -4);
            return Db::name('column')->where('model_id', $mid)->where(function ($query) use($cidArr, $cwhere) {
                        $query->where('id', 'in', $cidArr)->whereor($cwhere);
                    })->column('name');
        } else {
            return Db::name('column')->where('model_id', $mid)->where(function ($query) use($cid) {
                        $query->where('id', $cid)->whereor('path', 'like', '%,' . $cid . ',%');
                    })->column('name');
        }
    }
    
    //创建内容链接
    public function buildContentUrl($id, $modelUse, $cOrmName) {
        return url($modelUse . '/content', ['name' => $cOrmName, 'id' => $id]);
    }
    
    //格式化显示数据
    protected function dealModelShowData($fieldinfo, $data) {
        $newdata = [];
        foreach ($data as $key => $value) {
//            $newdata[$key]['title'] = $fieldinfo[$key]['title'];
            switch ($fieldinfo[$key]['type']) {
                case 'array':
                    $newdata[$key] = parse_attr($newdata[$key]);
                    break;
                case 'radio':
                    if (!empty($value)) {
                        if (!empty($fieldinfo[$key]['options'])) {
                            $optionArr = parse_attr($fieldinfo[$key]['options']);
                            $newdata[$key] = isset($optionArr[$value]) ? $optionArr[$value] : $value;
                        } elseif (!empty($fieldinfo[$key]['jsonrule'])) {
                            $dataRule = json_decode($fieldinfo[$key]['jsonrule'], true);
                            $newdata[$key] = Db::name($dataRule['choose']['table'])->where($dataRule['choose']['key'], $value)->value($dataRule['choose']['value']);
                        }
                    }
                    break;
                case 'select':
                    if (!empty($value)) {
                        if (!empty($fieldinfo[$key]['options'])) {
                            $optionArr = parse_attr($fieldinfo[$key]['options']);
                            $newdata[$key] = isset($optionArr[$value]) ? $optionArr[$value] : $value;
                        } elseif (!empty($fieldinfo[$key]['jsonrule'])) {
                            $dataRule = json_decode($fieldinfo[$key]['jsonrule'], true);
                            $newdata[$key] = Db::name($dataRule['choose']['table'])->where($dataRule['choose']['key'], $value)->value($dataRule['choose']['value']);
                        }
                    }
                    break;
                case 'checkbox':
                    $value = (strlen($value) > 2) ? substr($value, 1, -1) : '';
                    if (!empty($value)) {
                        if ('places' == $key) {
                            //定位选项获取
                            $newdata[$key] = Db::name('place')->where('id', 'IN', $value)->order('orders,id desc')->column('id,title');
                        } else {
                            //option选项
                            if (!empty($fieldinfo[$key]['options'])) {
                                $optionArr = parse_attr($fieldinfo[$key]['options']);
                                $valueArr = explode(',', $value);
                                foreach ($valueArr as $v) {
                                    if (isset($optionArr[$v])) {
                                        $newdata[$key][$v] = $optionArr[$v];
                                    } elseif ($v) {
                                        $newdata[$key][$v] = $v;
                                    }
                                }
                                //其他表关联
                            } elseif (!empty($fieldinfo[$key]['jsonrule'])) {
                                $dataRule = json_decode($fieldinfo[$key]['jsonrule'], true);
                                $newdata[$key] = Db::name($dataRule['choose']['table'])->where($dataRule['choose']['where'])->where($dataRule['choose']['key'], 'IN', $value)->limit($dataRule['choose']['limit'])->order($dataRule['choose']['order'])->column($dataRule['choose']['key'] . ',' . $dataRule['choose']['value']);
                            } else {
                                $newdata[$key] = [];
                            }
                        }
                    } else {
                        $newdata[$key] = [];
                    }
                    break;
                case 'image':
                    $newdata[$key] = empty($value) ? ['path' => '', 'thumb' => ''] : model('attachment')->getFileInfo($value, 'path,thumb', true);
                    if ('' == $newdata[$key] ['thumb']) {
                        $newdata[$key] ['thumb'] = $newdata[$key] ['path'];
                    }
                    break;
                case 'images':
                    $newdata[$key] = empty($value) ? [] : model('attachment')->getFileInfo($value, 'id,path,thumb', true);
                    break;
                case 'files':
                    $newdata[$key] = empty($value) ? [] : model('attachment')->getFileInfo($value, 'id,name,path,size', true);
                    break;
                case 'tags':
                    $newdata[$key] = explode(',', $value);
                    break;
                case 'Ueditor':
                    $newdata[$key] = htmlspecialchars_decode($value);
                    break;
                case 'summernote':
                    $newdata[$key] = htmlspecialchars_decode($value);
                    break;
                default:
                    $newdata[$key] = $value;
                    break;
            }
            if (!isset($newdata[$key])) {
                $newdata[$key] = '';
            }
        }
        return $newdata;
    }
}
