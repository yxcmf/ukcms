<?php

namespace app\home\controller;

use think\Controller;
use think\Db;
use seo\Sitemap as Sitemap;

class Seo extends Controller {

    public function Sitemap() {
        $Sitemap = new Sitemap();
        $rootUrl = $this->request->domain();
        $limitNum = 1000;
        //单页栏目
        $pageColumn = '';
        $Sitemap->AddItem($rootUrl . url('index/index'), 0, 'always', time() - rand(3600, 172800));
        $columList = model('Column')->getColumn('sort', 'id,path,name,url,type,update_time');
        if (!empty($columList)) {
            foreach ($columList as $vo) {
                $Sitemap->AddItem($rootUrl . $vo['url'], 1, 'always', $vo['update_time']);
                if (1 == $vo['type']) {
                    $pageColumn.="'".$vo['name'] . "',";
                }
            }
        }
        if ('' != $pageColumn) {
            $pageColumn = " and cname not in(".substr($pageColumn, 0, -1).")";
        }
        $modelList = Db::name('model')->where('status', 1)->where('purpose', 'column')->column('table');
        if (!empty($modelList)) {
            $num = 1;
            $modelField = model('ModelField');
            $volist = [];
            foreach ($modelList as $vo) {
                $volist = $modelField->getDataList($vo, "status='1'$pageColumn", 'id,cname,update_time', '', 'update_time desc');
                if (!empty($volist)) {
                    foreach ($volist as $v) {
                        $Sitemap->AddItem($rootUrl . $v['url'], 2, 'weekly', $v['update_time']);
                        $num++;
                        if ($num >= $limitNum) {
                            break;
                        }
                    }
                }
            }
        }
        $Sitemap->Show();
    }

}
