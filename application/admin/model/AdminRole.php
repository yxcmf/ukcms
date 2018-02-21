<?php

namespace app\admin\model;

class AdminRole extends \think\Model {

    protected $pk = 'id';
    protected $autoWriteTimestamp = true;

    public function getRole($where = '', $column = 'id,path,names,description,status') {
        $list = self::where($where)->order('orders,id')->column($column);
        if (!empty($list)) {
            return $this->resort($list, 'id');
        } else {
            return[];
        }
    }

    protected function resort($list, $keyField) {
        $max_sort = 0;
        $min_sort = 1000;
        foreach ($list as &$vo) {   //获得最大深度
            $vo['deep'] = substr_count($vo['path'], ',');
            if ($vo['deep'] > $max_sort) {
                $max_sort = $vo['deep'];
            }
            if ($vo['deep'] < $min_sort) {
                $min_sort = $vo['deep'];
            }
            //每个深度一个数组$real_i,存放一行所有数据
            ${'rela_' . $vo['deep']}[] = $vo;
        }
        if (isset(${'rela_' . $min_sort})) {
            foreach (${'rela_' . $min_sort} as $n) {
                ${'all_column_' . $min_sort}[$n[$keyField]] = $n;
                if (isset(${'rela_' . ($min_sort + 1)})) {
                    foreach (${'rela_' . ($min_sort + 1)} as $y) {
                        if (stristr($y['path'], ',' . $n['id'] . ','))
                            ${'all_column_' . $min_sort}[$y[$keyField]] = $y; //将二级分类放在对应一级父分类后
                    }
                }
            }
        }
        if ($max_sort > $min_sort + 1) {
            for ($i = $min_sort + 1; $i < $max_sort; $i++) {
                if (empty(${'rela_' . $i})) {
                    ${'all_column_' . $i} = ${'rela_' . ($i + 1)};
                }
                if (is_array(${'all_column_' . ($i - 1)})) {
                    foreach (${'all_column_' . ($i - 1)} as $p) {
                        ${'all_column_' . $i}[$p[$keyField]] = $p;
                        if ($p['deep'] == $i) {
                            foreach (${'rela_' . ($i + 1)} as $r) {
                                if (stristr($r['path'], ',' . $p['id'] . ','))
                                    ${'all_column_' . $i}[$r[$keyField]] = $r; //将子分类放在对应父分类后
                            }
                        }
                    }
                }
                unset(${'all_column_' . ($i - 1)});
            }
        }
        if(0==$max_sort){
            return [];
        }else{
            if($max_sort>1&&$max_sort>$min_sort){
                return ${'all_column_' . ($max_sort - 1)};
            }else{
                return ${'all_column_' . $min_sort};
            }
        }
//        return $max_sort > 1 ? ${'all_column_' . ($max_sort - 1)} : ($max_sort == 0 ? [] : ${'all_column_' . $min_sort});
    }

}
