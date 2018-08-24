<?php

namespace app\member\model;

use think\helper\Hash;

/**
 * 会员菜单
 */
class MemberMenu extends \think\Model {

    use \app\common\traits\TreeArray;

    protected $pk = 'id';
    protected $autoWriteTimestamp = true;

    //生成多维数组树结构会员中心菜单
    public function getMenu($ifUrl = false, $where = '') {
        $list = self::where($where)->order('orders,id')->column('id,pid,title,url_value,url_type,url_target');
        if (!empty($list)) {
            if ($ifUrl) {
                foreach ($list as &$vo) {
                    $vo['url'] = $this->buildUrl($vo);
                }
            }
            $list = $this->buildTree($list);
            $list = $this->addModelMenu($list, $ifUrl);
        }
        return $list;
    }

    //创建会员菜单链接地址
    protected function buildUrl($item) {
        if (empty($item['url_value'])) {
            return '#';
        } else {
            return 1 == $item['url_type'] ? url($item['url_value']) : $item['url_value'];
        }
    }

    //更新会员模型管理菜单
    protected function addModelMenu($list, $ifUrl, $rootId = 1) {
        if (isset($list[$rootId])) {
            //模型基础菜单不需更新
            $baseUrlValue = ['content/add', 'content/edit', 'content/delete', 'content/move'];

            //当前启用的系统模型
            $modelNow = model('Model')->where('status', 1)->Column('table,title');
            if (!empty($modelNow)) {
                //会员菜单已有模型
                $menuExit = isset($list[$rootId]['cnode']) ? $list[$rootId]['cnode'] : [];
                $menuUrlValue = [];
                foreach ($menuExit as $key => $vo) {
                    if (!in_array($vo['url_value'], $baseUrlValue)) {
                        $menuUrlValue[$vo['url_value']] = $vo['id'];
                    }
                }
                unset($menuExit);
                foreach ($modelNow as $key => $vo) {
                    $menuUrlValueKey = 'content/' . $key;
                    if (isset($menuUrlValue[$menuUrlValueKey])) {
                        //去掉要保留的菜单,剩下需要删除的
                        unset($menuUrlValue[$menuUrlValueKey]);
                    } else {
                        $item = [];
                        //新增菜单处理
                        $item['pid'] = $rootId;
                        $item['title'] = $vo;
                        $item['url_type'] = 1;
                        $item['url_value'] = $menuUrlValueKey;
                        $item['url_target'] = '_self';
                        $item['create_time'] = $item['update_time'] = time();
                        $item['id'] = self::insertGetId($item);

                        if ($ifUrl) {
                            $item['url'] = $this->buildUrl($item);
                        }
                        unset($item['create_time']);
                        unset($item['update_time']);
                        $list[$rootId]['cnode'][] = $item;
                    }
                }
                //删除的菜单处理
                if (!empty($menuUrlValue)) {
                    $menuDelIds = implode(',', $menuUrlValue);
                    self::where('id', 'in', $menuDelIds)->delete();
                    foreach ($list[$rootId]['cnode'] as $key => $vo) {
                        if (in_array($vo['id'], $menuUrlValue)) {
                            unset($list[$rootId]['cnode'][$key]);
                        }
                    }
                }
            } else {
                //清空会员菜单节点
                unset($list[$rootId]);
                //清空会员中心模型管理菜单
                $this->where('pid', $rootId)->where('url_value', 'not in', $baseUrlValue)->delete();
            }
        }
        return $list;
    }

}
