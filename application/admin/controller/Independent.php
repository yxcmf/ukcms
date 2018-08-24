<?php

namespace app\admin\controller;

use think\Db;
use think\facade\Cache;

/**
 * 独立模型内容控制器
 */
class Independent extends Common
{

    use \app\common\traits\controller\Notice;

    public function _empty()
    {
        $modelName = $this->request->action();
        $model = Db::name('model')->where('table', $modelName)->find();
        if (!$model) {
            $this->error('找不到模型~');
        }
        $this->assign([
            'modelId' => $model['id'],
            'modelTitle' => $model['title'],
            'modelStatus' => $model['status']
        ]);
        //搜索数据验证
        $getParam = ['cid' => $this->request->get('cid'), 'keyword' => $this->request->get('keyword')];
        $result = $this->validate($getParam, [
            'cid|栏目ID' => 'number',
            'keyword|标题关键词' => 'chsDash',
        ]);
        if (true !== $result) {
            $this->error($result);
        }
        //关键词搜索条件
        $where = '';
        if (!empty($getParam['keyword'])) {
            $searchField = Db::name('model_field')->where('model_id', $model['id'])->where('ifmain', 1)->where('ifsearch', 1)->column('name');
            if ($searchField) {
                $kwhere = '';
                foreach ($searchField as $vo) {
                    $kwhere .= "$vo like '%$getParam[keyword]%' or ";
                }
                $where .= substr($kwhere, 0, -4);
            } else {
                $this->error('模型必须至少要有一个字段是可搜索的~');
            }
        }
        //列表需要显示的自定义主表字段
        $fieldArr = Db::name('model_field')
            ->where('model_id', $model['id'])
            ->where('status', 1)
            ->where('ifmain', 1)
            ->where('status', 1)
            ->where('ifsearch', 1)
            ->where('name', 'not in', ['id', 'uid', 'create_time', 'update_time', 'orders', 'status', 'hits', 'cname', 'places'])
            ->order('orders,id')
            ->limit('column' == $model['purpose'] ? 1 : 2)
            ->column('id,name,title');
        $fieldStr = '';
        if (!empty($fieldArr)) {
            foreach ($fieldArr as $vo) {
                $fieldStr .= ',' . $vo['name'];
            }
        }
        $list = Db::name($model['table'])
            ->order('orders,id desc')
            ->field('id' . $fieldStr . ',update_time,orders,status')
            ->where($where)
            ->paginate(15, false, [
                'query' => $getParam
            ]);

        $this->assign('list', $list);
        $this->assign('page', $list->render());
        $this->assign('fieldArr', $fieldArr);
        return $this->fetch('list');
    }

    public function add($mid = 0)
    {
        $modelId = intval($mid);
        if (!$modelId) {
            $this->error('参数错误~');
        }
        $info = Db::name('model')->where('id', $modelId)->where('status', 1)->field('table,title,purpose')->find();
        if (empty($info)) {
            return $this->error('模型被冻结不可操作');
        }
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $ModelField = model('ModelField');
            $data['modelFieldExt'] = isset($data['modelFieldExt']) ? $data['modelFieldExt'] : [];
            try {
                $ModelField->addModelData($modelId, $data['modelField'], $data['modelFieldExt']);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            Cache::clear('db_' . $info['table']);
            $this->dialog('模型内容添加成功~', [
                ['title' => '返回列表', 'class' => 'default', 'url' => url($info['table'])],
                ['title' => '继续添加', 'class' => 'success', 'url' => url('add', ['mid' => $mid, 'cname' => isset($data['modelField']['cname']) ? $data['modelField']['cname'] : ''])]
            ]);
        } else {
            $fieldList = model('ModelField')->getFieldList($modelId);
            $this->assign('fieldList', $fieldList);
            $this->assign('mid', $modelId);
            $this->assign('modelName', $info['title']);
            return $this->fetch();
        }
    }


    public function edit($mid = 0, $id = 0)
    {
        $modelId = intval($mid);
        if (!$modelId) {
            $this->error('参数错误mid~');
        }
        $info = Db::name('model')->where('id', $modelId)->where('status', 1)->field('table,title,purpose')->find();
        if (empty($info)) {
            return $this->error('模型被冻结不可操作');
        }
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $ModelField = model('ModelField');
            $data['modelFieldExt'] = isset($data['modelFieldExt']) ? $data['modelFieldExt'] : [];
            try {
                $ModelField->editModelData($modelId, $data['modelField'], $data['modelFieldExt']);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            Cache::clear('db_' . $info['table']);
            $this->success('模型内容编辑成功~', url($info['table']));
        } else {
            $contentId = intval($id);
            if (!$contentId) {
                $this->error('参数错误cid~');
            }
            $fieldList = model('ModelField')->getFieldList($modelId, $contentId);
            $this->assign('fieldList', $fieldList);
            $this->assign('mid', $modelId);
            $this->assign('modelName', $info['title']);
            $this->assign('id', $contentId);
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



//批量复制
//    public function copy($mid)
//    {
//        if (!is_numeric($mid)) {
//            return $this->error('参数错误~');
//        }
//        $ids = input('post.ids/a', null, 'intval');
//        $cname = input('post.cname', '', 'trim');
//        $num = input('post.num/d');
//        $num = $num > 0 ? $num : 1;
//        if (empty($ids) || empty($cname)) {
//            return $this->error('没勾选内容或没选择栏目~');
//        }
//        try {
//            model('ModelField')->copyData($mid, $ids, $num, $cname);
//        } catch (\Exception $ex) {
//            return $this->error($ex->getMessage());
//        }
//        return $this->success('数据复制成功~');
//    }

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
