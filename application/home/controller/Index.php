<?php

namespace app\home\controller;

use think\Db;

class Index extends Common {

    public function index() {
        return $this->fetch('index/index');
    }

    public function search($mid = 0, $cid = 0, $keyword = '') {
        $result = $this->validate([
            'mid' => $mid,
            'cid' => $cid,
            'keyword' => $keyword
                ], [
            'mid|模型' => 'require|number',
            'cid|栏目' => 'number',
            'keyword|标题关键词' => 'require|chsDash',
        ]);
        if (true !== $result) {
            $this->error($result);
        }
        $modellist = Db::name('model')->where('purpose', 'column')->column('id,title,table');
        if (empty($modellist)) {
            return $this->error('没有可搜索模型~');
        }
        if ($mid) {
            $modelTable = Db::name('model')->where('id', $mid)->where('purpose', 'column')->value('table');
            if (empty($modelTable)) {
                $this->error('模型错误~');
            }
            $searchField = Db::name('model_field')->where('model_id', $mid)->where('ifmain', 1)->where('ifsearch', 1)->column('name');
            if (empty($searchField)) {
                $this->error('没有设置搜索字段~');
            }
            $where = '';
            foreach ($searchField as $vo) {
                $where .= "$vo like '%$keyword%' or ";
            }
            $where = '(' . substr($where, 0, -4) . ') ';
            $where .= " and status='1'";
            $list = model('ModelField')->getDataList($modelTable, $where, "*", "", "orders,id desc", "", [15, false, ['query' => ['mid' => $mid, 'cid' => $cid, 'keyword' => $keyword,]]], $cid);
        } else {
            foreach ($modellist as $key => $vo) {
                $mid=$key;
                $searchField = Db::name('model_field')->where('model_id', $mid)->where('ifmain', 1)->where('ifsearch', 1)->column('name');
                if (empty($searchField)) {
                    continue;
                }
                $where = '';
                foreach ($searchField as $v) {
                    $where .= "$v like '%$keyword%' or ";
                }
                $where = '(' . substr($where, 0, -4) . ') ';
                $where .= " and status='1'";
                $list = model('ModelField')->getDataList($vo['table'], $where, "*", "", "orders,id desc", "", [15, false, ['query' => ['mid' => $mid, 'cid' => $cid, 'keyword' => $keyword,]]], $cid);
                if ($list->isEmpty()) {
                    continue;
                } else {
                    break;
                }
            }
        }

        $this->assign([
            'mid' => $mid,
            'cid' => $cid,
            'keyword' => $keyword,
            'list' => $list->toArray(),
            'page' => $list->render(),
            'columnlist' => model('Column')->getColumn('sort', 'id,path,title,type,model_id'),
            'modellist' => $modellist
        ]);
        return $this->fetch('index/search');
    }

}
