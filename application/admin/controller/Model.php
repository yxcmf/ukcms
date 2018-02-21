<?php

namespace app\admin\controller;

use think\facade\Cache;

/**
 * 内容模型控制器
 */
class Model extends Common {

    protected $modelMenuPid = 57;

    public function index() {
        $mlist = model('Model')->order('orders,id')->paginate(15);
        $page = $mlist->render();
        $this->assign('mlist', $mlist);
        $this->assign('page', $page);
        return $this->fetch();
    }

    public function add() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, 'Model');
            if (true !== $result) {
                return $this->error($result);
            }
            $Model = model('Model');
            try {
                $Model->createTable($data);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            Cache::clear('db_' . $data['table']);
            Cache::clear('db_mode_field');
            Cache::clear('db_model');
            //后台菜单增加模型内容管理
            $menuData['pid'] = $this->modelMenuPid;
            $menuData['title'] = $data['title'];
            $menuData['icon'] = 'column' == $data['purpose'] ? 'fa fa-sticky-note' : 'fa fa-sticky-note-o';
            $menuData['url_type'] = 1;
            $menuData['url_value'] = 'admin/content/' . $data['table'];
            $menuData['url_target'] = '_self';
            $menuData['orders'] = $data['orders'];
            $menuData['ifsystem'] = 1;
            $menuData['ifvisible'] = 1;
            model('adminMenu')->save($menuData);
            Cache::clear('db_admin_menu_tree');
            $this->success('模型添加成功~', url('index'));
        } else {
            return $this->fetch();
        }
    }

    public function edit($id = 0) {
        $id = intval($id);
        if ($id < 1) {
            $this->error('参数错误');
        }
        $info = model('Model')->where('id', $id)->find();
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $result = $this->validate($data, 'Model.edit');
            if (true !== $result) {
                return $this->error($result);
            }
            $data['id'] = intval($id);
            $Model = model('Model');
            try {
                $Model->editTable($data, $info);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            //编辑后台菜单
            $menuData['title'] = $data['title'];
            $menuData['url_value'] = 'admin/content/' . $data['table'];
            $menuData['orders'] = $data['orders'];
            model('adminMenu')->where('url_value', 'admin/content/' . $info['table'])->update($menuData);
            Cache::clear('db_' . $info['table']);
            Cache::clear('db_' . $data['table']);
            Cache::clear('db_mode_field');
            Cache::clear('db_model');
            $this->success('模型编辑成功~', url('index'));
        } else {
            $this->assign('info', $info);
            return $this->fetch();
        }
    }

    public function setState($id, $status) {
        $id = intval($id);
        $status = intval($status);
        if ($status != 0 && $status != 1)
            return '参数错误';
        if (model('Model')->where('id', $id)->update(['status' => $status])) {
            Cache::clear('db_mode_field');
            Cache::clear('db_model');
            return true;
        } else {
            return '设置失败';
        }
    }

    public function setSub($id, $status) {
        $id = intval($id);
        $status = intval($status);
        if ($status != 0 && $status != 1)
            return '参数错误';
        if (model('Model')->where('id', $id)->update(['ifsub' => $status])) {
            Cache::clear('db_mode_field');
            Cache::clear('db_model');
            return true;
        } else {
            return '设置失败';
        }
    }

    public function delete($id) {
        $id = intval($id);
        if ($id < 1) {
            return '参数错误';
        }
        $Model = model('Model');
        $info = $Model->where('id', $id)->field('id,table,type,table')->find();
        try {
            $Model->deleteTable($info);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
        Cache::clear('db_' . $info['table']);
        Cache::clear('db_mode_field');
        Cache::clear('db_model');
        model('adminMenu')->where('url_value', 'admin/content/' . $info['table'])->delete();
        Cache::clear('db_admin_menu_tree');
        return true;
    }

}
