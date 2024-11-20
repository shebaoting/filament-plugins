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
    ],
    'tables' => [
        'title' => '数据表',
        'create' => '创建数据表',
        'edit' => '编辑表',
        'columns' => '数据表字段',
        'form' => [
            'name' => '表名称',
            'type' => '类型',
            'nullable' => '可空',
            'foreign' => '外键',
            'foreign_table' => '外键表',
            'foreign_col' => '外键列',
            'foreign_on_delete_cascade' => '删除时级联',
            'auto_increment' => '自增',
            'primary' => '主键',
            'unsigned' => '无符号',
            'default' => '默认',
            'unique' => '唯一',
            'index' => '索引',
            'lenth' => '长度',
            'migrated' => '已迁移',
            'generated' => '已生成',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ],
        'actions' => [
            'create' => '创建',
            'migrate' => '迁移',
            'generate' => '生成',
            'columns' => '创建字段',
            'add-id' => '创建ID字段',
            'add-timestamps' => '创建时间字段',
            'add-softdeletes' => '创建软删除字段',
        ],
        'notifications' => [
            'migrated' => [
                'title' => '成功',
                'body' => '数据表已成功迁移。'
            ],
            'not-migrated' => [
                'title' => '错误',
                'body' => '数据表迁移失败。'
            ],
            'generated' => [
                'title' => '成功',
                'body' => '数据表已成功生成。'
            ],
            'model' => [
                'title' => '错误',
                'body' => '找不到模型，请先生成模型。'
            ]
        ]
    ]
];
