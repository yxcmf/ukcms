<?php

namespace app\admin\controller;

use think\facade\Cache;

class Role extends Common {

    public function index() {
        $nowUser = model('AdminUser')->where("id='" . session('user_info.uid') . "'")->find();
        $glist = model('AdminRole')->getRole("path like '%" . $nowUser->groupid . ",%'");
        $this->assign('glist', $glist);
        return $this->fetch();
    }

    public function add() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (empty($data['ids'])) {
                $this->error('至少需要勾选一个菜单权限');
            }
            $data['menu_ids'] = implode(',', $data['ids']);
            $data['status'] = isset($data['status']) ? intval($data['status']) : 0;
            $result = $this->validate($data, 'AdminRole');
            if (true !== $result) {
                return $this->error($result);
            }
            $AdminRole = model('AdminRole');
            try {
                $AdminRole->allowField(['path', 'names', 'description', 'menu_ids', 'orders', 'status'])->save($data);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            Cache::clear('db_admin_menu_tree');
            $this->success('角色添加成功~', url('index'));
        } else {
            $nowUser = model('AdminUser')->where("id='" . session('user_info.uid') . "'")->find();
            $AdminRole = model('AdminRole');
            $nowRole = $AdminRole->where('id', $nowUser->groupid)->find();
            $roles = $AdminRole->getRole("path like '%" . $nowUser->groupid . ",%'", 'id,path,names');
            $this->assign('roles', $roles);
            $this->assign('nowRole', $nowRole);
            return $this->fetch();
        }
    }

    public function edit($id = 0) {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['id'] = intval($data['id']);
            if ($data['id'] < 1) {
                $this->error('参数错误~');
            }
            if ($data['id'] == 1) {
                $this->error('此后台角色不可以编辑~');
            }
            if (empty($data['ids'])) {
                $this->error('至少需要勾选一个菜单权限');
            }
            $path = model('AdminRole')->where('id', $data['id'])->value('path');
            if ($path . $data['id'] . ',' == $data['path']) {
                $this->error('不可以作为自身的下级角色');
            }
            $data['menu_ids'] = implode(',', $data['ids']);
            $data['status'] = isset($data['status']) ? intval($data['status']) : 0;
            $result = $this->validate($data, 'AdminRole');
            if (true !== $result) {
                return $this->error($result);
            }
            $AdminRole = model('AdminRole');
            try {
                $AdminRole->allowField(['path', 'names', 'description', 'menu_ids', 'orders', 'status'])->save($data, ['id' => intval($data['id'])]);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            Cache::clear('db_admin_menu_tree');
            $this->success('角色编辑成功~', url('index'));
        } else {
            $id = intval($id);
            if ($id < 1) {
                $this->error('参数错误~');
            }
            if ($id == 1) {
                $this->error('此后台角色不可以编辑~');
            }
            $info = model('AdminRole')->where('id', $id)->find();
            $this->assign('info', $info);
            $nowUser = model('AdminUser')->where("id='" . session('user_info.uid') . "'")->find();
            $AdminRole = model('AdminRole');
            $nowRole = $AdminRole->where('id', $nowUser->groupid)->find();
            $roles = $AdminRole->getRole("path like '%" . $nowUser->groupid . ",%'", 'id,path,names');
            $this->assign('roles', $roles);
            $this->assign('nowRole', $nowRole);
            return $this->fetch();
        }
    }

    public function setState($id, $status) {
        $id = intval($id);
        if ($id == 1) {
            return '此后台角色不可修改';
        }
        $status = intval($status);
        if ($status != 0 && $status != 1)
            return '参数错误';
        if (model('AdminRole')->where('id', $id)->update(['status' => $status])) {
            Cache::clear('db_admin_menu_tree');
            return true;
        } else {
            return '设置失败';
        }
    }

    public function delete($id = 0) {
        $id = intval($id);
        if ($id == 1) {
            return '此后台角色不可以删除';
        }
        $unum = model('AdminUser')->where('groupid', '=', $id)->count();
        if ($unum > 0) {
            return '必须先删除此角色下用户~';
        }
        $child = model('AdminRole')->where('path', 'like', '%,' . $id . ',%')->value('id');
        if ($child) {
            return '此角色下还有子角色不可以删除~';
        }
        if (model('AdminRole')->get($id)->delete()) {
            Cache::clear('db_admin_menu_tree');
            return true;
        } else {
            return '数据库操作失败';
        }
    }

}
