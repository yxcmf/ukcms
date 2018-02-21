<?php

namespace app\admin\controller;

class User extends Common {

    public function index() {
        $nowUser = model('AdminUser')->where("id='" . session('user_info.uid') . "'")->find();
        $groupIds = model('AdminRole')->where('path', 'like', "%" . $nowUser->groupid . ",%")->column('id');
        $ulist = model('AdminUser')->where("groupid", "in", $groupIds)->order('orders,id desc')->paginate(20);
        $glist = model('AdminRole')->column('id,names');
        $this->assign('ulist', $ulist);
        $this->assign('page', $ulist->render());
        $this->assign('glist', $glist);
        return $this->fetch();
    }

    public function add() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['status'] = isset($data['status']) ? intval($data['status']) : 0;
            $data['create_time'] = time();
            $result = $this->validate($data, 'AdminUser');
            if (true !== $result) {
                return $this->error($result);
            }
            $AdminUser = model('AdminUser');
            $data['password'] = $AdminUser->setPassword($data['password']);
            try {
                $AdminUser->allowField(['groupid', 'username', 'realname', 'password', 'mobile', 'email', 'create_time', 'orders', 'status'])->save($data);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success('用户添加成功~', url('index'));
        } else {
            $nowUser = model('AdminUser')->where("id='" . session('user_info.uid') . "'")->find();
            $glist = model('AdminRole')->getRole("path like '%" . $nowUser->groupid . ",%'", 'id,names,path');
            $this->assign('glist', $glist);
            return $this->fetch();
        }
    }

    public function edit($id = 0) {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['id'] = intval($data['id']);
            $data['status'] = isset($data['status']) ? intval($data['status']) : 0;

            $result = $this->validate($data, 'AdminUser.update');
            if (true !== $result) {
                return $this->error($result);
            }
            $AdminUser = model('AdminUser');
            if (empty($data['password'])) {
                unset($data['password']);
            } else {
                $data['password'] = $AdminUser->setPassword($data['password']);
            }
            try {
                $AdminUser->update($data, [], ['id', 'groupid', 'username', 'password', 'realname', 'mobile', 'email', 'orders', 'status']);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success('用户编辑成功~', url('index'));
        } else {
            $id = intval($id);
            if ($id < 1) {
                $this->error('参数错误~');
            }
            $info = model('AdminUser')->where('id', $id)->find();
            $nowUser = model('AdminUser')->where("id='" . session('user_info.uid') . "'")->find();
            $glist = model('AdminRole')->getRole("path like '%" . $nowUser->groupid . ",%'", 'id,names,path');
            $this->assign('info', $info);
            $this->assign('glist', $glist);
            return $this->fetch();
        }
    }

    public function setState($id, $status) {
        $id = intval($id);
        $status = intval($status);
        if ($id <= 0 || ($status != 0 && $status != 1))
            return '参数错误';
        if (0 == $status) {
            $snum = model('AdminUser')->where('groupid', 1)->where('status', 1)->where('id', 'neq', $id)->count();
            if ($snum < 1) {
                return '至少一个超级管理账户保持启用状态~';
            }
        }
        if (model('AdminUser')->where('id', $id)->update(['status' => $status])) {
            return true;
        } else {
            return '设置失败';
        }
    }

    public function delete($id = 0) {
        if ($this->request->isPost()) {
            $ids = input('post.ids/a', null, 'intval');
            if (empty($ids)) {
                $this->error('请先勾选需要删除的用户~');
            }
            $AdminUser = model('AdminUser');
            $snum = $AdminUser->where('groupid', 1)->where('id', 'not in', $ids)->count();
            if ($snum < 1) {
                $this->error('必须保留至少一个超级管理账户~');
            }
            if ($AdminUser->destroy($ids)) {
                $this->success('用户删除成功~');
            } else {
                $this->error('用户删除失败~');
            }
        } else {
            $id = intval($id);
            $AdminUser = model('AdminUser');
            $snum = $AdminUser->where('groupid', '=', 1)->count();
            $user = $AdminUser->get($id);
            if ($user->groupid == 1 && $snum <= 1) {
                return '必须保留至少一个超级管理账户~';
            }
            if ($user->delete()) {
                return true;
            } else {
                return '数据库操作失败';
            }
        }
    }

}
