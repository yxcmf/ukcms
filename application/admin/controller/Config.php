<?php

namespace app\admin\controller;

use app\admin\model\Config as ConfigModel;

/**
 * 系统配置控制器
 * @package app\admin\controller
 */
class Config extends Common {

    public function index($group = 'base') {
        $fieldArr = 'modelField';
        // 保存数据
        if ($this->request->isPost()) {
            $data = $this->request->post($fieldArr . '/a');
            $Config = model('Config');
            // 查询该分组下所有的配置项名和类型
            $items = $Config->where('group', $group)->where('status', 1)->column('name,type');
            $updates = [];
            foreach ($items as $name => $type) {
                if (!isset($data[$name])) {
                    switch ($type) {
                        // 开关
                        case 'switch':
                            $data[$name] = 0;
                            break;
                        case 'checkbox':
                            $data[$name] = '';
                            break;
                    }
                } else {
                    // 如果值是数组则转换成字符串，适用于复选框等类型
                    if (is_array($data[$name])) {
                        $data[$name] = implode(',', $data[$name]);
                    }
                    switch ($type) {
                        // 开关
                        case 'switch':
                            $data[$name] = 1;
                            break;
                    }
                }
                if (isset($data[$name])) {
                    $updates[] = ['name' => $name, 'value' => $data[$name]];
                }
            }
            $Config->saveAll($updates);
            cache('system_config', null);
            return $this->success('设置更新成功', url('index', ['group' => $group]));
        } else {
            // 数据列表
            $configList = model('Config')->where('group', $group)
                    ->where('status', 1)
                    ->order('orders,id desc')
                    ->column('name,title,remark,type,value,options');

            foreach ($configList as &$value) {
                if ($value['options'] != '') {
                    $value['options'] = parse_attr($value['options']);
                }
                if ($value['type'] == 'checkbox') {
                    $value['value'] = empty($value['value']) ? [] : explode(',', $value['value']);
                }
                if ($value['type'] == 'datetime') {
                    $value['value'] = empty($value['value']) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', $value['value']);
                }
                if ($value['type'] == 'date') {
                    $value['value'] = empty($value['value']) ? '' : date('Y-m-d', $value['value']);
                }
                if ($value['type'] == 'image') {
                    $value['param'] = ['dir' => 'images', 'module' => 'admin', 'watermark' => 0];
                }
                if ($value['type'] == 'images') {
                    $value['param'] = ['dir' => 'images', 'module' => 'admin', 'watermark' => 0];
                    if (!empty($value['value'])) {
                        $value['value'].=',';
                    }
                }
                if ($value['type'] == 'files') {
                    $value['param'] = ['dir' => 'files', 'module' => 'admin'];
                    if (!empty($value['value'])) {
                        $value['value'].=',';
                    }
                }
                if ($value['type'] == 'Ueditor') {
                    $value['value'] = htmlspecialchars_decode($value['value']);
                }
                $value['fieldArr'] = 'modelField';
            }
            //字段类型列表
//            $fieldList = FieldTypeModel::order('orders')->column('*');
//            $this->assign('fieldList', $fieldList);
            $this->assign('path', '');
            $this->assign('groupArray', config('config_group'));
            $this->assign('fieldList', $configList);
            $this->assign('group', $group);
            return $this->fetch();
        }
    }

}
