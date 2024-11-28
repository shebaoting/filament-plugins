<?php

return [
    'group' => '云市场',
    'plugins' => [
        'title' => '插件',
        'create' => '创建插件',
        'import' => '导入插件',
        'form' => [
            'name' => '插件名称',
            'name-placeholder' => '插件名称',
            'identifier' => '插件标识',
            'identifier-placeholder' => '插件标识只允许输入英文字母以及下划线',
            'description' => '介绍',
            'description-placeholder' => '请详细描述这个插件的介绍',
            'icon' => '图标',
            'color' => '颜色',
            'file' => '插件压缩包',
            'logo' => '形象图',
        ],
        'actions' => [
            'generate' => '管理',
            'active' => '启用',
            'disable' => '禁用',
            'delete' => '删除',
            'github' => 'Github',
            'docs' => 'Docs',
        ],
        'notifications' => [
            'exists' => [
                'title' => '错误',
                'body' => '插件已存在。'
            ],
            'invalid_identifier' => [
                'title' => '错误',
                'body' => '插件标识无效，只能包含英文字母和下划线。'
            ],
            'autoload' => [
                'title' => '错误',
                'body' => '插件无法激活，因为找不到类。请在终端中运行 composer dump-autoload'
            ],
            'enabled' => [
                'title' => '成功',
                'body' => '插件已成功激活。'
            ],
            'deleted' => [
                'title' => '成功',
                'body' => '插件已成功删除。'
            ],
            'disabled' => [
                'title' => '成功',
                'body' => '插件已成功停用。'
            ],
            'import' => [
                'title' => '成功',
                'body' => '插件已成功导入。'
            ]
        ]
    ]
];
