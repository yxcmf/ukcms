<?php

namespace app\home\model;

use think\Db;

/**
 * 字段模型
 * @package app\index\model
 */
class ModelField extends \app\common\model\ModelField {

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
                $datalist = null == $page ?
                        Db::name($table)->where($where)->field($field)->order($order)->limit($limit)->select() :
                        Db::name($table)->where($where)->field($field)->order($order)->limit($limit)->paginate($listRows, $simple, $config)
                ;
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
                $dataInfo = Db::name($table)->where($where)->field($field)->order($order)->find();
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
        return url('home/' . $modelUse . '/content', ['name' => $cOrmName, 'id' => $id]);
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
