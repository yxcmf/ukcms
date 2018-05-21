<?php

namespace app\admin\controller;

use think\Db;

/**
 * 内容模型字段控制器
 */
class Field extends Common {

    protected $ext_table = '_data';

    public function index($mid = 0) {
        $mid = intval($mid);
        if (!$mid) {
            $this->error('参数错误~');
        }
        $banFields=['id', 'did', 'status', 'cname', 'places', 'uid', 'ifextend'];
        $flist = model('ModelField')->where('model_id', $mid)->whereNotIn('name', $banFields)->order('orders,id')->paginate(20);
        $page = $flist->render();
        $this->assign('flist', $flist);
        $this->assign('page', $page);
        $modelInfo = Db::name('model')->where('id', $mid)->find();
        $this->assign('modelTitle', $modelInfo['title']);
        $this->assign('mid', $modelInfo['id']);
        $this->assign('stringFieldType', Db::name('field_type')->where('ifstring', 1)->column('name'));
        return $this->fetch();
    }

    public function add($mid = 0) {
        $mid = intval($mid);
        if (!$mid) {
            $this->error('参数错误~');
        }
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, 'ModelField');
            if (true !== $result) {
                return $this->error($result);
            }
            $ModelField = model('ModelField');
            try {
                $ModelField->addField($data, $mid);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success('字段添加成功~', url('index', ['mid' => $mid]));
        } else {
            //所有模型表
            $modelList = Db::name('model')->where('status', 1)->column('table,type');
            $tables = [];
            foreach ($modelList as $key => $vo) {
                $tables[] = $key;
                if (2 == $vo)
                    $tables[] = $key . $this->ext_table;
            }
            $modelInfo = Db::name('model')->where('id', $mid)->find();
            $fieldType = Db::name('field_type')->order('orders')->column('name,title,default_define,ifoption,ifstring');
            $this->assign([
                'modelTitle' => $modelInfo['title'],
                'modelType' => $modelInfo['type'],
                'mid' => $modelInfo['id'],
                'fieldType' => $fieldType,
                'tables' => json_encode($tables, true)
            ]);
            return $this->fetch();
        }
    }

    public function edit($id = 0) {
        $id = intval($id);
        if (!$id) {
            $this->error('参数错误~');
        }
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $result = $this->validate($data, 'ModelField');
            if (true !== $result) {
                return $this->error($result);
            }
            $ModelField = model('ModelField');
            try {
                $ModelField->editField($data, $id);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success('字段编辑成功~', url('index', ['mid' => $data['model_id']]));
        } else {
            //所有模型表
            $modelList = Db::name('model')->where('status', 1)->column('table,type');
            $tables = [];
            foreach ($modelList as $key => $vo) {
                $tables[] = $key;
                if (2 == $vo)
                    $tables[] = $key . $this->ext_table;
            }
            $info = model('ModelField')->where('id', $id)->find();
            if ($info['jsonrule']) {
                $info['jsonrule'] = json_decode($info['jsonrule'], true);
            }
            $modelInfo = Db::name('model')->where('id', $info->model_id)->find();
            $fieldType = Db::name('field_type')->order('orders')->column('name,title,default_define,ifoption,ifstring');
            $this->assign([
                'modelTitle' => $modelInfo['title'],
                'modelType' => $modelInfo['type'],
                'info' => $info,
                'fieldType' => $fieldType,
                'tables' => json_encode($tables, true)
            ]);
            return $this->fetch();
        }
    }

    public function setVisible($id, $ifvisible) {
        $id = intval($id);
        $ifvisible = intval($ifvisible);
        if (0 != $ifvisible && 1 != $ifvisible) {
            return '参数错误';
        }
        $field = model('ModelField')->get($id);
        if ($field->ifrequire && 0 == $ifvisible) {
            return '必填字段不可以设置为隐藏';
        }
        $field->ifeditable = $ifvisible;
        if ($field->save()) {
            return true;
        } else {
            return '设置失败';
        }
    }

    public function setState($id, $status) {
        $id = intval($id);
        $status = intval($status);
        if ($status != 0 && $status != 1)
            return '参数错误';
        if (model('ModelField')->where('id', $id)->update(['status' => $status])) {
            return true;
        } else {
            return '设置失败';
        }
    }

    public function setRequire($id, $ifrequire) {
        $id = intval($id);
        $ifrequire = intval($ifrequire);
        if ($ifrequire != 0 && $ifrequire != 1) {
            return '参数错误';
        }
        $field = model('ModelField')->get($id);
        if (!$field->ifeditable && $ifrequire) {
            return '隐藏字段不可以设置为必填';
        }
        $field->ifrequire = $ifrequire;
        if ($field->save()) {
            return true;
        } else {
            return '设置失败';
        }
    }

    public function setSearch($id, $ifsearch) {
        $id = intval($id);
        $ifsearch = intval($ifsearch);
        if ($ifsearch != 0 && $ifsearch != 1)
            return '参数错误';
        if (model('ModelField')->where('id', $id)->update(['ifsearch' => $ifsearch])) {
            return true;
        } else {
            return '设置失败';
        }
    }

    public function changeOrder($id, $num) {
        if (!is_numeric($id)) {
            return '参数错误';
        }
        if (!is_numeric($num)) {
            return '排序只能是数字';
        }
        if (model('ModelField')->where('id', $id)->update(['orders' => $num])) {
            return true;
        } else {
            return '设置排序失败';
        }
    }

    public function delete($id) {
        $id = intval($id);
        if (!$id) {
            return '参数错误';
        }
        $ModelField = model('ModelField');
        try {
            $ModelField->deleteField($id);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
        return true;
    }

}
