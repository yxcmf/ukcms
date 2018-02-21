<?php

namespace app\home\model;

/**
 * 广告位模型
 */
class Link extends \think\Model {

    protected $pk = 'id';
    protected $autoWriteTimestamp = true;

    public function getLinkList($group, $intime, $key) {
        $ifcache = config('app_cache') ? true : false;
        $cacheKey = $group . $intime . $key;
        $list = $ifcache ? cache($cacheKey) : false;
        if (false === $list) {
            if ($key) {
                $where = "id='$key'";
            } else {
                $where = "" == $group ? "" : "group_name='$group'";
                if ('yes' == $intime) {
                    $nowTime = time();
                    $timeWhere = "(start_time=0 or start_time<$nowTime) and (end_time=0 or end_time>$nowTime)";
                    $where.="" == $where ? "$timeWhere" : " and " . $timeWhere;
                }
            }
            $list = [];
            $sqllist = self::where($where)->order('orders desc,id desc')->column('id,title,url,picture,content');
            if (!empty($sqllist)) {
                foreach ($sqllist as $value) {
                    $value['url'] = '' == $value['url'] ? '#' : ((strpos($value['url'], '://') !== false) ? $value['url'] : url($value['url']));
                    $value['picture'] = model('attachment')->getFileInfo($value['picture']);
                    $list[] = $value;
                }
            }
            if ($ifcache) {
                cache($cacheKey, $list, null, 'db_link');
            }
        }
        return $list;
    }

}
