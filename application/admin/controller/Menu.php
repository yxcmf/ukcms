<?php

namespace app\admin\controller;

use think\facade\Cache;

class Menu extends Common {

    public function index() {
        if ($this->request->isPost()) {
            
        } else {
            return $this->fetch();
        }
    }

    public function add() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, 'AdminMenu');
            if (true !== $result) {
                return $this->error($result);
            }
            $AdminMenu = model('AdminMenu');
            try {
                $AdminMenu->allowField(['pid', 'title', 'url_type', 'url_value', 'url_target', 'icon', 'ifvisible'])->save($data);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            Cache::clear('db_admin_menu_tree');
            $this->redirect('admin/menu/index');
        }
    }

    public function edit() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, 'AdminMenu');
            if (true !== $result) {
                return $this->error($result);
            }
            $AdminMenu = model('AdminMenu');
            try {
                $AdminMenu->allowField(['pid', 'title', 'url_type', 'url_value', 'url_target', 'icon', 'ifvisible'])->save($data, ['id' => intval($data['id'])]);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            Cache::clear('db_admin_menu_tree');
            $this->redirect('admin/menu/index');
        }
    }

    //设置隐显节点
    public function visible($id = 0, $val) {
        $id = intval($id);
        $val = intval($val);
        if ($val != 0 && $val != 1)
            return '参数错误';
        $AdminMenu = model('AdminMenu');
        switch ($val) {
            case 0:
                try {
                    $AdminMenu->hideNode($id);
                } catch (\Exception $ex) {
                    return $ex->getMessage();
                }
                Cache::clear('db_admin_menu_tree');
                return true;
            case 1:
                try {
                    $AdminMenu->where('id', $id)->update(['ifvisible' => 1]);
                } catch (\Exception $ex) {
                    return $ex->getMessage();
                }
                Cache::clear('db_admin_menu_tree');
                return true;
        }
    }

    /**
     * 删除节点及其子节点
     * @param int $id
     * @return bool
     */
    public function delete($id = 0) {
        $AdminMenu = model('AdminMenu');
        try {
            $AdminMenu->delNode(intval($id));
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
        Cache::clear('db_admin_menu_tree');
        return true;
    }

    public function changeorder() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $updata = json_decode($data['jcode'], true);
            if (!empty($updata)) {
                $AdminMenu = model('AdminMenu');
                $result = $this->validate($data, 'AdminMenu.order');
                if (true !== $result) {
                    return $result;
                }
                try {
                    $AdminMenu->saveAll($updata);
                } catch (\Exception $ex) {
                    return $ex->getMessage();
                }
                Cache::clear('db_admin_menu_tree');
                return true;
            } else {
                return true;
            }
        }
    }

}
