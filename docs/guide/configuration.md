# 基础配置

安装扩展后，只需修改应用程序配置，如下所示：


```php
return [
	'layout' => '@yiiplus/desktop/views/layouts/main.php',

	...

	'modules' => [
		...
	    'admin' => [
	        'class' => 'yiiplus\desktop\Module',
	    ],
	    ...
	],

	...

	'components' => [
		...
		'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
        ...
	],

	...

	'as access' => [
	    'class' => 'yiiplus\desktop\components\AccessControl',
	    'allowActions' => [
	        'site/*',
            'admin/*',
	    ]
	],
];
```

要使用菜单管理器（可选），请在此处执行迁移：

`./yii migrate --migrationPath=@yiiplus/desktop/migrations/ --interactive=0`