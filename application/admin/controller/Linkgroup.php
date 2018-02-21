<?php

namespace app\admin\controller;

class Linkgroup extends Common {

    public function index() {
        $llist = model('LinkGroup')->order('id desc')->paginate(15);
        $this->assign('llist', $llist);
        $this->assign('page', $llist->render());
        return $this->fetch();
    }

    public function add() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, 'LinkGroup');
            if (true !== $result) {
                return $this->error($result);
            }
            $LinkGroup = model('LinkGroup');
            try {
                $LinkGroup->allowField(['title', 'name'])->save($data);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success('分组添加成功~', url('index'));
        } else {
            return $this->fetch();
        }
    }

    public function edit($id = 0) {
        if (!is_numeric($id)) {
            $this->error('参数错误~');
        }
        $info = model('LinkGroup')->where('id', $id)->find();
        if (empty($info)) {
            $this->error('链接分组不存在~');
        }
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['id'] = $id;
            $result = $this->validate($data, 'LinkGroup');
            if (true !== $result) {
                return $this->error($result);
            }
            $LinkGroup = model('LinkGroup');
            try {
                $LinkGroup->allowField(['title', 'name'])->save($data, ['id' => $data['id']]);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            if ($info['name'] != $data['name']) {
                model('Link')->where('group_name', $info['name'])->update(['group_name' => $data['name']]);
            }

            $this->success('分组编辑成功~', url('index'));
        } else {
            $this->assign('info', $info);
            return $this->fetch();
        }
    }

    public function delete($id = 0) {
        if (!is_numeric($id)) {
            return '参数错误~';
        }
        $LinkGroupNow=model('LinkGroup')->get($id);
        $linkId=model('Link')->where('group_name', $LinkGroupNow->name)->value(id);
        if($linkId){
            return '分组下还有链接没有删除';
        }
        if ($LinkGroupNow->delete()) {
            return true;
        } else {
            return '数据库操作失败';
        }
    }

}
