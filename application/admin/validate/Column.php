<?php

namespace app\admin\validate;

use think\Validate;

class Column extends Validate {

    //定义验证规则
    public $rule = [
        'path|上级栏目' => 'require|regex:^[\d,]+$',
        'model_id|内容模型' => 'require|number',
        'ext_model_id|拓展模型' => 'number',
        'title|栏目标题' => 'require|chsAlphaNum',
        'name|栏目英文标识' => 'require|alpha|unique:column|notIn:index,search,sitemap,add,edit,delete,move,setstate,copy,changeorder',
//        'meta_title|栏目网页标题' => 'chsAlphaNum',
//        'meta_keywords|栏目网页关键词' => '',
//        'meta_description|栏目网页描述' => '',
        'cover_picture|栏目封面图' => 'number',
        'template_list|列表模板' => 'require|regex:^[a-zA-Z\d.]+$',
        'template_content|内容页模板' => 'require|regex:^[a-zA-Z\d.]+$',
        'list_row|翻页行数' => 'number|between:1,100',
//        'listorder|列表排序' => 'number|between:1,100',
        'orders|排序' => 'require|number',
        'status|状态' => 'require|in:0,1',
    ];
    //定义验证场景
    protected $scene = [
        'link' => ['path', 'title', 'cover_picture', 'url|自定义链接地址' => 'require|regex:^[a-zA-Z\/\:\&\=\?\d]*$', 'orders', 'status'],
//        'page' => ['path', 'model_id', 'title', 'name', 'meta_title', 'meta_keywords', 'meta_description', 'cover_picture', 'template_list', 'orders', 'status']
        'list' => ['path', 'ext_model_id', 'model_id', 'title', 'name', 'cover_picture', 'template_list', 'template_content', 'list_row', 'orders', 'status', 'urls|列表筛选条件' => 'regex:^[a-z\d,]+$', 'orders', 'status'],
        'page' => ['path', 'ext_model_id', 'title', 'name', 'cover_picture', 'template_list', 'orders', 'status'],
//        'other' => ['path', 'ext_model_id', 'model_id', 'cover_picture', 'template_list', 'template_content', 'list_row', 'orders', 'status', 'colinfo|标题和标识' => 'require'],
//        'batch' => ['title', 'name']
    ];

}
