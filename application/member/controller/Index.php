<?php

namespace app\member\controller;

use think\Controller;

class Index extends Common {

    public function index() {
        return $this->fetch();
    }

    public function baseinfo() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['id'] = $this->memberInfo->id;
            // 验证数据
            $result = $this->validate($data, 'Member.updateSelf');
            if (true !== $result) {
                $this->error($result);
            }
            $Member = model('Member');
            $data['password'] = $Member->setPassword($data['password']);
            try {
                $Member->allowField(['nickname', 'telephone', 'email'])->save($data, ['id' => $data['id']]);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success('信息修改成功~');
        } else {
            $this->assign('info', $this->memberInfo);
            return $this->fetch();
        }
    }

    public function setting() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['id'] = $this->memberInfo->id;
            // 验证数据
            $result = $this->validate($data, 'Member.passwordself');
            if (true !== $result) {
                $this->error($result);
            }
            $Member = model('Member');
            $data['password'] = $Member->setPassword($data['password']);
            try {
                $Member->allowField(['password'])->save($data, ['id' => $data['id']]);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success('密码修改成功~');
        } else {
            return $this->fetch();
        }
    }

}
