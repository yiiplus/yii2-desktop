# 表配置
```
return [
	'yii2_desktop' => [
        'table_config' => [
            'admin_user_table'=>'admin_user', // 后台用户表名
        ]
    ]
];
```

```
使用该命令在后台用户表生成所需字段:
`./yii migrate/up --migrationPath=@vendor/yiiplus/yii2-desktop/migrations`
```
