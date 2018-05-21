<?php

return[
    ['title' => '会员系统', 'icon' => 'fa fa-user-o', 'url_value' => '', 'ifvisible' => 1, 'orders' => 1,
        'children' => [
            ['title' => '账户管理', 'icon' => 'fa fa-users', 'url_value' => '', 'ifvisible' => 1, 'orders' => 1,
                'children' => [
                    ['title' => '会员管理', 'icon' => 'fa fa-user', 'url_value' => 'member/admin.user/index', 'ifvisible' => 1, 'orders' => 1,
                        'children' => [
                            ['title' => '添加会员', 'icon' => 'fa fa-plus', 'url_value' => 'member/admin.user/add', 'ifvisible' => 0, 'orders' => 2],
                            ['title' => '编辑会员', 'icon' => 'fa fa-edit', 'url_value' => 'member/admin.user/edit', 'ifvisible' => 0, 'orders' => 3],
                            ['title' => '会员状态', 'icon' => 'fa fa-toggle-on', 'url_value' => 'member/admin.user/setstate', 'ifvisible' => 0, 'orders' => 4],
                            ['title' => '删除会员', 'icon' => 'fa fa-times', 'url_value' => 'member/admin.user/delete', 'ifvisible' => 0, 'orders' => 5]
                        ]
                    ],
                    ['title' => '会员等级', 'icon' => 'fa fa-address-card', 'url_value' => 'member/admin.group/index', 'ifvisible' => 1, 'orders' => 2,
                       'children' => [
                            ['title' => '添加会员等级', 'icon' => 'fa fa-plus', 'url_value' => 'member/admin.group/add', 'ifvisible' => 0, 'orders' => 2],
                            ['title' => '编辑会员等级', 'icon' => 'fa fa-edit', 'url_value' => 'member/admin.group/edit', 'ifvisible' => 0, 'orders' => 3],
                            ['title' => '会员等级状态', 'icon' => 'fa fa-toggle-on', 'url_value' => 'member/admin.group/setstate', 'ifvisible' => 0, 'orders' => 4],
                            ['title' => '删除会员等级', 'icon' => 'fa fa-times', 'url_value' => 'member/admin.group/delete', 'ifvisible' => 0, 'orders' => 5]
                        ]
                    ],
                ]
            ]
        ]
    ]
];
