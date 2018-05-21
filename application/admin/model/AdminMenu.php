<?php

namespace app\admin\model;

use think\facade\Url;
use think\Db;

class AdminMenu extends \think\Model
{

    protected $pk = 'id';
    protected $autoWriteTimestamp = true;
    public static $groupid = 0;

    public static function setGroupid($groupid)
    {
        self::$groupid = $groupid;
    }

    /**
     * 获取当前用户角色菜单权限id
     */
    public static function getRoleMenuIds($ifarr = true)
    {
        if (self::$groupid == 0) {
            throw new \Exception('未设置分组~');
        }
        $menuIds = Db::name('admin_role')->where('id', self::$groupid)->value('menu_ids');
        switch ($menuIds) {
            case '':
                throw new \Exception('账户所属角色没有分配菜单权限或已被删除');
            case '-1':
                return true;
            default:
                return $ifarr ? explode(',', $menuIds) : $menuIds;
        }
    }

    /**
     * 获取当前操作菜单id
     */
    public static function getNowMenuId()
    {
        $model = request()->module();
        $controller = request()->controller();
        $action = request()->action();
        $nowid = self::where('url_value', strtolower($model . '/' . $controller . '/' . $action))->value('id');
        if (!$nowid) {
            throw new \Exception($model . '/' . $controller . '/' . $action . '此功能链接没有添加到后台菜单中');
        }
        return $nowid;
    }

    /**
     * ajax操作判断当前操作是否有权限
     */
    public static function ajaxCheckAccess()
    {
        $nowid = self::getNowMenuId();
        $menuIdArray = self::getRoleMenuIds();
        if (is_array($menuIdArray)) {
            //判断当前菜单是否有权限访问
            if (!in_array($nowid, $menuIdArray)) {
                throw new \Exception('此账户所属角色没有访问权限');
            }
        }
    }

    /**
     * 非ajax操作获取后台菜单信息
     */
    public static function getMenuInfo()
    {
        $nowid = self::getNowMenuId();
        $menuIdArray = self::getRoleMenuIds();
        $where = "";
        if (is_array($menuIdArray)) {
            //判断当前菜单是否有权限访问
            if (!in_array($nowid, $menuIdArray)) {
                throw new \Exception('此账户所属角色没有访问权限');
            }
            //只获取有权限的菜单
            $where = "id in(" . implode(',', $menuIdArray) . ")";
        }
        list($tree, $refer) = self::buildTree($where, 'id,pid,title,url_value,url_type,url_target,icon,ifvisible');
        list($rootid, $path) = self::getNowInfo($nowid, $refer);
        return [$tree, $rootid, $nowid, $path];
    }

    public function getFirstUrl()
    {
        $menuIdString = self::getRoleMenuIds(false);
        $where = "ifvisible='1'";
        if (true !== $menuIdString) {
            $where .= " and id in ($menuIdString)";
        }
        return self::buildTree($where, 'id,pid,title,url_value,url_type,url_target,ifvisible', true);
    }

    /**
     * 创建树形多维数组和基于主键的数组引用
     */
    public static function buildTree($where, $fields, $firsturl = false)
    {
        $cacheKey = 'db_admin_menu_tree_' . $where . $fields . session('user_info.groupid');
        $treeAndRefer = cache($cacheKey);
        //缓存后台菜单信息
        if (false === $treeAndRefer) {
            $list = self::where($where)->order('orders,id')->column($fields);
            if (empty($list)) {
                throw new \Exception('当前用户所属角色拥有权限的菜单不存在');
            }
            $refer = [];
            foreach ($list as $key => $data) {
                if ($data['url_type'] == 1) {//处理url格式
                    $data['furl'] = empty($data['url_value']) ? '#' : Url::build($data['url_value']);
                } else {
                    $data['furl'] = $data['url_value'];
                }
                $list [$key] = $data;
                $refer[$data['id']] = &$list[$key];
            }
            $tree = [];
            foreach ($list as $key => $data) {
                // 判断是否存在parent  
                $parentId = $data['pid'];
                if (0 == $parentId) {
//                    $tree[$data['id']] = & $list[$key];
                    $tree[] = &$list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent = &$refer[$parentId];
                        $parent['cnode'][] = &$list[$key];
                    }
                }
            }
            //修改顶部菜单链接
            foreach ($tree as $key => $vo) {
                $ctree = $vo;
                while (!empty($ctree['cnode'])) {
                    if (!$ctree['cnode'][0]['ifvisible'] || empty($ctree['cnode'][0]['url_value'])) {
                        array_shift($ctree['cnode']);
                    } else {
                        $ctree = $ctree['cnode'][0];
                        $tree[$key]['url_value'] = $ctree['url_value'];
                        $tree[$key]['furl'] = $ctree['furl'];
                        $tree[$key]['url_target'] = $ctree['url_target'];
                    }
                }
                if (!$tree[$key]['url_value']) {
                    $tree[$key]['ifvisible'] = 0;
                }
            }
            $treeAndRefer = [$tree, $refer];
            cache($cacheKey, $treeAndRefer, null, 'db_admin_menu_tree');
        }
        if ($firsturl) {
            foreach ($treeAndRefer[0] as $vo) {
                if ($vo['url_value']) {
                    return $vo['furl'];
                }
            }
        } else {
            return $treeAndRefer;
        }
    }

    /**
     * 获取当前节点根节点和路径信息
     */
    public static function getNowInfo($rootid, $refer)
    {
        if ($rootid) {
            $path = []; //面包屑数组
            $path[] = $refer[$rootid];
            while ($refer[$rootid]['pid']) {
                $rootid = $refer[$rootid]['pid'];
                unset($refer[$rootid]['url_value']);
                if (isset($refer[$rootid]))
                    $path[] = $refer[$rootid];
            }

            if (!empty($path)) {
                $path = array_reverse($path);
            }
            return [$rootid, $path];
        } else
            return [0, []];
    }

    public function delNode($id)
    {
        if ($id > 0) {
            if (self::where('id', $id)->value('ifsystem')) {
                throw new \Exception('ID:' . $id . '是系统节点不可删除');
            }
            $idlist = self::where('pid', $id)->column('id');
            if (!empty($idlist)) {
                foreach ($idlist as $vo) {
                    $result = $this->delNode($vo);
                    if (!$result) {
                        return false;
                    }
                }
            }
            $result = self::where('id', $id)->delete();
            if (!$result) {
                throw new \Exception('ID:' . $id . '删除失败');
            }
        } else {
            throw new \Exception('非法操作，参数错误');
        }
    }

    public function hideNode($id)
    {
        if ($id > 0) {
            $idlist = self::where('pid', $id)->column('id');
            if (!empty($idlist)) {
                foreach ($idlist as $vo) {
                    $this->hideNode($vo);
                }
            }
            self::where('id', $id)->update(['ifvisible' => 0]);
        } else {
            throw new \Exception('非法操作，参数错误');
        }
    }

}
