<?php

namespace app\admin\controller;

class Tag extends Common {

    public function index() {
        //搜索数据验证
        $getParam = ['mid' => $this->request->get('mid'), 'keyword' => $this->request->get('keyword')];
        $result = $this->validate($getParam, [
            'mid|模型ID' => 'number',
            'keyword|标题关键词' => 'chsDash',
        ]);
        if (true !== $result) {
            $this->error($result);
        }
        $where = '';
        if ($getParam['mid']) {
            $where = "mid='$getParam[mid]'";
        }
        if (!empty($getParam['keyword'])) {
            $where.='' == $where ? "title like '%$getParam[keyword]%'" : " and title like '%$getParam[keyword]%'";
        }
        $list = model('Tag')->where($where)->order('weight desc,id desc')->paginate(20, false, [
            'query' => $getParam
        ]);
        if (!$list->isEmpty()) {
            foreach ($list as $key => $vo) {
                $list[$key]['url'] = str_replace("\r\n", "<br>", $vo['url']);
            }
        }
        $mlist = model('Model')->where('purpose', 'column')->column('id,title');
        $this->assign('list', $list);
        $this->assign('page', $list->render());
        $this->assign('mlist', $mlist);
        return $this->fetch();
    }

    public function add() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, 'Tag');
            if (true !== $result) {
                return $this->error($result);
            }
            $Tag = model('Tag');
            try {
                $Tag->allowField(['mid', 'title', 'weight', 'url'])->save($data);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success('标签添加成功~', url('index'));
        } else {
            $mlist = model('Model')->where('purpose', 'column')->column('id,title');
            $this->assign('mlist', $mlist);
            return $this->fetch();
        }
    }

    public function edit($id = 0) {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['id'] = intval($data['id']);
            $result = $this->validate($data, 'Tag');
            if (true !== $result) {
                return $this->error($result);
            }
            $Tag = model('Tag');
            try {
                $Tag->update($data, [], ['mid', 'title', 'weight', 'url']);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success('标签编辑成功~', url('index'));
        } else {
            $id = intval($id);
            if ($id < 1) {
                $this->error('参数错误~');
            }
            $info = model('Tag')->where('id', $id)->find();
            $mlist = model('Model')->where('purpose', 'column')->column('id,title');
            $this->assign('info', $info);
            $this->assign('mlist', $mlist);
            return $this->fetch();
        }
    }

    public function importData() {
        if ($this->request->isPost()) {
            $mid = intval($this->request->post('mid'));
            $file = $this->request->file('tags');
            $path = config('upload_temp_path');
            $info = $file->validate(['ext' => 'xlsx,xls,csv'])->move($path, 'tags');
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

            $data = [];
            for ($i = 2; $i <= $highestRowNum; $i++) {//ignore row 1
                for ($j = 0; $j < $highestColumnNum; $j++) {
                    $cellName = \PHPExcel_Cell::stringFromColumnIndex($j) . $i;
                    $cellVal = $sheet->getCell($cellName)->getValue();
                    if (is_object($cellVal)) {
                        $cellVal = $cellVal->__toString();
                    }
                    $key = $i - 2;
                    switch ($j) {
                        case 0:
                            $data[$key]['mid'] = $mid;
                            $data[$key]['title'] = $cellVal;
                            break;
                        case 1:
                            $data[$key]['weight'] = intval($cellVal);
                            break;
                        case 2:
                            $cellVal = strpos($cellVal, "\r\n") !== false ? $cellVal : str_replace("\n", "\r\n", $cellVal);
                            $data[$key]['url'] = $cellVal;
                            break;
                    }
                    if (empty($data[$key]['title'])) {
                        unset($data[$key]);
                    }
                }
            }
            if (file_exists($path)) {
                chmod($path, 0755);
                @unlink($path);
            }
            $Tag = model('Tag');
            try {
                $Tag->insertAll($data);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success(($i - 2) . '个标签导入成功~', url('index'));
        } else {
            $mlist = model('Model')->where('purpose', 'column')->column('id,title');
            $this->assign('mlist', $mlist);
            return $this->fetch();
        }
    }

    public function setValue($id, $value) {
        $id = intval($id);
        $value = intval($value);
        if ($id <= 0) {
            return '参数错误';
        }
        if (model('Tag')->where('id', $id)->update(['weight' => $value])) {
            return true;
        } else {
            return '设置失败';
        }
    }

    public function delete($id = 0) {
        if ($this->request->isPost()) {
            $ids = input('post.ids/a', null, 'intval');
            if (empty($ids)) {
                $this->error('请先勾选需要删除的标签~');
            }
            $Tag = model('Tag');
            if ($Tag->destroy($ids)) {
                $this->success('标签删除成功~');
            } else {
                $this->error('标签删除失败~');
            }
        } else {
            $id = intval($id);
            $Tag = model('Tag');
            $taginfo = $Tag->get($id);
            if ($taginfo->delete()) {
                return true;
            } else {
                return '数据库操作失败';
            }
        }
    }

    public function getTagLink() {
        $content = $this->request->post('content');
        $mid = intval($this->request->post('mid'));
        $num = intval($this->request->post('num'));
        $mids = '0';
        if ($mid) {
            $mids.=',' . $mid;
        }
        $tags = model('Tag')->where('url', '<>', '')->where('mid', 'in', $mids)->order('weight desc,id')->limit(300)->column('title,url');
        if (!empty($tags) && !empty($content)) {
            foreach ($tags as $key => $vo) {
                $voArr = explode("\r\n", $vo);
                if (isset($voArr[$num])) {
                    $tags[$key] = trim($voArr[$num]);
                    if (strpos($tags[$key], '://') === false) {
                        //这里需要优化为前台链接
                        $tags[$key] = url($tags[$key]);
                    }
                } else {
                    unset($tags[$key]);
                }
            }
            if (!empty($tags)) {
                try {
                    $KeyReplace = new \str\KeyReplace($content, $tags);
                    $content = $KeyReplace->getResultText();
                } catch (\Exception $ex) {
                    exit($ex->getMessage());
                }
            }
        }
        exit($content);
    }

}
