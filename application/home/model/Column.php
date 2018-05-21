<?php

namespace app\home\model;

/**
 * 前台栏目模型
 */
class Column extends \think\Model {

    use \app\common\traits\TreeArray,
        \app\common\traits\SortArray;

    // 自动写入时间戳
    protected $pk = 'id';
    protected $autoWriteTimestamp = true;

    public function getColumn($type = 'tree', $column = 'id,path,name,title,type,url', $key = 'id', $rootId = 0, $select = 'show') {
        $ifcache = config('app_cache') ? true : false;
        $cacheKey = 'db_column_' . $type . $column . $key . $rootId . $select;
        $list = $ifcache ? cache($cacheKey) : false;
        if (false === $list) {
            $where = '1=1';
            switch ($select) {
                case 'show':
                    $where = "status='1'";
                    break;
                case 'hide':
                    $where = "status='0'";
                default:
                    break;
            }
            if ($rootId) {
                $where.='' == $where ? "path like '%,$rootId,%'" : " and path like '%,$rootId,%'";
            }
            $list = self::where($where)->order('orders,id')->column($column);

            if (!empty($list)) {
                foreach ($list as &$vo) {
                    if (isset($vo['url'])) {
                        $vo['url'] = self::buildUrl($vo['type'], $vo['name'], $vo['url']);
                    }
                    if (isset($vo['cover_picture']) && $vo['cover_picture']) {
                        $vo['cover'] = model('attachment')->getFileInfo($vo['cover_picture'], 'path');
                    }
                }
                switch ($type) {
                    case 'tree':
                        $list = $this->buildTree($list, $rootId);
                    case 'sort':
                        $list = $this->resort($list, $key);
                }
            }
            if ($ifcache) {
                cache($cacheKey, $list, null, 'db_column');
            }
        }
        return $list;
    }

    public function getColumnInfo($name, $fields = '*') {
        $ifcache = config('app_cache') ? true : false;
        $cacheKey = 'db_column_' . $name . $fields;
        $info = $ifcache ? cache($cacheKey) : false;
        if (false === $info) {
            $info = self::where('name', $name)->field($fields)->find();
            if (empty($info)) {
                throw new \Exception('栏目不存在~');
            }
            $info['cover'] = empty($info['cover_picture']) ? '' : model('attachment')->getFileInfo($info['cover_picture']);
            if (isset($info['type']) && isset($info['name']) && isset($info['url'])) {
                if (2 == $info['type'] && $info['url']) {
                    $info['condition'] = $info['url'];
                }
                $info['url'] = self::buildUrl($info['type'], $info['name'], $info['url']);
            }
            if ($ifcache) {
                if (null === $info) {
                    $info = [];
                }
                cache($cacheKey, $info, null, 'db_column');
            }
        }
        return $info;
    }

    public function getBreadcrumb($path) {
        $ifcache = config('app_cache') ? true : false;
        $cacheKey = 'db_column_' . $path;
        $crumbArray = $ifcache ? cache($cacheKey) : [];
        if (empty($crumbArray)) {
            $crumbList = self::where("id", "in", $path)->order('path,orders')->column('name,type,title,url');
            if (!empty($crumbList)) {
                foreach ($crumbList as $key => $vo) {
                    $crumbArray[$key]['title'] = $vo['title'];
                    $crumbArray[$key]['url'] = self::buildUrl($vo['type'], $vo['name'], $vo['url']);
                }
            }
            if ($ifcache) {
                cache($cacheKey, $crumbArray, null, 'db_column');
            }
        }
        return $crumbArray;
    }

    public static function buildUrl($type, $name, $url = '') {
        switch ($type) {
            case 3://自定义链接
                $url = empty($url) ? '#' : ((strpos($url, '://') !== false) ? $url : url($url));
                break;
            default:
                $url = url('column/index', ['name' => $name]);
                break;
        }
        return $url;
    }

}
