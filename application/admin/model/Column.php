<?php

namespace app\admin\model;

use think\Db;

/**
 * 后台栏目模型
 */
class Column extends \think\Model {

    use \app\common\traits\TreeArray,
        \app\common\traits\SortArray;

    // 自动写入时间戳
    protected $pk = 'id';
    protected $autoWriteTimestamp = true;

    public function getColumn($column = 'id,path,title', $type = 'sort', $ifModelName = true, $key = 'id') {
        if ($ifModelName && strpos($column, 'model_id')) {
            $list = Db::view('Column', $column)->view('Model', 'title as mtitle,table', "Column.model_id=Model.id", "LEFT")->order('orders,id')->select();
        } else {
            $list = self::order('orders,id')->column($column);
        }
        if (!empty($list)) {
            switch ($type) {
                case 'tree':
                    return $this->buildTree($list);
                case 'sort':
                    return $this->resort($list, $key);
                default:
                    return $list;
            }
        } else {
            return[];
        }
    }

    public function addColumn($fields, $data) {
        return self::allowField($fields)->save($data);
    }

    public function editColumn($fields, $data, $info) {
        //是否修改上级栏目
        if (!empty($data['path'])) {
            //自身作为父栏目
            if ($info['path'] . $info['id'] . ',' == $data['path']) {
                throw new \Exception('不可以作为自身的下级栏目');
            }
            //下级有子栏目
            if ($info['path'] != $data['path']) {
                $cid = self::where('path', 'like', '%,' . $info['id'] . ',')->value('id');
                if ($cid) {
                    throw new \Exception('[' . $info['title'] . ']有下级子栏目不可以移动');
                }
            }
        }

        $listmodel = Db::name('model')->where('id', $info['model_id'])->find();
        //更换模型删除之前模型下对应列表信息
        if (isset($data['model_id']) && $data['model_id'] != $info['model_id']) {
            if (!empty($listmodel)) {
                if (2 == $listmodel['type']) {
                    $ids = Db::name($listmodel['table'])->where('cname', $info['name'])->where('ifextend', 0)->column('id');
                    Db::name($listmodel['table'] . '_data')->where('did', 'in', $ids)->delete();
                }
                Db::name($listmodel['table'])->where('cname', $info['name'])->where('ifextend', 0)->delete();
            }
        }
        //列表栏目更换对应模型内容栏目标识
        if (2 == $info['type'] && isset($data['name']) && $data['name'] != $info['name']) {
            if (!empty($listmodel)) {
                Db::name($listmodel['table'])->where('cname', $info['name'])->update(['cname' => $data['name']]);
            }
        }

        $extmodel = Db::name('model')->where('id', $info['ext_model_id'])->find();
        //更换模型删除之前模型下对应拓展信息
        if (isset($data['ext_model_id']) && $data['ext_model_id'] != $info['ext_model_id']) {
            if (!empty($extmodel)) {
                if (2 == $extmodel['type']) {
                    $exid = Db::name($extmodel['table'])->where('cname', $info['name'])->where('ifextend', 0)->value('id');
                    Db::name($extmodel['table'] . '_data')->where('did', $exid)->delete();
                }
                Db::name($extmodel['table'])->where('cname', $info['name'])->where('ifextend', 1)->delete();
            }
        }
//       return self::allowField($fields)->save($data,['id'=>$data['id']]);
        return self::update($data, [], $fields);
    }

    public function moveColumn($path, $info) {
        $child = self::where('path', $info['path'] . $info['id'] . ',')->value('id');
        if ($child) {
            throw new \Exception('[' . $info['title'] . ']下级还有栏目不可移动');
        } else {
            self::where('id', $info['id'])->update(['path' => $path]);
        }
    }

    public function deleteColumn($info) {
        $child = self::where('path', 'like', '%,' . $info['id'] . ',%')->value('id');
        if ($child) {
            throw new \Exception('[' . $info['title'] . ']下还有子栏目不可以删除');
        }
        //删除绑定内容模型中对应的数据
        if ($info['model_id']) {
            $listmodel = Db::name('model')->where('id', $info['model_id'])->find();
            if (!empty($listmodel)) {
                if (2 == $listmodel['type']) {
                    $ids = Db::name($listmodel['table'])->where('cname', $info['name'])->where('ifextend', 0)->column('id');
                    Db::name($listmodel['table'] . '_data')->where('did', 'in', $ids)->delete();
                }
                Db::name($listmodel['table'])->where('cname', $info['name'])->where('ifextend', 0)->delete();
            }
        }
        //删除绑定内容模型中对应的数据
        if ($info['ext_model_id']) {
            $extmodel = Db::name('model')->where('id', $info['ext_model_id'])->find();
            if (!empty($extmodel)) {
                if (2 == $extmodel['type']) {
                    $exid = Db::name($extmodel['table'])->where('cname', $info['name'])->where('ifextend', 0)->value('id');
                    Db::name($extmodel['table'] . '_data')->where('did', $exid)->delete();
                }
                Db::name($extmodel['table'])->where('cname', $info['name'])->where('ifextend', 1)->delete();
            }
        }
        self::where('id', $info['id'])->delete();
        return true;
    }

}
