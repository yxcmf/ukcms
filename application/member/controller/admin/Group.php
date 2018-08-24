<?php

namespace app\member\controller\admin;

class Group extends \app\admin\controller\Common {

    public function index() {
        $list = model('MemberGroup')->order('id desc')->paginate(20);
        $this->assign('list', $list);
        $this->assign('page', $list->render());
        return $this->fetch();
    }

    public function add() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['status'] = isset($data['status']) ? intval($data['status']) : 0;
            $data['menu_ids'] = is_array($data['ids']) ? implode(',', $data['ids']) : '';
            $result = $this->validate($data, 'MemberGroup');
            if (true !== $result) {
                return $this->error($result);
            }
            $MemberGroup = model('MemberGroup');

            try {
                $MemberGroup->allowField(['title', 'menu_ids', 'conf', 'status'])->save($data);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success('会员组添加成功~', url('index'));
        } else {
            $menu = model('MemberMenu')->getMenu();
            $this->assign('menu', json_encode($menu));
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
        if (model('MemberGroup')->where('id', $id)->update(['status' => $status])) {
            return true;
        } else {
            return '设置失败';
        }
    }

    public function delete($id = 0) {
        $id = intval($id);
        if (1 == $id) {
            return '不可以删除此分组';
        }
        if (model('MemberGroup')->destroy($id)) {
            model('Member')->where('groupid', $id)->update(['groupid' => 1]);
            return true;
        } else {
            return '数据库操作失败';
        }
    }

}
