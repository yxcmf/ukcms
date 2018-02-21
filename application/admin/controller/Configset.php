<?php

namespace app\admin\controller;

use think\Db;

class Configset extends Common {

    public function index($group = 'base') {
        $list = Db::view('config', 'id,name,title,type,orders,status,update_time')
                ->where('group', $group)
                ->view('field_type', 'title as ftitle', 'field_type.name=config.type', 'LEFT')
                ->order('orders,id desc')
                ->paginate(15);
        $this->assign([
            'list' => $list,
            'group' => $group,
            'groupArray' => config('config_group'),
            'page' => $list->render()
        ]);
        return $this->fetch();
    }

    public function add() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['status'] = isset($data['status']) ? intval($data['status']) : 0;
            $result = $this->validate($data, 'Config');
            if (true !== $result) {
                return $this->error($result);
            }
            $Config = model('Config');
            try {
                $Config->allowField(['name', 'title', 'group', 'type', 'value', 'options', 'remark', 'orders', 'status'])->save($data);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $result = $Config->allowField(['name', 'title', 'group', 'type', 'value', 'options', 'remark', 'orders', 'status'])->save($data);
            cache('system_config', null);
            $this->success('配置添加成功~', url('index', ['group' => $data['group']]));
        } else {
            $groupArray = config('config_group');
            $fieldType = Db::name('field_type')->order('orders')->column('name,title,ifoption,ifstring');
            $this->assign([
                'groupArray' => $groupArray,
                'fieldType' => $fieldType
            ]);
            return $this->fetch();
        }
    }

    public function edit($id) {
        $id = intval($id);
        if ($id < 1) {
            $this->error('参数错误~');
        }
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['id'] = $id;
            $data['status'] = isset($data['status']) ? intval($data['status']) : 0;
            $result = $this->validate($data, 'Config');
            if (true !== $result) {
                return $this->error($result);
            }
            $Config = model('Config');
            try {
                $Config->allowField(['name', 'title', 'group', 'type', 'value', 'options', 'remark', 'orders', 'status'])->save($data, ['id' => $data['id']]);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            cache('system_config', null);
            $this->success('配置编辑成功~', url('index', ['group' => $data['group']]));
        } else {
            $groupArray = config('config_group');
            $fieldType = Db::name('field_type')->order('orders')->column('name,title,ifoption,ifstring');
            $info = model('Config')->where('id', $id)->find();
            $this->assign([
                'groupArray' => $groupArray,
                'fieldType' => $fieldType,
                'info' => $info
            ]);
            return $this->fetch();
        }
    }

    public function delete($id) {
        if (!is_numeric($id) || $id < 0) {
            return '参数错误';
        }
        if (model('Config')->get($id)->delete()) {
            cache('system_config', null);
            return true;
        } else {
            return '数据库操作失败';
        }
    }

    public function changeorder($id, $num) {
        if (!is_numeric($num) || !is_numeric($id) || $id < 0) {
            return '参数错误';
        }
        if (model('Config')->where('id', $id)->update(['orders' => $num])) {
            return true;
        } else {
            return '设置排序失败';
        }
    }

    public function setstate($id, $status) {
        if (($status != 0 && $status != 1) || !is_numeric($id) || $id < 0) {
            return '参数错误';
        }
        if (model('Config')->where('id', $id)->update(['status' => $status])) {
            cache('system_config', null);
            return true;
        } else {
            return '设置失败';
        }
    }

}
