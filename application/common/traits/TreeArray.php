<?php

namespace app\common\traits;

trait TreeArray {

    //多维树结构
    protected function buildTree($list, $rootId = 0) {
        $refer = [];
        foreach ($list as $key => $data) {
            $refer[$data['id']] = &$list[$key];
        }
        $tree = [];
        foreach ($list as $key => $data) {
            // 判断是否存在parent  
            if (!isset($data['pid'])) {
                $parentId = $list[$key]['pid'] = pathToId($data['path']);
            } else {
                $parentId = $list[$key]['pid'];
            }
            if ($rootId == $parentId) {
                $tree[$data['id']] = & $list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $refer[$parentId]['cnode'][$data['id']] = & $list[$key];
                }
            }
        }
        return $tree;
    }

}
