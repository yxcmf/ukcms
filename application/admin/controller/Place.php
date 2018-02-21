<?php

namespace app\admin\controller;

class Place extends Common {

    public function index() {
        $plist = model('Place')->order('orders,id desc')->paginate(20);
        $modelList = model('Model')->where('purpose', 'column')->order('orders')->column('id,title');
        $this->assign('modelList', $modelList);
        $this->assign('plist', $plist);
        $this->assign('page', $plist->render());
        return $this->fetch();
    }

    public function add() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, 'Place');
            if (true !== $result) {
                return $this->error($result);
            }
            $Place = model('Place');
            try {
                $Place->allowField(['mid', 'title', 'orders'])->save($data);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success('推荐位添加成功~', url('index'));
        } else {
            $modelList = model('Model')->where('purpose', 'column')->order('orders')->column('id,title');
            $this->assign('modelList', $modelList);
            return $this->fetch();
        }
    }

    public function edit($id = 0) {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, 'Place');
            if (true !== $result) {
                return $this->error($result);
            }
            $Place = model('Place');
            try {
                $Place->allowField(['mid', 'title', 'orders'])->save($data, ['id' => intval($data['id'])]);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
           $this->success('推荐位编辑成功~', url('index'));
        } else {
            $info = model('Place')->where('id', intval($id))->find();
            $modelList = model('Model')->where('purpose', 'column')->order('orders')->column('id,title');
            $this->assign('modelList', $modelList);
            $this->assign('info', $info);
            return $this->fetch();
        }
    }

    public function delete($id = 0) {
        $id = intval($id);
        if (model('Place')->where('id', $id)->delete()) {
            return true;
        } else {
            return '数据库操作失败';
        }
    }

}
