<?php

namespace app\admin\controller;

use think\Db;
use think\facade\Cache;

/**
 * 内容控制器
 */
class Content extends Common
{

    use \app\common\traits\controller\Notice;

    public function _empty($columnName)
    {
        $columnInfo = Db::name('column')->where('name', $columnName)->field('id,type,name,title,model_id,ext_model_id,url')->find();
        if (empty($columnInfo)) {
            model('adminMenu')->where('url_value', 'admin/content/' . $columnName)->delete();
            Cache::clear('db_admin_menu_tree');
            $this->error('栏目不存在~');
        }
        switch ($columnInfo['type']) {
            case 1://频道
                if ($columnInfo['ext_model_id']) {
                    return $this->extendmodel($columnInfo);
                } else {
                    $this->assign([
                        'columnTitle' => $columnInfo['title'],
                        'columnId' => $columnInfo['id'],
                    ]);
                    return $this->fetch('empty');
                }
            case 2://列表
                if ('page' == $this->request->param('act') && $columnInfo['ext_model_id']) {
                    return $this->extendmodel($columnInfo);
                } else {
                    return $this->listmodel($columnInfo);
                }
            case 3://自定义
                return $this->columnUrl($columnInfo);
        }
    }

    /**
     * @desc 栏目拓展内容模型信息
     */
    protected function columnUrl($columnInfo)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            try {
                model('Column')->allowField(['url'])->save($data, ['id' => $columnInfo['id']]);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success('栏目自定义链接编辑成功~');
        } else {
            $this->assign([
                'columnTitle' => $columnInfo['title'],
                'columnUrl' => $columnInfo['url'],
            ]);
            return $this->fetch('url');
        }
    }

    /**
     * @desc 栏目拓展内容模型信息
     */
    protected function extendmodel($columnInfo)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['modelFieldExt'] = isset($data['modelFieldExt']) ? $data['modelFieldExt'] : [];
            $data['modelField']['cname'] = $columnInfo['name'];
            $data['modelField']['ifextend'] = 1;
            $ModelField = model('ModelField');
            if (isset($data['modelField']['id'])) {
                //编辑
                try {
                    $ModelField->editModelData($columnInfo['ext_model_id'], $data['modelField'], $data['modelFieldExt']);
                } catch (\Exception $ex) {
                    $this->error($ex->getMessage());
                }
            } else {
                //新增
                try {
                    $ModelField->addModelData($columnInfo['ext_model_id'], $data['modelField'], $data['modelFieldExt']);
                } catch (\Exception $ex) {
                    $this->error($ex->getMessage());
                }
            }
            $this->success('栏目拓展信息编辑成功~');
        } else {
            $fieldList = model('ModelField')->getFieldList($columnInfo['ext_model_id'], $columnInfo['name'], 'column');
            $this->assign([
                'columnTitle' => $columnInfo['title'],
                'fieldList' => $fieldList
            ]);
            return $this->fetch('page');
        }
    }


    /**
     * @desc 栏目列表内容模型信息
     */
    protected function listmodel($columnInfo)
    {
        $listModel = Db::name('model')->where('id', $columnInfo['model_id'])->find();
        if (!$listModel) {
            $this->error('找不到内容模型~');
        }
        //搜索数据验证
        $getParam = ['keyword' => $this->request->get('keyword')];
        $result = $this->validate($getParam, [
            'keyword|标题关键词' => 'chsDash',
        ]);
        if (true !== $result) {
            $this->error($result);
        }
        //关键词搜索条件
        $where = '';
        if (!empty($getParam['keyword'])) {
            $searchField = Db::name('model_field')->where('model_id', $listModel['id'])->where('ifmain', 1)->where('ifsearch', 1)->column('name');
            if (!empty($searchField)) {
                $kwhere = [];
                foreach ($searchField as $vo) {
                    $kwhere[] = "$vo like '%$getParam[keyword]%'";
                }
                $where .= implode(' or ', $kwhere);
            } else {
                $this->error('栏目内容模型必须至少要有一个字段是可搜索的~');
            }
        }
        //列表需要显示的自定义主表字段
        $fieldArr = Db::name('model_field')
            ->where('model_id', $listModel['id'])
            ->where('status', 1)
            ->where('ifmain', 1)
            ->where('status', 1)
            ->where('ifsearch', 1)
            ->where('name', 'not in', ['id', 'uid', 'create_time', 'update_time', 'orders', 'status', 'hits', 'cname', 'places'])
            ->order('orders,id')
            ->limit('column' == $listModel['purpose'] ? 1 : 2)
            ->column('id,name,title');
        $fieldStr = '';
        if (!empty($fieldArr)) {
            foreach ($fieldArr as $vo) {
                $fieldStr .= ',' . $vo['name'];
            }
        }
        $where = '' == $where ? '' : '(' . $where . ') and ';
        $where .= "cname = '" . $columnInfo['name'] . "'";
        $where .= " and ifextend = 0";
        //当前模型可用推荐位
        $places = Db::name('place')->where('mid', 0)->whereOr('mid', $listModel['id'])->column('id,title');
        $list = Db::view($listModel['table'], 'id' . $fieldStr . ',uid,cname,hits,update_time,places,orders,status')
            ->where($where)
            ->view('admin_user', 'realname', 'admin_user.id=' . $listModel['table'] . '.uid', 'LEFT')
            ->order('orders,id desc')
            ->paginate(15, false, [
                'query' => $getParam
            ]);
        $this->assign([
            'columnTitle' => $columnInfo['title'],
            'columnName' => $columnInfo['name'],
            'ExtModelId' => $columnInfo['ext_model_id'],
            'listModelId' => $listModel['id'],
            'modelStatus' => $listModel['status'],
            'places' => $places,
            'list' => $list,
            'page' => $list->render(),
            'fieldArr' => $fieldArr
        ]);
        return $this->fetch('list');
    }

    public function add($cname = '')
    {
        if (empty($cname)) {
            $this->error('参数错误~');
        }
        $columnInfo = Db::view('column', 'title,model_id')
            ->view('model', 'table', 'column.model_id=model.id', 'LEFT')
            ->where('column.name', $cname)
            ->where('column.status', 1)
            ->where('model.status', 1)
            ->find();
        if (empty($columnInfo)) {
            return $this->error('栏目或内容模型不存在或被冻结');
        }
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $ModelField = model('ModelField');
            $data['modelField']['cname'] = $cname;
            $data['modelFieldExt'] = isset($data['modelFieldExt']) ? $data['modelFieldExt'] : [];
            try {
                $ModelField->addModelData($columnInfo['model_id'], $data['modelField'], $data['modelFieldExt']);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            Cache::clear('db_' . $columnInfo['table']);
            $this->dialog('内容添加成功~', [
                ['title' => '返回列表', 'class' => 'default', 'url' => url($cname)],
                ['title' => '继续添加', 'class' => 'success', 'url' => url('add', ['cname' => $cname])]
            ]);
        } else {
            $placeList = Db::name('place')->where('mid', $columnInfo['model_id'])->whereOr('mid', 0)->order('orders,id desc')->column('id,title');
            $fieldList = model('ModelField')->getFieldList($columnInfo['model_id']);
            $this->assign([
                'placeList' => $placeList,
                'fieldList' => $fieldList,
                'cname' => $cname,
                'columnTitle' => $columnInfo['title']
            ]);
            return $this->fetch();
        }
    }

    public function edit($cname = '', $id = 0)
    {
        if (empty($cname)) {
            $this->error('参数错误~');
        }
        $columnInfo = Db::view('column', 'title,model_id')
            ->view('model', 'table', 'column.model_id=model.id', 'LEFT')
            ->where('column.name', $cname)
            ->where('column.status', 1)
            ->where('model.status', 1)
            ->find();
        if (empty($columnInfo)) {
            return $this->error('栏目或内容模型不存在或被冻结');
        }
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $ModelField = model('ModelField');
            $data['modelFieldExt'] = isset($data['modelFieldExt']) ? $data['modelFieldExt'] : [];
            try {
                $ModelField->editModelData($columnInfo['model_id'], $data['modelField'], $data['modelFieldExt'], ['cname']);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            Cache::clear('db_' . $columnInfo['table']);
            $this->success('模型内容编辑成功~', url($cname));
        } else {
            $contentId = intval($id);
            if (!$contentId) {
                $this->error('参数错误cid~');
            }
            $placeList = Db::name('place')->where('mid', $columnInfo['model_id'])->whereOr('mid', 0)->order('orders,id desc')->column('id,title');
            $fieldList = model('ModelField')->getFieldList($columnInfo['model_id'], $contentId);
            $this->assign([
                'placeList' => $placeList,
                'fieldList' => $fieldList,
                'id' => $contentId,
                'columnTitle' => $columnInfo['title']
            ]);
            return $this->fetch();
        }
    }

    public function delete($mid = 0, $id = 0)
    {
        $mid = intval($mid);
        $tableName = Db::name('model')->where('id', $mid)->where('status', 1)->value('table');

        if ($this->request->isPost()) {
            if (!$mid) {
                return $this->error('参数错误~');
            }
            if (!$tableName) {
                return $this->error('模型被冻结不可操作');
            }
            $ids = input('post.ids/a', null, 'intval');
            if (empty($ids)) {
                return $this->error('请先勾选需要删除的信息~');
            }
            $ModelField = model('ModelField');
            try {
                $ModelField->deleteModelData($mid, $ids);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            Cache::clear('db_' . $tableName);
            $this->success('信息删除成功~');
        } else {
            $id = intval($id);
            if (!is_numeric($mid) || !is_numeric($id)) {
                return '参数错误';
            }
            if (!$tableName) {
                return '模型被冻结不可操作';
            }
            $ModelField = model('ModelField');
            try {
                $ModelField->deleteModelData($mid, $id);
            } catch (\Exception $ex) {
                return $ex->getMessage();
            }
            Cache::clear('db_' . $tableName);
            return true;
        }
    }

//批量移动栏目
    public function move($mid)
    {
        if (!is_numeric($mid)) {
            return $this->error('参数错误~');
        }
        $ids = input('post.ids/a', null, 'intval');
        $cname = input('post.cname', '', 'trim');
        if (empty($ids) || empty($cname)) {
            return $this->error('没勾选内容或没选择栏目~');
        }
        $columnListModelId = Db::name('column')->where('name', $cname)->value('model_id');
        if ($columnListModelId != $mid) {
            return $this->error('当前栏目模型与移动栏目模型不一致~');
        }
        $tableName = Db::name('model')->where('id', $mid)->where('status', 1)->value('table');
        if (!$tableName) {
            return $this->error('模型被冻结不可操作~');
        }
        if (Db::name($tableName)->where('id', 'in', $ids)->update(['cname' => $cname])) {
            Cache::clear('db_' . $tableName);
            $this->success('栏目修改成功~');
        } else {
            $this->error('栏目修改失败~');
        }
    }

//批量复制
    public function copy($mid)
    {
        if (!is_numeric($mid)) {
            return $this->error('参数错误~');
        }
        $ids = input('post.ids/a', null, 'intval');
        $cname = input('post.cname', '', 'trim');
        $num = input('post.num/d');
        $num = $num > 0 ? $num : 1;
        if (empty($ids) || empty($cname)) {
            return $this->error('没勾选内容或没选择栏目~');
        }
        try {
            model('ModelField')->copyData($mid, $ids, $num, $cname);
        } catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }
        return $this->success('数据复制成功~');
    }

    public function setState($mid = 0, $id = 0, $status = 0)
    {
        if (($status != 0 && $status != 1) || !is_numeric($mid) || !is_numeric($id)) {
            return '参数错误';
        }
        $tableName = Db::name('model')->where('id', $mid)->where('status', 1)->value('table');
        if (!$tableName) {
            return '模型被冻结不可操作';
        }
        if (Db::name($tableName)->where('id', $id)->update(['status' => $status])) {
            Cache::clear('db_' . $tableName);
            return true;
        } else {
            return '设置失败';
        }
    }

    public function changeOrder($mid, $id, $num)
    {
        if (!is_numeric($mid) || !is_numeric($id)) {
            return '参数错误';
        }
        if (!is_numeric($num)) {
            return '排序只能是数字';
        }
        $tableName = Db::name('model')->where('id', $mid)->where('status', 1)->value('table');
        if (!$tableName) {
            return '模型被冻结不可操作';
        }
        if (Db::name($tableName)->where('id', $id)->update(['orders' => $num])) {
            Cache::clear('db_' . $tableName);
            return true;
        } else {
            return '设置排序失败';
        }
    }


}
