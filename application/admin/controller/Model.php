<?php

namespace app\admin\controller;

use think\Db;
use think\facade\Cache;

/**
 * 内容模型控制器
 */
class Model extends Common
{
    use \app\common\traits\controller\Translate;

    protected $modelMenuPid = 100;

    public function index()
    {
        $mlist = model('Model')->order('orders,id')->paginate(15);
        $page = $mlist->render();
        $this->assign('mlist', $mlist);
        $this->assign('page', $page);
        return $this->fetch();
    }

    public function add()
    {
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
            if ('independence' == $data['purpose']) {
                $menuData['pid'] = $this->modelMenuPid;
                $menuData['title'] = $data['title'];
                $menuData['icon'] = 'fa fa-sticky-note-o';
                $menuData['url_type'] = 1;
                $menuData['url_value'] = 'admin/independent/' . $data['table'];
                $menuData['url_target'] = '_self';
                $menuData['orders'] = $data['orders'];
                $menuData['ifsystem'] = 1;
                $menuData['ifvisible'] = 1;
                model('adminMenu')->save($menuData);
                Cache::clear('db_admin_menu_tree');
            }

            $this->success('模型添加成功~', url('index'));
        } else {
            return $this->fetch();
        }
    }

    public function edit($id = 0)
    {
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
            if ('independence' == $info['purpose']) {
                //编辑后台菜单
                $menuData['title'] = $data['title'];
                $menuData['url_value'] = 'admin/independent/' . $data['table'];
                $menuData['orders'] = $data['orders'];
                model('adminMenu')->where('url_value', 'admin/independent/' . $info['table'])->update($menuData);
                Cache::clear('db_admin_menu_tree');
            }
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

    public function setState($id, $status)
    {
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

    public function setSub($id, $status)
    {
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

    public function delete($id)
    {
        $id = intval($id);
        if ($id < 1) {
            return '参数错误';
        }
        $Model = model('Model');
        $info = $Model->where('id', $id)->field('id,table,type,table,purpose')->find();
        try {
            $Model->deleteTable($info);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
        Cache::clear('db_' . $info['table']);
        Cache::clear('db_mode_field');
        Cache::clear('db_model');
        if ('independence' == $info['purpose']) {
            model('adminMenu')->where('url_value', 'admin/independent/' . $info['table'])->delete();
            Cache::clear('db_admin_menu_tree');
        }
        return true;
    }

    //导出数据
    public function exportdata($mid = 0, $cname = '')
    {
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
                $where[] = "create_time<$endTime";
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
            for ($i = 2; $i <= count($list) + 1; $i++) {
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
    public function importdata($mid = 0, $cname = '')
    {
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
                        $this->error('请先添加绑定此模型的栏目~');
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
                    ->setInputEncoding('GBK')//不设置将导致中文列内容返回boolean(false)或乱码
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
                $ifRowEmpty = true;
                for ($j = 0; $j < $highestColumnNum; $j++) {
                    $cellName = \PHPExcel_Cell::stringFromColumnIndex($j) . $i;
                    $cellVal = $sheet->getCell($cellName)->getValue();
                    if (is_object($cellVal)) {
                        //格式化excel单元数据
                        $cellVal = $cellVal->__toString();
                    }
                    if (!empty($cellVal)) {
                        $ifRowEmpty = false;
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
                                $cellVal .= $vo['dst'] . "\r\n";
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

                if ($ifRowEmpty) {
                    continue;
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
                $listMain[] = $rowMain;
                if (2 == $modelInfo['type']) {
                    $rowExt['did'] = $lastId;
                    $listExt[] = $rowExt;
                    $lastId++;
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
                    $this->dialog('还没有绑定此模型并且激活的列表栏目~', [
                        ['title' => '返回模型列表', 'class' => 'default', 'url' => url('index')],
                        ['title' => '去添加栏目', 'class' => 'success', 'url' => url('column/add')]
                    ], ['color' => 'warning', 'icon' => 'fa-warning']);
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
    public function importexample($mid = 0)
    {
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

}
