<?php

namespace app\member\controller\admin;

class User extends \app\admin\controller\Common {

    public function index() {
        $ulist = model('Member')->order('id desc')->paginate(20);
        $glist = model('MemberGroup')->column('id,title');
        $this->assign('ulist', $ulist);
        $this->assign('page', $ulist->render());
        $this->assign('glist', $glist);
        return $this->fetch();
    }

    public function add() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['status'] = isset($data['status']) ? intval($data['status']) : 0;

            $result = $this->validate($data, 'Member');
            if (true !== $result) {
                return $this->error($result);
            }
            $Member = model('Member');
            $data['password'] = $Member->setPassword($data['password']);
            $data['integral'] = 0;
            $data['register_ip'] = get_client_ip();
            try {
                $Member->allowField(['groupid', 'account', 'password', 'integral', 'nickname', 'email', 'telephone', 'register_ip', 'status'])->save($data);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success('会员添加成功~', url('index'));
        } else {
            $glist = model('MemberGroup')->column('id,title');
            $this->assign('glist', $glist);
            return $this->fetch();
        }
    }

    public function edit($id = 0) {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['id'] = intval($data['id']);
            $data['status'] = isset($data['status']) ? intval($data['status']) : 0;

            $result = $this->validate($data, 'Member.update');
            if (true !== $result) {
                return $this->error($result);
            }
            $Member = model('Member');
            if (empty($data['password'])) {
                unset($data['password']);
            } else {
                $data['password'] = $Member->setPassword($data['password']);
            }
            try {
                $Member->update($data, [], ['groupid', 'password', 'nickname', 'email', 'telephone', 'status']);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success('用户编辑成功~', url('index'));
        } else {
            $id = intval($id);
            if ($id < 1) {
                $this->error('参数错误~');
            }
            $info = model('Member')->where('id', $id)->find();
            $glist = model('MemberGroup')->column('id,title');
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
        if (model('Member')->where('id', $id)->update(['status' => $status])) {
            return true;
        } else {
            return '设置失败';
        }
    }

    public function delete($id = 0) {
        if ($this->request->isPost()) {
            $ids = input('post.ids/a', null, 'intval');
            if (empty($ids)) {
                $this->error('请先勾选需要删除的会员~');
            }
            if (model('Member')->destroy($ids)) {
                $this->success('会员删除成功~');
            } else {
                $this->error('会员删除失败~');
            }
        } else {
            $id = intval($id);
            if (model('Member')->destroy($id)) {
                return true;
            } else {
                return '数据库操作失败';
            }
        }
    }

}
