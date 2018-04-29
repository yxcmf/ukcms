<?php

namespace app\admin\model;

use think\Db;

/**
 * 后台内容模型
 * @package app\admin\model
 */
class Model extends \think\Model {

    protected $ext_table = '_data';
    // 自动写入时间戳
    protected $pk = 'id';
    protected $autoWriteTimestamp = true;

    protected function tableExist($table) {
        if (true == Db::query("SHOW TABLES LIKE '{$table}'")) {
            return true;
        } else {
            return false;
        }
    }

    public function createTable($data) {
        $data['table'] = strtolower($data['table']);
        $table = config('database.prefix') . $data['table'];
        if ($this->tableExist($table)) {
            throw new \Exception('创建失败！' . $table . '表已经存在~');
        }
        $chooseRecord = [];
        $chooseSqlExt = '';
        // 新建主表
        switch ($data['purpose']) {
            case 'column':
                $chooseSql = '';
                if (isset($data['fields']['title'])) {
                    $chooseSql.=" `title` varchar(256) DEFAULT '' COMMENT '标题' ,";
                    $chooseRecord[] = [
                        'name' => 'title',
                        'title' => '标题',
                        'define' => 'varchar(256)',
                        'type' => 'text',
                        'ifsearch' => 1,
                        'ifeditable' => 1,
                        'iffixed' => 0,
                        'ifrequire' => 1,
                    ];
                }
                if (isset($data['fields']['keywords'])) {
                    $chooseSql.="`keywords` varchar(256) DEFAULT '' COMMENT 'SEO关键词',";
                    $chooseRecord[] = [
                        'name' => 'keywords',
                        'title' => 'SEO关键词',
                        'define' => 'varchar(256)',
                        'type' => 'tags',
                        'jsonrule' => '{"string":{"table":"tag","key":"title","delimiter":",","where":"","limit":"6","order":"[rand]"}}',
                        'ifeditable' => 1,
                        'iffixed' => 0,
                        'orders' => 100
                    ];
                }
                if (isset($data['fields']['description'])) {
                    $chooseSql.="`description` varchar(3000) DEFAULT '' COMMENT 'SEO摘要',";
                    $chooseRecord[] = [
                        'name' => 'description',
                        'title' => 'SEO摘要',
                        'define' => 'varchar(3000)',
                        'type' => 'textarea',
                        'ifeditable' => 1,
                        'iffixed' => 0,
                        'orders' => 100
                    ];
                }
                if (isset($data['fields']['content']) && 2 == $data['type']) {
                    $chooseSqlExt.="`content` text COMMENT '内容',";
                    $chooseRecord[] = [
                        'name' => 'content',
                        'title' => '内容',
                        'define' => 'text',
                        'type' => 'Ueditor',
                        'ifmain' => 0,
                        'ifeditable' => 1,
                        'iffixed' => 0,
                        'orders' => 100
                    ];
                }
                $sql = <<<EOF
            CREATE TABLE IF NOT EXISTS `{$table}` (
            `id` int(11) UNSIGNED AUTO_INCREMENT COMMENT '文档id' ,
            `cname` varchar(64) DEFAULT '' COMMENT '栏目标识' ,
            `ifextend` tinyint(2) UNSIGNED  DEFAULT 0 COMMENT '是否栏目拓展字段' ,
            `uid` int(10) UNSIGNED  DEFAULT 1 COMMENT '用户id' ,
            `places` varchar(64) DEFAULT '' COMMENT '推荐位' ,
            {$chooseSql}
            `create_time` int(11) UNSIGNED DEFAULT 0 COMMENT '创建时间' ,
            `update_time` int(11) UNSIGNED DEFAULT 0 COMMENT '更新时间' ,
            `orders` int(11) DEFAULT 100 COMMENT '排序' ,
            `status` tinyint(2) UNSIGNED DEFAULT 0 COMMENT '状态' ,
            `hits` int(11) UNSIGNED DEFAULT 0 COMMENT '点击量' ,
            PRIMARY KEY (`id`)
            )
            ENGINE=MyISAM
            DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
            CHECKSUM=0
            ROW_FORMAT=DYNAMIC
            DELAY_KEY_WRITE=0
            COMMENT='{$data['title']}模型表'
            ;
EOF;
                break;
            case 'independence':
                $sql = <<<EOF
            CREATE TABLE IF NOT EXISTS `{$table}` (
            `id` int(11) UNSIGNED AUTO_INCREMENT COMMENT '文档id' ,
            `uid` int(10) UNSIGNED DEFAULT 0 COMMENT '用户id' ,
            `create_time` int(11) UNSIGNED DEFAULT 0 COMMENT '创建时间' ,
            `update_time` int(11) UNSIGNED DEFAULT 0 COMMENT '更新时间' ,
            `orders` int(11) DEFAULT 100 COMMENT '排序' ,
            `status` tinyint(2) UNSIGNED DEFAULT 0 COMMENT '状态' ,
            PRIMARY KEY (`id`)
            )
            ENGINE=MyISAM
            DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
            CHECKSUM=0
            ROW_FORMAT=DYNAMIC
            DELAY_KEY_WRITE=0
            COMMENT='{$data['title']}模型表'
            ;
EOF;
                break;
        }
        Db::execute($sql);

        if ($data['type'] == 2) {
            // 新建附属表
            $sql = <<<EOF
                CREATE TABLE IF NOT EXISTS `{$table}{$this->ext_table}` (
                `did` int(11) UNSIGNED DEFAULT 0 COMMENT '文档id' ,
                {$chooseSqlExt}
                PRIMARY KEY (`did`)
                )
                ENGINE=MyISAM
                DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
                CHECKSUM=0
                ROW_FORMAT=DYNAMIC
                DELAY_KEY_WRITE=0
                COMMENT='{$data['title']}模型扩展表'
                ;
EOF;
            Db::execute($sql);
        }
        $data['status'] = isset($data['status']) ? $data['status'] : 0;
        $data['ifsub'] = isset($data['ifsub']) ? $data['ifsub'] : 0;
        //添加记录
        if (self::allowField(['title', 'table', 'type', 'purpose', 'orders', 'status', 'ifsub'])->save($data)) {
            return $this->addFieldRecord($data['purpose'], $data['type'], $chooseRecord);
        } else {
            throw new \Exception('创建表成功,添加记录表信息记录失败~');
        }
    }

    public function editTable($data, $info) {
        $data['table'] = strtolower($data['table']);
        $iftitle = ($info['title'] != $data['title'] && $data['title'] != '');
        if ($info['table'] != $data['table'] && $data['table'] != '') {
            $tablePrefix = config('database.prefix');
            $oldTable = $tablePrefix . $info['table'];
            if (!$this->tableExist($oldTable)) {
                throw new \Exception('修改失败！' . $info['table'] . '表不存在~');
            }
            $newTable = $tablePrefix . $data['table'];
            Db::startTrans();
            try {
                Db::execute("ALTER TABLE `{$oldTable}` RENAME TO `{$newTable}`");
                if ($iftitle) {
                    Db::execute("ALTER TABLE `{$newTable}` COMMENT = '{$data['title']}模型表'");
                }
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                throw new \Exception($e->getMessage());
            }
            if ($info['type'] == 2) {
                $oldTable .=$this->ext_table;
                $newTable .=$this->ext_table;
                Db::startTrans();
                try {
                    Db::execute("ALTER TABLE `{$oldTable}` RENAME TO `{$newTable}`");
                    if ($iftitle) {
                        Db::execute("ALTER TABLE `{$newTable}` COMMENT = '{$data['title']}模型拓展表'");
                    }
                    Db::commit();
                } catch (\Exception $e) {
                    Db::rollback();
                    throw new \Exception($e->getMessage());
                }
            }
        }
        $data['status'] = isset($data['status']) ? $data['status'] : 0;
        $data['ifsub'] = isset($data['ifsub']) ? $data['ifsub'] : 0;
        //修改记录
        $this->allowField(['title', 'table', 'orders', 'status', 'ifsub'])->save($data, ['id' => intval($data['id'])]);
    }

    protected function addFieldRecord($purpose, $type, $chooseRecord = []) {
        // 添加默认字段记录
        $default = [
            'model_id' => $this->getAttr('id'),
            'create_time' => request()->time(),
            'update_time' => request()->time(),
            'ifmain' => 1,
            'status' => 1,
            'iffixed' => 1,
            'orders' => 100
        ];
        switch ($purpose) {
            case 'column':
                $data = [
                    [
                        'name' => 'id',
                        'title' => '文档id',
                        'define' => 'int(11) UNSIGNED',
                        'type' => 'hidden',
                        'ifeditable' => 1,
                    ],
                    [
                        'name' => 'cname',
                        'title' => '栏目标识',
                        'define' => 'varchar(64)',
                        'type' => 'text',
                        'ifeditable' => 0,
                        'ifrequire' => 1
                    ],
                    [
                        'name' => 'ifextend',
                        'title' => '是否栏目拓展',
                        'define' => 'tinyint(2)',
                        'type' => 'number',
                        'ifeditable' => 0,
                        'value' => 0,
                    ],
                    [
                        'name' => 'uid',
                        'title' => '用户id',
                        'define' => 'int(10) UNSIGNED',
                        'type' => 'number',
                        'ifeditable' => 0,
                        'value' => 1,
                    ],
                    [
                        'name' => 'places',
                        'title' => '推荐位',
                        'define' => 'varchar(64)',
                        'type' => 'checkbox',
                        'ifeditable' => 0
                    ],
                    [
                        'name' => 'create_time',
                        'title' => '创建时间',
                        'define' => 'int(11) UNSIGNED',
                        'type' => 'datetime',
                        'value' => 0,
                        'ifeditable' => 1,
                        'orders' => 200
                    ],
                    [
                        'name' => 'update_time',
                        'title' => '更新时间',
                        'define' => 'int(11) UNSIGNED',
                        'type' => 'datetime',
                        'ifeditable' => 0,
                        'value' => 0,
                        'orders' => 200
                    ],
                    [
                        'name' => 'orders',
                        'title' => '排序',
                        'define' => 'int(10) UNSIGNED',
                        'type' => 'number',
                        'ifeditable' => 1,
                        'value' => 100,
                        'orders' => 200
                    ],
                    [
                        'name' => 'status',
                        'title' => '状态',
                        'define' => 'tinyint(2)',
                        'type' => 'radio',
                        'ifeditable' => 1,
                        'value' => 1,
                        'options' => '0:禁用
1:启用',
                        'orders' => 200
                    ],
                    [
                        'name' => 'hits',
                        'title' => '点击量',
                        'define' => 'int(10) UNSIGNED',
                        'type' => 'number',
                        'ifeditable' => 1,
                        'value' => 0,
                        'orders' => 200
                    ]
                ];
                if ([] != $chooseRecord) {
                    $data = array_merge($data, $chooseRecord);
                }
                break;
            case 'independence':
                $data = [
                    [
                        'name' => 'id',
                        'title' => '文档id',
                        'define' => 'int(11) UNSIGNED',
                        'type' => 'hidden',
                        'ifeditable' => 1,
                    ],
                    [
                        'name' => 'uid',
                        'title' => '用户id',
                        'define' => 'int(10) UNSIGNED',
                        'type' => 'number',
                        'ifeditable' => 0,
                        'value' => 0,
                    ],
                    [
                        'name' => 'create_time',
                        'title' => '创建时间',
                        'define' => 'int(11) UNSIGNED',
                        'type' => 'datetime',
                        'ifeditable' => 1,
                        'value' => 0,
                        'orders' => 200
                    ],
                    [
                        'name' => 'update_time',
                        'title' => '更新时间',
                        'define' => 'int(11) UNSIGNED',
                        'type' => 'datetime',
                        'ifeditable' => 0,
                        'value' => 0,
                        'orders' => 200
                    ],
                    [
                        'name' => 'orders',
                        'title' => '排序',
                        'define' => 'int(10) UNSIGNED',
                        'type' => 'number',
                        'ifeditable' => 1,
                        'value' => 100,
                        'orders' => 200
                    ],
                    [
                        'name' => 'status',
                        'title' => '状态',
                        'define' => 'tinyint(2)',
                        'type' => 'radio',
                        'ifeditable' => 1,
                        'value' => 1,
                        'options' => '0:禁用
1:启用',
                        'orders' => 200
                    ]
                ];
                break;
        }
        if ($type == 2) {
            $data[] = [
                'name' => 'did',
                'title' => '附表文档id',
                'define' => 'int(11) UNSIGNED',
                'type' => 'hidden',
                'ifeditable' => 0,
                'ifmain' => 0,
            ];
        }
        foreach ($data as $item) {
            $item = array_merge($default, $item);
            Db::name('model_field')->insert($item);
        }
    }

    public function deleteTable($info) {
//        $num = Db::name($info['table'])->count();
//        if ($num != 0) {
//            throw new \Exception($info['table'] . '表中存有数据不可以删除~');
//            return false;
//        }
        $cnum = Db::name('Column')->where('model_id', $info['id'])->count();
        if ($cnum > 0) {
            throw new \Exception('还有' . $cnum . '个栏目关联此模型,不可删除');
        }
        $table_name = config('database.prefix') . $info['table'];
        Db::execute("DROP TABLE IF EXISTS `{$table_name}`");
        if ($info['type'] == 2) {
            $table_name.=$this->ext_table;
            Db::execute("DROP TABLE IF EXISTS `{$table_name}`");
        }
        //删除数据库记录
        self::get($info['id'])->delete();
        Db::name('model_field')->where('model_id', $info['id'])->delete();
    }

}
