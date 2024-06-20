<?php

return [
    'group' => '云市场',
    'plugins' => [
       'title' => '插件',
       'create' => '创建插件',
       'import' => '导入插件',
       'form' => [
            'alias' => '插件名称',
            'alias-placeholder' => '请输入插件名称',
            'name' => '插件标识',
            'name-placeholder' => '插件标识只允许输入英文字母以及下划线',
            'description' => '介绍',
            'description-placeholder' => '请详细描述这个插件的介绍',
            'icon' => '图标',
            'color' => '颜色',
            'file' => '插件压缩包',
            'logo' => '形象图',
       ],
       'actions' => [
            'generate' => 'Generate',
            'active' => 'Active',
            'disable' => 'Disable',
            'delete' => 'Delete',
           'github' => 'Github',
           'docs' => 'Docs',
       ],
        'notifications' => [
            'autoload' => [
                'title' => 'Error',
                'body' => 'The plugin could not be activated because the class could not be found. please run composer dump-autoload on your terminal'
            ],
            'enabled' => [
                'title' => 'Success',
                'body' => 'The plugin has been activated successfully.'
            ],
            'deleted' => [
                'title' => 'Success',
                'body' => 'The plugin has been deleted successfully.'
            ],
            'disabled' => [
                'title' => 'Success',
                'body' => 'The plugin has been deactivated successfully.'
            ],
            'import' => [
                'title' => 'Success',
                'body' => 'The plugin has been imported successfully.'
            ]
        ]
    ],
    'tables' => [
        'title' => 'Tables',
        'create' => 'Create Table',
        'edit' => 'Edit Table',
        'columns' => 'Table Columns',
        'form' => [
            'name' => 'Name',
            'type' => 'Type',
            'nullable' => 'Nullable',
            'foreign' => 'Foreign',
            'foreign_table' => 'Foreign Table',
            'foreign_col' => 'Foreign Column',
            'foreign_on_delete_cascade' => 'On Delete Cascade',
            'auto_increment' => 'Auto Increment',
            'primary' => 'Primary',
            'unsigned' => 'Unsigned',
            'default' => 'Default',
            'unique' => 'Unique',
            'index' => 'Index',
            'lenth' => 'Length',
            'migrated' => 'Migrated',
            'generated' => 'Generated',
            'created_at' => 'Created At',
            'updated_at' => 'Update At',
        ],
        'actions' => [
            'create' => 'Create Table',
            'migrate' => 'Migrate',
            'generate' => 'Generate',
            'columns' => 'Add Column',
            'add-id' => 'Add ID Column',
            'add-timestamps' => 'Add Timestamps',
            'add-softdeletes' => 'Add Soft Deletes',
        ],
        'notifications' => [
            'migrated' => [
                'title' => 'Success',
                'body' => 'The table has been migrated successfully.'
            ],
            'not-migrated' => [
                'title' => 'Error',
                'body' => 'The table could not be migrated.'
            ],
            'generated' => [
                'title' => 'Success',
                'body' => 'The table has been generated successfully.'
            ],
            'model' => [
                'title' => 'Error',
                'body' => 'The model could not be found generate it first.'
            ]
        ]
    ]
];
