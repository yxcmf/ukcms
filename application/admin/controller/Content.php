<?php

namespace app\admin\controller;

use think\Db;
use think\facade\Cache;

/**
 * 内容控制器
 */
class Content extends Common {

    use \app\common\traits\controller\Notice,
        \app\common\traits\controller\Translate;

    public function _empty() {
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
                    $kwhere.= "$vo like '%$getParam[keyword]%' or ";
                }
                $where.=substr($kwhere, 0, -4);
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
                ->order('orders,id desc')
                ->limit('column' == $model['purpose'] ? 1 : 2)
                ->column('id,name,title');
        $fieldStr = '';
        if (!empty($fieldArr)) {
            foreach ($fieldArr as $vo) {
                $fieldStr.=',' . $vo['name'];
            }
        }
        switch ($model['purpose']) {
            case 'column':
                if ($getParam['cid']) {
                    $childColumn = Db::name('column')->where("path like '%,$getParam[cid],%' and model_id='$model[id]'")->whereOr('id', $getParam['cid'])->column('name');
                    foreach ($childColumn as &$vo) {
                        $vo = "'$vo'";
                    }
                    $where = '' == $where ? '' : $where . ' and ';
                    $where .= "cname in(" . implode(',', $childColumn) . ")";
                }
                //当前模型可用推荐位
                $places = Db::name('place')->where('mid', 0)->whereOr('mid', $model['id'])->column('id,title');
                $list = Db::view($model['table'], 'id' . $fieldStr . ',uid,cname,hits,ifextend,update_time,places,orders,status')
                        ->where($where)
                        ->view('admin_user', 'realname', 'admin_user.id=' . $model['table'] . '.uid', 'LEFT')
                        ->order('orders,id desc')
                        ->paginate(15, false, [
                    'query' => $getParam
                ]);
                $this->assign('places', $places);
                $this->assign('list', $list);
                $this->assign('page', $list->render());
                $columnList = model('Column')->getColumn('id,name,path,title,type,model_id', 'sort', false, 'name');
                $this->assign('columnList', $columnList);
                $this->assign('fieldArr', $fieldArr);
                return $this->fetch('column');
            case 'independence':
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
                return $this->fetch('independence');
        }
    }

    public function add($mid = 0, $cname = '') {
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
            if ('column' == $info['purpose']) {
                $ifModCol = Db::name('column')->where('type', 2)->where('model_id', $modelId)->where('status', 1)->value('id');
                if (!$ifModCol) {
                    $this->error('还没有绑定此模型并且激活的列表栏目~');
                }
                $columnList = model('Column')->getColumn('id,name,path,title,type,model_id', 'sort', false);
                $placeList = Db::name('place')->where('mid', $modelId)->whereOr('mid', 0)->order('orders,id desc')->column('id,title');
                $this->assign([
                    'columnList' => $columnList,
                    'placeList' => $placeList,
                ]);
            }
            $fieldList = model('ModelField')->getFieldList($modelId);
            $this->assign('fieldList', $fieldList);
            $this->assign('mid', $modelId);
            $this->assign('modelName', $info['title']);
            //用于栏目列表添加链接
            $this->assign('cname', $cname);
            return $this->fetch();
        }
    }

    //导出数据
    public function exportdata($mid = 0, $cname = '') {
        $modelId = intval($mid);
        if (!$modelId) {
            $this->error('mid参数错误~');
        }
        $modelInfo = Db::name('model')->where('id', $modelId)->where('status', 1)->field('table,title,type,purpose')->find();
        if (empty($modelInfo)) {
            return $this->error('模型被冻结不可操作');
        }
        if ($this->request->isPost()) {
            $data = $this->request->post();
            //栏目
            if (!empty($data[cname])) {
                $where[] = "cname='$data[cname]'";
            }
            //时间
            $data['creattime'] = trim($data['creattime']);
            if (!empty($data['creattime'])) {
                list($beginTime, $endTime) = explode('-', $data['creattime']);
                $beginTime = strtotime(trim($beginTime));
                $endTime = strtotime(trim($endTime));
                $where[] = "create_time>$beginTime";
                $where[] =  "create_time<$endTime";
            }
            $whereStr = empty($where) ? '' : implode(' and ', $where);
            //限制条数
            $limit = $data['limitnum'] ? '0,' . intval($data['limitnum']) : '';

            $list = model('ModelField')->getDataList($modelInfo['table'], $whereStr, "*", "*", 'orders,id desc', $limit);
            if (empty($list)) {
                return $this->error('没有满足条件的模型信息可以导出');
            }
//            print_r($list);
//            die;
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->getProperties()->setCreator("UKcms")
                    ->setLastModifiedBy("UKcms Adminstrator")
                    ->setTitle("UKcms " . $modelInfo['table'])
                    ->setSubject("UKcms " . $modelInfo['table'])
                    ->setDescription("UKcms " . $modelInfo['table'])
                    ->setKeywords("excel")
                    ->setCategory("result file");

            $Letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
            $modelFields = Db::name('model_field')->where('model_id', $modelId)->where('name', 'not in', ['id', 'did', 'cname', 'uid', 'ifextend', 'status'])->order("orders ,id desc")->column('name,title,type');
            $i = 0;
            foreach ($modelFields as $value) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($Letters[$i] . '1', $value['title'] . '[' . $value['type'] . ']');
                $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($Letters[$i])->setWidth(30);
                $i++;
            }
            for ($i = 2; $i <= count($list)+1; $i++) {
                $j = 0;
                $n = $i - 2;
                foreach ($modelFields as $value) {
                    switch ($value['type']) {
                        case 'datetime':
                            $strValue = date('Y-m-d H:m:s', $list[$n][$value['name']]);
                            break;
                        case 'checkbox':
                        case 'select':
                        case 'files':
                        case 'tags':
                            $strValue = implode(',', $list[$n][$value['name']]);
                            break;
                        case 'image':
                            $strValue = $list[$n][$value['name']]['path'] . ($list[$n][$value['name']]['thumb'] ? '|' . $list[$n][$value['name']]['thumb'] : '');
                            break;
                        case 'images':
                            $strValue = '';
                            if (!empty($list[$n][$value['name']])) {
                                foreach ($list[$n][$value['name']] as $vo) {
                                    $strValue .= $vo['path'] . ($vo['thumb'] ? '|' . $vo['thumb'] : '') . ",";
                                }
                            }
                            break;
                        default:
                            $strValue = is_array($list[$n][$value['name']]) ? json_encode($list[$n][$value['name']]) : $list[$n][$value['name']];
                    }
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($Letters[$j] . $i, $strValue);
                    $j++;
                }
            }


            $objPHPExcel->getActiveSheet()->setTitle($modelInfo['table']);
            $objPHPExcel->setActiveSheetIndex(0);
            header("Content-Type: application/vnd.ms-excel");
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=ukcms_" . $modelInfo['table'] . ".xls");
            header("Content-Transfer-Encoding: binary ");
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        } else {
            if ('column' == $modelInfo['purpose']) {
                $columnList = model('Column')->getColumn('id,name,path,title,type,model_id', 'sort', false);
                $this->assign('columnList', $columnList);
            }
            $this->assign([
                'modelInfo' => $modelInfo,
                'mid' => $mid,
                //用于栏目列表添加链接
                'cname' => $cname,
            ]);
            return $this->fetch();
        }
    }

    //导入数据
    public function importdata($mid = 0, $cname = '') {
        $modelId = intval($mid);
        if (!$modelId) {
            $this->error('mid参数错误~');
        }
        $modelInfo = Db::name('model')->where('id', $modelId)->where('status', 1)->field('table,title,type,purpose')->find();
        if (empty($modelInfo)) {
            return $this->error('模型被冻结不可操作');
        }
        //翻译设置
        $baiduId = config('baidu_translate_id');
        $baiduSecret = config('baidu_translate_secret');

        if ($this->request->isPost()) {
            set_time_limit(0);

            $data = $this->request->post();
            if (isset($data['iftranslate'])) {
                $iftranslate = true;
                $strFields = Db::name('field_type')->where('ifstring', 1)->column('name');
            } else {
                $iftranslate = false;
            }
            //如果是栏目模型
            if ('column' == $modelInfo['purpose']) {
                $column = $data['cname'];
                //如果没有选择栏目
                if (!$column) {
                    $columnList = Db::name('column')->where('model_id', $mid)->column('name');
                    if (empty($columnList)) {
                        $this->error('请先添加此模型下的栏目~');
                    }
                    $cnum = count($columnList);
                }
            }
            //随机时间处理
            $data['creattime'] = trim($data['creattime']);
            if (!empty($data['creattime'])) {
                list($beginTime, $endTime) = explode('-', $data['creattime']);
                $beginTime = strtotime(trim($beginTime));
                $endTime = strtotime(trim($endTime));
            }
            $modelFields = Db::name('model_field')->where('model_id', $modelId)->where('name', 'not in', ['id', 'did', 'cname'])->column('name,ifmain,type,value,jsonrule');
//            vendor("phpexcel.PHPExcel");
            $file = $this->request->file('excel');
            if (null == $file) {
                $this->error('没有选择数据文件~');
            }
            $path = config('upload_temp_path');
            $info = $file->validate(['ext' => 'xlsx,xls,csv'])->move($path, 'model_data');

            //数据为空返回错误
            if (empty($info)) {
                $this->error('数据文件格式或大小不正确~');
            }
            $fileName = $info->getSaveName();
            $type = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $path = realpath($path . DIRECTORY_SEPARATOR . $fileName);
            //根据不同类型分别操作
            if ($type == 'xlsx' || $type == 'xls') {
                $objPHPExcel = \PHPExcel_IOFactory::load($path);
            } else if ($type == 'csv') {
                $objReader = \PHPExcel_IOFactory::createReader('CSV')
                        ->setDelimiter(',')
                        ->setInputEncoding('GBK') //不设置将导致中文列内容返回boolean(false)或乱码
                        ->setEnclosure('"')
                        ->setLineEnding("\r\n")
                        ->setSheetIndex(0);
                $objPHPExcel = $objReader->load($path);
            }

            $sheet = $objPHPExcel->getSheet(0);
            //获取行数与列数,注意列数需要转换
            $highestRowNum = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $highestColumnNum = \PHPExcel_Cell::columnIndexFromString($highestColumn);

            //取得字段，这里测试表格中的第一行为数据的字段，因此先取出用来作后面数组的键名
            $filed = array();
            for ($i = 0; $i < $highestColumnNum; $i++) {
                $cellName = \PHPExcel_Cell::stringFromColumnIndex($i) . '1';
                $cellVal = $sheet->getCell($cellName)->getValue(); //取得列内容
                if (is_object($cellVal))
                    $cellVal = $cellVal->__toString();
                //只保留存在的字段
                if (isset($modelFields[$cellVal])) {
                    $filed [$i] = $cellVal;
                }
            }
            //开始取出数据并存入数组
            $listMain = []; //主表数据
            $listExt = []; //附表数据
            $tableInfo = Db::query("show table status like '" . config('database.prefix') . $modelInfo['table'] . "'");
            $lastId = $tableInfo[0]['Auto_increment'];
            unset($tableInfo);

            for ($i = 2; $i <= $highestRowNum; $i++) {//ignore row 1
                $rowMain = [];
                $rowExt = [];
                for ($j = 0; $j < $highestColumnNum; $j++) {
                    $cellName = \PHPExcel_Cell::stringFromColumnIndex($j) . $i;
                    $cellVal = $sheet->getCell($cellName)->getValue();
                    if (is_object($cellVal)) {
                        //格式化excel单元数据
                        $cellVal = $cellVal->__toString();
                    }
                    //百度翻译
                    if ($iftranslate && $cellVal && $baiduId && $baiduSecret && in_array($modelFields[$filed[$j]]['type'], $strFields)) {
                        //翻译文本字段
                        $fyreturn = $this->translate($cellVal, $data['froml'], $data['tol'], [$baiduId, $baiduSecret]);
                        if (isset($fyreturn['error_code'])) {
                            $this->error('翻译错误提示：' . $fyreturn['error_code'] . '-' . $fyreturn['error_msg']);
                        } else {
                            $cellVal = '';
                            foreach ($fyreturn['trans_result'] as $vo) {
                                $cellVal.=$vo['dst'] . "\r\n";
                            }
                            $cellVal = substr($cellVal, 0, -4);
                            if (strlen($cellVal) > 300)
                                sleep(3);
                        }
                    }
                    if (isset($filed[$j])) {
                        if ($modelFields[$filed[$j]]['ifmain']) {
                            $rowMain[$filed[$j]] = $cellVal;
                        } else {
                            $rowExt[$filed[$j]] = $cellVal;
                        }
                    }
                }
                foreach ($modelFields as $key => $vo) {
                    //编辑器导入html标签处理
                    if ('Ueditor' == $vo['type'] || 'summernote' == $vo['type']) {
                        if (isset($rowMain[$key])) {
                            $rowMain[$key] = '<p>' . str_replace(["\r\n", "\r", "\n"], '</p><p>', $rowMain[$key]) . '</p>';
                        }
                        if (isset($rowExt[$key])) {
                            $rowExt[$key] = '<p>' . str_replace(["\r\n", "\r", "\n"], '</p><p>', $rowExt[$key]) . '</p>';
                        }
                    }
                    //默认值设置,字段在excel没有设置但是指定有默认值的
                    if (!isset($rowMain[$key]) && !isset($rowExt[$key]) && ($vo['value'] || $vo['jsonrule'])) {
                        if ('' != $vo['jsonrule']) {
                            $vo['jsonrule'] = json_decode($vo['jsonrule'], true);
                        }
                        if ($vo['value']) {
                            $defaultValue = $vo['value'];
                        } elseif (isset($vo['jsonrule']['string'])) {
                            $stringArray = Db::name($vo['jsonrule']['string']['table'])->where($vo['jsonrule']['string']['where'])->limit($vo['jsonrule']['string']['limit'])->order($vo['jsonrule']['string']['order'])->column($vo['jsonrule']['string']['key']);
                            if (!empty($stringArray)) {
                                $defaultValue = implode($vo['jsonrule']['string']['delimiter'], $stringArray);
                            } else {
                                $defaultValue = '';
                            }
                        }
                        if (isset($defaultValue)) {
                            if ($vo['ifmain']) {
                                $rowMain[$key] = $defaultValue;
                            } else {
                                $rowExt[$key] = $defaultValue;
                            }
                            unset($defaultValue);
                        }
                    }
                }
                //时间处理
                if (isset($timeArr)) {
                    $rowMain['create_time'] = $rowMain['update_time'] = rand($beginTime, $endTime);
                } else {
                    $rowMain['create_time'] = $rowMain['update_time'] = time();
                }
                //栏目归属
                if ('column' == $modelInfo['purpose']) {
                    if ($column) {
                        $rowMain['cname'] = $column;
                    } else {
                        //没有选择栏目情况下栏目平均分布内容
                        $key = $i % $cnum;
                        $rowMain['cname'] = $columnList[$key];
                    }
                    $rowMain['ifextend'] = 0;
                }
                if (!empty($rowMain)) {
                    $listMain[] = $rowMain;
                    if (2 == $modelInfo['type']) {
                        $rowExt['did'] = $lastId;
                        $listExt[] = $rowExt;
                        $lastId++;
                    }
                }
            }
            if (file_exists($path)) {
                @chmod($path, 0755);
                @unlink($path);
            }
            $numMain = Db::name($modelInfo['table'])->insertAll($listMain);
            if ($numMain) {
                $message = '';
                if (!empty($listExt)) {
                    $numExt = Db::name($modelInfo['table'] . '_data')->insertAll($listExt);
                    if ($numMain !== $numExt) {
                        $message = '主、附表导入的数据条数不一致!';
                    }
                }
                Cache::clear($modelInfo['table']);
                $this->success('主表导入了' . $numMain . '条数据~' . $message, url('content/' . $modelInfo['table']));
            } else {
                $this->error('数据导入失败~');
            }
        } else {
            if ('column' == $modelInfo['purpose']) {
                $ifModCol = Db::name('column')->where('type', 2)->where('model_id', $modelId)->where('status', 1)->value('id');
                if (!$ifModCol) {
                    $this->error('还没有绑定此模型并且激活的列表栏目~');
                }
                $columnList = model('Column')->getColumn('id,name,path,title,type,model_id', 'sort', false);
                $this->assign('columnList', $columnList);
            }
            $this->assign([
                'modelInfo' => $modelInfo,
                'mid' => $mid,
                //用于栏目列表添加链接
                'cname' => $cname,
                'canTranslate' => ($baiduId && $baiduSecret) ? true : false
            ]);
            return $this->fetch();
        }
    }

    //导入格式下载
    public function importexample($mid = 0) {
        $modelId = intval($mid);
        if (!$modelId) {
            $this->error('mid参数错误~');
        }
        $modelInfo = Db::name('model')->where('id', $modelId)->field('table,title,purpose')->find();
        if (empty($modelInfo)) {
            $this->error('模型不存在~');
        }
        $modelFields = Db::name('model_field')->where('model_id', $modelId)->where('name', 'not in', ['id', 'did', 'cname', 'create_time', 'update_time', 'uid', 'ifextend'])->order("orders ,id desc")->column('name,title,type');
//        vendor("phpexcel.PHPExcel");
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("UKcms")
                ->setLastModifiedBy("UKcms Adminstrator")
                ->setTitle("UKcms " . $modelInfo['table'])
                ->setSubject("UKcms " . $modelInfo['table'])
                ->setDescription("UKcms " . $modelInfo['table'])
                ->setKeywords("excel")
                ->setCategory("result file");
        if (!empty($modelFields)) {
            $Letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
            $i = 0;
            foreach ($modelFields as $key => $value) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($Letters[$i] . '1', $key)->setCellValue($Letters[$i] . '2', $value['title'] . '[' . $value['type'] . ']');
                $i++;
            }
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($Letters[$i] . '2', '请删除第二行并从第二行开始填写信息');
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($Letters[$i])->setWidth(35);

            $objPHPExcel->getActiveSheet()->setTitle($modelInfo['table']);
            $objPHPExcel->setActiveSheetIndex(0);
            header("Content-Type: application/vnd.ms-excel");
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=ukcms_" . $modelInfo['table'] . ".xls");
            header("Content-Transfer-Encoding: binary ");
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }
    }

    public function edit($mid = 0, $id = 0) {
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
            if ('column' == $info['purpose']) {
                $columnList = model('Column')->getColumn('id,name,path,title,type,model_id', 'sort', false, 'name');
                $placeList = Db::name('place')->where('mid', $modelId)->whereOr('mid', 0)->order('orders,id desc')->column('id,title');
                $this->assign([
                    'columnList' => $columnList,
                    'placeList' => $placeList,
                ]);
            }
            $fieldList = model('ModelField')->getFieldList($modelId, $contentId);
            $this->assign('fieldList', $fieldList);
            $this->assign('mid', $modelId);
            $this->assign('modelName', $info['title']);
            $this->assign('id', $contentId);
            return $this->fetch();
        }
    }

    public function delete($mid = 0, $id = 0) {
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
    public function move($mid) {
        if (!is_numeric($mid)) {
            return $this->error('参数错误~');
        }
        $tableName = Db::name('model')->where('id', $mid)->where('status', 1)->value('table');
        if (!$tableName) {
            return $this->error('模型被冻结不可操作~');
        }
        $ids = input('post.ids/a', null, 'intval');
        $cname = input('post.cname', '', 'trim');
        if (empty($ids) || empty($cname)) {
            return $this->error('没勾选内容或没选择栏目~');
        }
        if (Db::name($tableName)->where('id', 'in', $ids)->update(['cname' => $cname])) {
            Cache::clear('db_' . $tableName);
            $this->success('栏目修改成功~');
        } else {
            $this->error('栏目修改失败~');
        }
    }

//批量复制
    public function copy($mid) {
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

    public function setState($mid = 0, $id = 0, $status = 0) {
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

    public function changeOrder($mid, $id, $num) {
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
