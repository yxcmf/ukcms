<?php

namespace app\home\taglib;

use think\template\TagLib;

/**
 * Uk标签库解析类
 * @author    YangXing <404133748@qq.com>
 */
class Uk extends Taglib {

    // 标签定义
    protected $tags = [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'select' => ['attr' => 'table,where,field,order,limit,id', 'alias' => 'sqllist'],
        'find' => ['attr' => 'table,id,field,key,id', 'alias' => 'sqlinfo'],
        'link' => ['attr' => 'group,intime,key,id', 'alias' => 'linklist'],
        'column' => ['attr' => 'type,select,key,id', 'alias' => 'columns', 'close' => 0],
    ];

    protected function replaceVar($str) {
        if (preg_match_all("/(?:\[)(.*)(?:\])/iU", $str, $matches)) {
            foreach ($matches[1] as $key => $vo) {
                $realVal = $this->autoBuildVar($vo);
                //不在开头
                if (0 != strpos($str, $matches[0][$key])) {
                    $realVal = '".' . $realVal;
                }
                //不在结尾
                if (strpos($str, $matches[0][$key]) < strlen($str) - strlen($matches[0][$key])) {
                    $realVal = $realVal . '."';
                }
                $str = str_replace($matches[0][$key], $realVal, $str);
            }
        }
        return $str;
    }

    /**
     * select标签解析 循环输出数据集
     * 格式：
     * {uk:select table="表名" where ="查询条件" field="主表字段" extfield="附表字段" order="排序" limit="区间"  id="循环变量名默认:vo" place="推荐位id半角逗号分隔" cid="栏目ID逗号分隔" nowtime="less|more"}
     * {$vo.字段名}
     * {/uk:select}
     * @access public
     * @param array $tag 标签属性
     * @param string $content 标签内容
     * @return string|void
     */
    public function tagSelect($tag, $content) {
        $table = trim($tag['table']);
        $where = isset($tag['where']) ? $tag['where'] : "status='1'";
        $field = (isset($tag['field']) && !empty($tag['field'])) ? $tag['field'] : '*';
        $extfield = isset($tag['extfield']) ? $tag['extfield'] : '';
        $order = isset($tag['order']) ? $tag['order'] : 'orders,id desc';
        $limit = isset($tag['limit']) ? $tag['limit'] : '';
        $place = isset($tag['place']) ? $tag['place'] : '';
        $cid = isset($tag['cid']) ? $tag['cid'] : 0;
        $id = isset($tag['id']) ? $tag['id'] : 'vo';
        $nowtime = isset($tag['nowtime']) ? $tag['nowtime'] : 'less';
        $modlength = isset($tag['mod']) ? $tag['mod'] : 0;

        //可传变量
        $cid = $this->replaceVar($cid);
        $where = $this->replaceVar($where);
        switch ($nowtime) {
            case 'less':
                $where.=empty($where) ? "create_time <" . time() : " and create_time <" . time();
                break;
            case 'more':
                $where.=empty($where) ? "create_time >" . time() : " and create_time >" . time();
                break;
            default:
                break;
        }
        $name = '$sql_list';
        $parseStr = "<?php $name=model('ModelField')->getDataList(\"$table\", \"$where\", \"$field\", \"$extfield\", \"$order\", \"$limit\", null, $cid, \"$place\"); ?>";
        $parseStr .= '<?php  $sum=count(' . $name . '); ?>';
        $parseStr .= '<?php  foreach(' . $name . ' as $key=>$' . $id . '): ?>';
        if ($modlength) {
            $parseStr .='<?php  $mod=$key%' . $modlength . '; ?>';
        }
        $parseStr .= $content;
        $parseStr .= '<?php endforeach; ?>';
        return $parseStr;
    }

    /**
     * find标签解析 循环输出数据集
     * 格式：
     * {uk:find table="表名" where ="查询条件" field="主表字段" extfield="附表字段"   key="id编号" id="变量默认:info}
     * {$info.字段名}
     * {/uk:find }
     * @access public
     * @param array $tag 标签属性
     * @param string $content 标签内容
     * @return string|void
     */
    public function tagFind($tag, $content) {
        $table = trim($tag['table']);
        $where = isset($tag['where']) ? $tag['where'] : "status='1'";
        $field = (isset($tag['field']) && !empty($tag['field'])) ? $tag['field'] : '*';
        $extfield = isset($tag['extfield']) ? $tag['extfield'] : '';
        $key = isset($tag['key']) ? $tag['key'] : 0;
        $id = isset($tag['id']) ? $tag['id'] : 'info';
        //可传变量
        $key = $this->replaceVar($key);
        $where = $this->replaceVar($where);
        $id = '$' . $id;
        $parseStr = "<?php $id=model('ModelField')->getDataInfo(\"$table\", \"$where\", \"$field\", \"$extfield\",\"\",$key); ?>";
        $parseStr .= $content;
        return $parseStr;
    }

    /**
     * link标签解析 循环输出广告位
     * 格式：
     * {uk:link group="分组英文标识"  intime="是否只调用有效时间内的:yes/no"  key="id编号"  id="循环变量名默认:vo" }
     * {$vo.字段名}
     * {/uk:link}
     * @access public
     * @param array $tag 标签属性
     * @param string $content 标签内容
     * @return string|void
     */
    public function tagLink($tag, $content) {
        $group = isset($tag['group']) ? $tag['group'] : "";
        $intime = isset($tag['intime']) ? $tag['intime'] : 'yes';
        $key = isset($tag['key']) ? $tag['key'] : 0;
        $id = isset($tag['id']) ? $tag['id'] : 'vo';
//可传变量
        $key = $this->replaceVar($key);

        $name = '$link_list';
        $parseStr = "<?php $name= model('Link')->getLinkList(\"$group\", \"$intime\", $key); ?>";
        $parseStr .= '<?php  foreach(' . $name . ' as $key=>$' . $id . '): ?>';
        $parseStr .= $content;
        $parseStr .= '<?php endforeach; ?>';
        return $parseStr;
    }

    /**
     * column标签解析 循环输出广告位
     * 格式：
     * {uk:column type="栏目格式：tree|sort"  select="筛选栏目状态：show|hide"  field="字段名称逗号分隔" key="根栏目id"  id="存储变量名:columns" }
     * @access public
     * @param array $tag 标签属性
     * @param string $content 标签内容
     * @return string|void
     */
    public function tagColumn($tag, $content) {
        $type = isset($tag['type']) ? $tag['type'] : 'tree';
        $select = isset($tag['select']) ? $tag['select'] : 'show';
        $field = isset($tag['field']) ? $tag['field'] : 'id,path,name,title,type,url';
        $key = isset($tag['key']) ? $tag['key'] : 0;
        $id = isset($tag['id']) ? $tag['id'] : 'columns';
        //可传变量
        $key = $this->replaceVar($key);

        $id = '$' . $id;
        $parseStr = "<?php $id= model('Column')->getColumn(\"$type\", \"$field\", 'id', $key, \"$select\"); ?>";
        return $parseStr;
    }

}
