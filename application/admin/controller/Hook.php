<?php

namespace app\admin\controller;

class Hook extends Common {

    public function index() {
        $list = model('Hook')->order('orders desc')->paginate(15);
        $this->assign('list', $list);
        $this->assign('page', $list->render());
        return $this->fetch();
    }

    public function add() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, 'Hook');
            if (true !== $result) {
                return $this->error($result);
            }
            $data['status'] = isset($data['status'])? : 0;
            $data['ifsystem'] = 0;
            $Hook = model('Hook');
            try {
                $Hook->allowField(['name', 'title', 'orders', 'status', 'ifsystem'])->save($data);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            cache('hooks', null);
            $this->success('钩子添加成功~', url('index'));
        } else {
            return $this->fetch();
        }
    }

    public function edit($id = 0) {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['id'] = intval($data['id']);
            $result = $this->validate($data, 'Hook.edit');
            if (true !== $result) {
                return $this->error($result);
            }
            $data['status'] = isset($data['status'])? : 0;
            $Hook = model('Hook');
            try {
                $Hook->update($data, [], ['title', 'orders', 'status']);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            //更新钩子行为
            $HookBehavior = model('HookBehavior');
            $nowBehavior = $HookBehavior->where('hook', $data['name'])->column('id,behavior');
            $newBehavior = explode(',', $data['behavior']);
            if (!empty($newBehavior)) {
                $newBehavior = array_reverse($newBehavior, false);
            }
            $addBehavior = array_diff($newBehavior, $nowBehavior);
            $delBehavior = array_diff($nowBehavior, $newBehavior);
            $exitBehavior = array_intersect($nowBehavior, $newBehavior);
            //新增行为
            if (!empty($addBehavior)) {
                $adddata = [];
                foreach ($addBehavior as $key => $vo) {
                    if ('' != $vo) {
                        $adddata[$key]['hook'] = $data['name'];
                        $adddata[$key]['behavior'] = $vo;
                        $adddata[$key]['orders'] = array_keys($newBehavior, $vo)[0];
                    }
                }
                $HookBehavior->saveAll($adddata);
            }
            //删除行为
            if (!empty($delBehavior)) {
                $HookBehavior->where('hook', $data['name'])->where('behavior', 'in', $delBehavior)->delete();
            }
            //更新行为排序
            if (!empty($exitBehavior)) {
                $updatedata = [];
                foreach ($exitBehavior as $key => $vo) {
                    $updatedata[$key]['id'] = array_keys($nowBehavior, $vo)[0];
                    $updatedata[$key]['orders'] = array_keys($newBehavior, $vo)[0];
                }
                $HookBehavior->saveAll($updatedata);
            }
            //更新钩子行为结束
            cache('hooks', null);
            cache('hook_behaviors', null);
            $this->success('钩子编辑成功~', url('index'));
        } else {
            $id = intval($id);
            if ($id < 1) {
                $this->error('参数错误~');
            }
            $info = model('Hook')->where('id', $id)->find();
            //绑定当前钩子的行为
            $nowblist = model('HookBehavior')->where('hook', $info->name)->order("orders desc,id desc")->column('behavior');
            //所有行为
            $allblist = model('Behavior')->order("orders desc,id desc")->column('name,title');
            $this->assign([
                'info' => $info,
                'nowblist' => $nowblist,
                'allblist' => $allblist
            ]);
            return $this->fetch();
        }
    }

    public function changeOrder($id, $num) {
        $result = $this->validate(['id' => $id, 'num' => $num], ['id|钩子ID' => 'require|number', 'num|排序' => 'require|number']);
        if (true !== $result) {
            return $result;
        }
        if (model('Hook')->where('id', $id)->update(['orders' => $num])) {
            return true;
        } else {
            return '设置排序失败';
        }
    }

    public function setState($id, $status) {
        $id = intval($id);
        $status = intval($status);
        if (($status != 0 && $status != 1) || $id < 0) {
            return '参数错误';
        }
        if (model('Hook')->where('id', $id)->cache('hooks')->update(['status' => $status])) {
            return true;
        } else {
            return '设置失败';
        }
    }

    public function delete($id = 0) {
        $id = intval($id);
        $Hook = model('Hook');
        $hookinfo = $Hook->get($id);
        if (1 == $hookinfo->ifsystem) {
            return '不可以删除系统钩子';
        }
        model('HookBehavior')->where('hook', $hookinfo->name)->cache('hook_behaviors')->delete();
        if ($hookinfo->delete()) {
            cache('hooks', null);
            return true;
        } else {
            return '数据库操作失败';
        }
    }

}
