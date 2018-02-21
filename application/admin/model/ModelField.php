<?php

namespace app\admin\model;

use think\Db;
use think\facade\Cache;

/**
 * 后台字段模型
 * @package app\admin\model
 */
class ModelField extends \app\common\model\ModelField {

    protected function tableExist($modeId, $ifmain = 1) {
        $table = Db::name('model')->where('id', $modeId)->value('table');
        $table = config('database.prefix') . $table;
        if (!$ifmain) {
            $table .=$this->ext_table;
        }
        if (true == Db::query("SHOW TABLES LIKE '{$table}'")) {
            return [$table, true];
        } else {
            return [$table, false];
        }
    }

    public function addField($data, $mid) {
        $data['name'] = strtolower($data['name']);
        $data['ifmain'] = isset($data['ifmain']) ? $data['ifmain'] : 0;
        list($table, $result) = $this->tableExist($mid, $data['ifmain']);
        if ($result) {
            //判断字段名唯一性
            if ($this->where('name', $data['name'])->where('model_id', $mid)->value('id')) {
                throw new \Exception("字段'" . $data['name'] . "`已经存在");
            }
            $data['ifeditable'] = isset($data['ifeditable']) ? intval($data['ifeditable']) : 0;
            $data['ifrequire'] = isset($data['ifrequire']) ? intval($data['ifrequire']) : 0;
            if ($data['ifrequire'] && !$data['ifeditable']) {
                throw new \Exception("必填字段不可以隐藏");
            }
            $sql = <<<EOF
            ALTER TABLE `{$table}`
            ADD COLUMN `{$data['name']}` {$data['define']} COMMENT '{$data['title']}';
EOF;
            Db::execute($sql);
            $fieldInfo = Db::name('field_type')->where('name', $data['type'])->field('ifoption,ifstring')->find();
            $data['model_id'] = $mid;
            $data['ifmain'] = isset($data['ifmain']) ? intval($data['ifmain']) : 0;

            //只有主表文本类字段才可支持搜索
            $data['ifsearch'] = isset($data['ifsearch']) ? ($fieldInfo['ifstring'] && $data['ifmain'] ? intval($data['ifsearch']) : 0) : 0;
            $data['status'] = isset($data['status']) ? intval($data['status']) : 0;
            $data['iffixed'] = 0;
            $data['options'] = $fieldInfo['ifoption'] ? $data['options'] : '';
            //选择关联
            if ($fieldInfo['ifoption']) {
                if (isset($data['jsonrule']['string'])) {
                    unset($data['jsonrule']['string']);
                }
                if (isset($data['jsonrule']['thumb'])) {
                    unset($data['jsonrule']['thumb']);
                }
                if (isset($data['jsonrule']['file'])) {
                    unset($data['jsonrule']['file']);
                }
                if (!empty($data['jsonrule']['choose']['table']) && !empty($data['jsonrule']['choose']['key']) && !empty($data['jsonrule']['choose']['value'])) {
                    $data['jsonrule'] = json_encode($data['jsonrule'], true);
                }
            }
            //字符组合关联
            if ($fieldInfo['ifstring']) {
                if (isset($data['jsonrule']['choose'])) {
                    unset($data['jsonrule']['choose']);
                }
                if (isset($data['jsonrule']['thumb'])) {
                    unset($data['jsonrule']['thumb']);
                }
                if (isset($data['jsonrule']['file'])) {
                    unset($data['jsonrule']['file']);
                }
                if (!empty($data['jsonrule']['string']['table']) && !empty($data['jsonrule']['string']['key'])) {
                    $data['jsonrule'] = json_encode($data['jsonrule'], true);
                }
            }
            //图片设置
            if ('image' == $data['type'] || 'images' == $data['type']) {
                if (isset($data['jsonrule']['choose'])) {
                    unset($data['jsonrule']['choose']);
                }
                if (isset($data['jsonrule']['string'])) {
                    unset($data['jsonrule']['string']);
                }
                if (isset($data['jsonrule']['file'])) {
                    unset($data['jsonrule']['file']);
                }
                if (isset($data['jsonrule']['thumb']['ifon'])) {
                    $data['jsonrule'] = json_encode($data['jsonrule'], true);
                }
            }
            //文件设置
            if ('files' == $data['type']) {
                if (isset($data['jsonrule']['choose'])) {
                    unset($data['jsonrule']['choose']);
                }
                if (isset($data['jsonrule']['string'])) {
                    unset($data['jsonrule']['string']);
                }
                if (isset($data['jsonrule']['thumb'])) {
                    unset($data['jsonrule']['thumb']);
                }
                if (isset($data['jsonrule']['file']['type'])) {
                    $data['jsonrule'] = json_encode($data['jsonrule'], true);
                }
            }
            if (is_array($data['jsonrule'])) {
                $data['jsonrule'] = '';
            }
            $result = $this->allowField(['model_id', 'name', 'title', 'type', 'define', 'value', 'options', 'remark', 'ifmain', 'ifeditable', 'ifsearch', 'ifrequire', 'orders', 'status', 'iffixed', 'jsonrule'])->save($data);
            if (!$result) {
                throw new \Exception('添加字段成功,记录字段信息失败~');
            }
            Cache::clear('db_' . $table);
            Cache::clear('db_mode_field');
            return true;
        } else {
            throw new \Exception('添加字段' . $data['name'] . '失败~' . $table . '表不存在');
        }
    }

    public function editField($data, $id) {
        $data['name'] = strtolower($data['name']);
        $data['ifmain'] = isset($data['ifmain']) ? $data['ifmain'] : 0;
        list($table, $result) = $this->tableExist($data['model_id'], $data['ifmain']);
        // 获取原字段名
        $old_name = $this->where('id', $id)->value('name');

        if ($result) {
            //判断字段名唯一性
            if ($this->where('name', $data['name'])->where('model_id', $data['model_id'])->where('id', '<>', $id)->value('id')) {
                throw new \Exception("字段'" . $data['name'] . "已经存在`");
            }
            $data['ifeditable'] = isset($data['ifeditable']) ? intval($data['ifeditable']) : 0;
            $data['ifrequire'] = isset($data['ifrequire']) ? intval($data['ifrequire']) : 0;
            if ($data['ifrequire'] && !$data['ifeditable']) {
                throw new \Exception("必填字段不可以隐藏");
            }
            $sql = <<<EOF
            ALTER TABLE `{$table}`
            CHANGE COLUMN `{$old_name}` `{$data['name']}` {$data['define']} COMMENT '{$data['title']}';
EOF;
            try {
                Db::execute($sql);
            } catch (\Exception $e) {
                $this->addField($data);
            }
            $fieldInfo = Db::name('field_type')->where('name', $data['type'])->field('ifoption,ifstring')->find();
            $data['id'] = $id;
            $data['ifmain'] = intval($data['ifmain']);
            $data['ifsearch'] = isset($data['ifsearch']) ? ($fieldInfo['ifstring'] ? intval($data['ifsearch']) : 0) : 0;
            $data['status'] = isset($data['status']) ? intval($data['status']) : 0;
            $data['options'] = $fieldInfo['ifoption'] ? $data['options'] : '';

            if ($fieldInfo['ifoption']) {
                if (isset($data['jsonrule']['string'])) {
                    unset($data['jsonrule']['string']);
                }
                if (isset($data['jsonrule']['thumb'])) {
                    unset($data['jsonrule']['thumb']);
                }
                if (isset($data['jsonrule']['file'])) {
                    unset($data['jsonrule']['file']);
                }
                if (!empty($data['jsonrule']['choose']['table']) && !empty($data['jsonrule']['choose']['key']) && !empty($data['jsonrule']['choose']['value'])) {
                    $data['jsonrule'] = json_encode($data['jsonrule'], true);
                }
            }
            if ($fieldInfo['ifstring']) {
                if (isset($data['jsonrule']['choose'])) {
                    unset($data['jsonrule']['choose']);
                }
                if (isset($data['jsonrule']['thumb'])) {
                    unset($data['jsonrule']['thumb']);
                }
                if (isset($data['jsonrule']['file'])) {
                    unset($data['jsonrule']['file']);
                }
                if (!empty($data['jsonrule']['string']['table']) && !empty($data['jsonrule']['string']['key'])) {
                    $data['jsonrule'] = json_encode($data['jsonrule'], true);
                }
            }
            if (('image' == $data['type'] || 'images' == $data['type'])) {
                if (isset($data['jsonrule']['choose'])) {
                    unset($data['jsonrule']['choose']);
                }
                if (isset($data['jsonrule']['string'])) {
                    unset($data['jsonrule']['string']);
                }
                if (isset($data['jsonrule']['file'])) {
                    unset($data['jsonrule']['file']);
                }
                if (isset($data['jsonrule']['thumb']['ifon'])) {
                    $data['jsonrule'] = json_encode($data['jsonrule'], true);
                }
            }
            //文件设置
            if ('files' == $data['type']) {
                if (isset($data['jsonrule']['choose'])) {
                    unset($data['jsonrule']['choose']);
                }
                if (isset($data['jsonrule']['string'])) {
                    unset($data['jsonrule']['string']);
                }
                if (isset($data['jsonrule']['thumb'])) {
                    unset($data['jsonrule']['thumb']);
                }
                if (isset($data['jsonrule']['file']['type'])) {
                    $data['jsonrule'] = json_encode($data['jsonrule'], true);
                }
            }
            if (is_array($data['jsonrule'])) {
                $data['jsonrule'] = '';
            }
            $this->allowField(['name', 'title', 'type', 'define', 'value', 'options', 'remark', 'ifeditable', 'ifsearch', 'ifrequire', 'orders', 'status', 'jsonrule'])->save($data, ['id' => intval($data['id'])]);
            Cache::clear('db_' . $table);
            Cache::clear('db_mode_field');
            return true;
        } else {
            throw new \Exception('修改字段' . $old_name . '失败~' . $table . '表不存在');
        }
    }

    /**
     * 删除字段
     * @param null $field 字段数据
     * @return bool
     */
    public function deleteField($id) {
        $info = self::where('id', $id)->field('model_id,ifmain,name')->find();
        list($table, $result) = $this->tableExist($info['model_id'], $info['ifmain']);
        if ($result) {
            $sql = <<<EOF
            ALTER TABLE `{$table}`
            DROP COLUMN `{$info['name']}`;
EOF;
            Db::execute($sql);
            $this->get($id)->delete();
            Cache::clear('db_' . $table);
            Cache::clear('db_mode_field');
        } else {
            throw new \Exception($table . '表不存在，删除字段' . $info['name'] . '失败~');
        }
    }

    /**
     * 复制模型内容
     */
    public function copyData($mid, $ids, $num, $cname = '') {
        $modelInfo = Db::name('model')->where('id', $mid)->where('status', 1)->find();
        if (empty($modelInfo)) {
            throw new \Exception('模型被冻结或已不存在~');
        }
        $data = Db::name($modelInfo['table'])->where('id', 'in', $ids)->select();
        if (empty($data)) {
            throw new \Exception('复制的数据已经被删除~');
        }
        if (2 == $modelInfo['type']) {
            $dataExtOld = Db::name($modelInfo['table'] . $this->ext_table)->where('did', 'in', $ids)->select();
            $dataExt = [];
            foreach ($dataExtOld as $vo) {
                $dataExt[$vo['did']] = $vo;
            }
            unset($dataExtOld);
        }
        //主副表
        if (isset($dataExt)) {
            for ($i = 0; $i < $num; $i++) {
                foreach ($data as $vo) {
                    $voExt = $dataExt[$vo['id']];
                    if (isset($vo['cname'])) {
                        $vo['cname'] = $cname;
                    }
                    unset($vo['id']);
                    $id = Db::name($modelInfo['table'])->insertGetId($vo);
                    $voExt['did'] = $id;
                    Db::name($modelInfo['table'] . $this->ext_table)->insert($voExt);
                }
            }
            //单表
        } else {
            foreach ($data as &$vo) {
                if (isset($vo['cname'])) {
                    $vo['cname'] = $cname;
                }
                unset($vo['id']);
            }
            $allData = [];
            for ($i = 0; $i < $num; $i++) {
                foreach ($data as $v) {
                    $allData[] = $v;
                }
            }
            Db::name($modelInfo['table'])->insertAll($allData);
        }
        Cache::clear('db_' . $modelInfo['table']);
    }
    
}