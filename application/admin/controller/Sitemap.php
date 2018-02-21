<?php

namespace app\admin\controller;

use think\Db;
use seo\Sitemap as SitemapClass;

class Sitemap extends Common {

    public function index() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $Sitemap = new SitemapClass();
            $rootUrl = $this->request->domain();
            $data['num'] = intval($data['num']);
            //单页栏目
            $pageColumn = '';
            $Sitemap->AddItem($rootUrl, intval($data['index']['priority']), $data['index']['changefreq'], time());
            $columList = Db::name('column')->where('status', 1)->order('update_time desc')->column('name,type,update_time');
            if (!empty($columList)) {
                foreach ($columList as $vo) {
                    $Sitemap->AddItem($rootUrl . '/' . $vo['name'] . '.html', intval($data['colum']['priority']), $data['colum']['changefreq'], $vo['update_time']);
                    if (1 == $vo['type']) {
                        $pageColumn.="'" . $vo['name'] . "',";
                    }
                }
            }
            if ('' != $pageColumn) {
                $pageColumn = " and cname not in(" . substr($pageColumn, 0, -1) . ")";
            }
            $modelList = Db::name('model')->where('status', 1)->where('purpose', 'column')->column('table');
            if (!empty($modelList)) {
                $num = 1;
                $volist = [];
                foreach ($modelList as $vo) {
                    $volist = Db::name($vo)->where("status='1'$pageColumn")->order('update_time desc')->column('id,cname,update_time');
                    if (!empty($volist)) {
                        foreach ($volist as $v) {
                            $Sitemap->AddItem($rootUrl . '/' . $v['cname'] . '/' . $v['id'] . '.html', intval($data['content']['priority']), $data['content']['changefreq'], $v['update_time']);
                            $num++;
                            if ($num >= $data['num']) {
                                break;
                            }
                        }
                    }
                }
            }
            if (isset($data['root'])) {
                try {
                    $Sitemap->SaveToFile(ROOT_PATH . 'Sitemap.xml');
                } catch (\Exception $ex) {
                    $this->error($ex->getMessage());
                }
                $this->success("Sitemap.xml文件已生成到网站根目录，动态Sitemap将失效");
            } else {
                header("Content-Type:application/vnd.adobe.workflow");
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Disposition: attachment;filename=Sitemap.xml");
                header("Content-Transfer-Encoding: binary ");
                $Sitemap->Show();
            }
        } else {
            return $this->fetch();
        }
    }

}
