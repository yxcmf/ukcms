<?php

namespace app\home\controller;

use think\Db;

class Column extends Common {

    public function index($name = '', $condition = '') {
        $result = $this->validate(['columnName' => $name], ['columnName|栏目标识' => 'require|alpha']);
        if (true !== $result) {
            abort(404, $result);
        }
        $Column = model('Column');
        try {
            $columnInfo = $Column->getColumnInfo($name);
        } catch (\Exception $ex) {
            abort(404, $ex->getMessage());
        }
        $ModelField = model('ModelField');
        if (2 == $columnInfo['type'] && $columnInfo['model_id']) {
            //获取内容模型信息
            $modelInfo = $ModelField->getModelInfo($columnInfo['model_id'], 'id,title,table,ifsub');
        }
        if ($this->request->isPost()) {
            if (!isset($modelInfo)) {
                $this->error('栏目禁止投稿~');
            }
            if (!$modelInfo['ifsub']) {
                $this->error($modelInfo['title'] . '模型禁止投稿~');
            }
            $data = $this->request->post();
            // 验证码
            if (config('captcha_signin_model')) {
                $captcha = $data['captcha'];
                $captcha == '' && $this->error('请输入验证码');
                if (!captcha_check($captcha)) {
                    //验证失败
                    $this->error('验证码错误或失效');
                }
            }
            //令牌验证
            $vresult = $this->validate($data, ['__token__|令牌' => 'require|token']);
            if (true !== $vresult) {
                $this->error($vresult);
            }
            $data['modelField']['cname'] = $name;
            $data['modelField']['orders'] = 100;
            $data['modelField']['status'] = 0;

            $data['modelFieldExt'] = isset($data['modelFieldExt']) ? $data['modelFieldExt'] : [];
            try {
                $ModelField->addModelData($modelInfo['id'], $data['modelField'], $data['modelFieldExt']);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success($modelInfo['title'] . '提交成功~');
        } else {
            //拓展字段
            if ($columnInfo['ext_model_id']) {
                $extInfo = $ModelField->getModelInfo($columnInfo['ext_model_id'], 'id,title,table,ifsub');
                $data = model('ModelField')->getDataInfo($extInfo['table'], "cname='" . $columnInfo['name'] . "' and status='1'", '*', '*');
                //更新点击量
                Db::name($extInfo['table'])->where('id', $data['id'])->update(['hits' => ['exp', 'hits+1']]);
                $this->assign('infoext', $data);
            }
            //列表栏目
            if (isset($modelInfo)) {
                if ($modelInfo['ifsub']) {
                    $fieldList = model('ModelField')->getFieldList($modelInfo['id']);
                    $this->assign('fieldList', $fieldList);
                }
                $where = "status='1' and create_time <" . time();
                //处理栏目列表筛选参数
                $param = [];
                if (isset($columnInfo['condition'])) {
                    $parr = explode(',', $columnInfo['condition']);
                    $pstr = "'" . str_replace(',', "','", $columnInfo['condition']) . "'";
                    $fieldInfo = model('ModelField')->getFieldList($columnInfo['model_id'], null, '', "status='1' and name in($pstr)", 'name,type,options,jsonrule,value,title');
                    //条件输出变量
                    $conditionParam = [];
                    $param = paramdecode($condition);
                    foreach ($parr as $vo) {
                        if (!empty($vo)) {
                            //判断是否是单选条件
                            $ifradio = 'checkbox' == $fieldInfo[$vo]['type'] ? false : true;
                            if ($ifradio) {
                                //单选选中参数
                                if (!empty($param[$vo])) {
                                    $conditionParam[$vo]['options'][$param[$vo]]['active'] = true;
                                    $nowParam = $param;
                                    $nowParam[$vo] = '';
                                    $conditionParam[$vo]['options'][$param[$vo]]['param'] = paramencode($nowParam);
                                    unset($nowParam);
                                    //单选条件
                                    $where.=" and $vo='$param[$vo]'";
                                }
                            } else {
                                //多选选中参数
                                if (!empty($param[$vo])) {
                                    $paramContent = explode('_', $param[$vo]);
                                    foreach ($paramContent as $k => $v) {
                                        $nowParamContent = $paramContent;
                                        unset($nowParamContent[$k]);
                                        $nowParam = $param;
                                        $nowParam[$vo] = implode('_', $nowParamContent);
                                        $conditionParam[$vo]['options'][$v]['active'] = 1;
                                        $conditionParam[$vo]['options'][$v]['param'] = paramencode($nowParam);
                                        unset($nowParam);
                                        unset($nowParamContent);
                                        //多选条件
                                        $where.=" and $vo like '%,$v,%'";
                                    }
                                    unset($paramContent);
                                }
                            }
                            //生成完整条件输出变量
                            $conditionParam[$vo]['title'] = $fieldInfo[$vo]['title'];
                            $conditionParam[$vo]['name'] = $vo;
                            foreach ($fieldInfo[$vo]['options'] as $k => $v) {
                                $conditionParam[$vo]['options'][$k]['title'] = $v;
                                //未选中条件参数生成
                                if (!isset($conditionParam[$vo]['options'][$k]['active'])) {
                                    $conditionParam[$vo]['options'][$k]['active'] = 0;
                                    if ($ifradio) {
                                        $nowParam = $param;
                                        $nowParam[$vo] = $k;
                                        $conditionParam[$vo]['options'][$k]['param'] = paramencode($nowParam);
                                    } else {
                                        $nowParam = $param;
                                        $nowParam[$vo] = empty($param[$vo]) ? $k : $param[$vo] . '_' . $k;
                                        $conditionParam[$vo]['options'][$k]['param'] = paramencode($nowParam);
                                    }
                                    unset($nowParam);
                                }
                                $conditionParam[$vo]['options'][$k]['url'] = url('column/index', ['name' => $columnInfo['name'], 'condition' => $conditionParam[$vo]['options'][$k]['param']]);
                                ksort($conditionParam[$vo]['options']);
                            }
                        }
                    }
                    $this->assign('condParam', $conditionParam);
                }
                //处理栏目列表筛选参数结束
                $pageNum = input('param.page');
                $page = [$columnInfo['list_row'], false, [
                        'page' => $pageNum? : 1,
                        'path' => empty($condition) ? $name . '-[PAGE].html' : $name . '-' . $condition . '-[PAGE].html'
                ]];
                if ('' != $columnInfo['listorder']) {
                    if (strpos($columnInfo['listorder'], 'id ') === false) {
                        $columnInfo['listorder'].=',id desc';
                    }
                } else {
                    $columnInfo['listorder'] = 'orders,addtime desc';
                }
                $list = model('ModelField')->getDataList($modelInfo['table'], $where, "*", "", $columnInfo['listorder'], "", $page, $columnInfo['id']);
                if ($list->isEmpty() && 1 != $page[2]['page']) {
                    abort(404, '第' . $page[2]['page'] . '页没有信息~');
                }
                $this->assign('param', $param);
                $this->assign('list', $list->toArray());
                $this->assign('page', $list->render());
            }

            $this->assign([
                'info' => $columnInfo,
                'crumbs' => $Column->getBreadcrumb($columnInfo['path'] . $columnInfo['id']),
                'rootId' => $this->getColumnId($columnInfo['path'] . $columnInfo['id']),
                'parentId' => $this->getColumnId($columnInfo['path'], 'parent'),
            ]);
            return $this->display('column/index/' . $columnInfo['template_list']);
        }
    }

//列表栏目内容
    public function content($name = '', $id = 0) {
        $result = $this->validate(['columnName' => $name, 'id' => $id], ['columnName|栏目标识' => 'require|alpha', 'id|文档ID' => 'require|number']);
        if (true !== $result) {
            abort(404, $result);
        }
        $Column = model('Column');
        try {
            $columnInfo = model('Column')->getColumnInfo($name);
        } catch (\Exception $ex) {
            abort(404, $ex->getMessage());
        }
        if (2 != $columnInfo['type']) {
            abort(404, '此类型栏目无内容文档');
        }
        $ModelField = model('ModelField');
        $modelTable = $ModelField->getModelInfo($columnInfo['model_id'], 'table');
        //内容所有字段
        $data = $ModelField->getDataInfo($modelTable, "id='" . $id . "' and  cname='" . $name . "' and status='1'", '*', '*');
        if (empty($data)) {
            abort(404, '内容不存在或未审核');
        }
        //更新点击量
        Db::name($modelTable)->where('id', $id)->update(['hits' => ['exp', 'hits+1']]);
        //下一篇
        $nextInfo = $ModelField->getDataInfo($modelTable, "status='1' and cname='$name' and create_time>'$data[create_time]'", 'id,cname,title', '', 'create_time');
        if (!empty($nextInfo)) {
            $this->assign('next', ['title' => $nextInfo['title'], 'url' => $nextInfo['url']]);
        }
        //上一篇
        $prevInfo = $ModelField->getDataInfo($modelTable, "status='1' and cname='$name' and create_time<'$data[create_time]'", 'id,cname,title', '', 'create_time desc');
        if (!empty($prevInfo)) {
            $this->assign('prev', ['title' => $prevInfo['title'], 'url' => $prevInfo['url']]);
        }
        //面包屑导航
        $this->assign([
            'info' => $columnInfo,
            'data' => $data,
            'crumbs' => $Column->getBreadcrumb($columnInfo['path'] . $columnInfo['id']),
            'rootId' => $this->getColumnId($columnInfo['path'] . $columnInfo['id']),
            'parentId' => $columnInfo['id'],
        ]);
        return $this->display('column/content/' . $columnInfo['template_content']);
    }

}
