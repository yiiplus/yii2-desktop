# 用户管理

对于希望将用户存储在数据库中的基本应用程序模板。 要使用此功能，请通过执行迁移创建所需的表。

`./yii migrate --migrationPath=@yiiplus/desktop/migrations`

然后更改用户组件配置：

```php
    'components' => [
        ...
        'user' => [
            'identityClass' => 'yiiplus\desktop\models\User',
            'loginUrl' => ['admin/user/login'],
        ]
    ]
```

然后您可以在 `admin/user` 访问此菜单。
