# Admin 模块

在配置中使用模块

```php
'modules' => [
    ...
    'admin' => [
        'class' => 'mdm\admin\Module',
        'controllerMap' => [
            'assignment' => [
                'class' => 'yiiplus\desktop\controllers\AssignmentController',
                'userClassName' => 'app\models\User',
                'idField' => 'user_id'
            ],
            'other' => [
                'class' => 'path\to\OtherController', // add another controller
            ],
        ],
	],
],

```

## 访问控制过滤器

访问控制过滤器（ACF）是一种简单的授权方法，最适合仅需要一些简单访问控制的应用程序。 正如其名称所示，ACF是一个动作过滤器，可以作为行为附加到控制器或模块。 ACF将检查一组访问规则，以确保当前用户可以访问所请求的操作。

```php
'as access' => [
    'class' => 'yiiplus\desktop\components\AccessControl',
    'allowActions' => [
        'site/login',
        'site/error',
    ]
]
```

## 过滤 ActionColumn 按钮

使用 `GridView` 时，还可以过滤按钮可见性。

```php
use yiiplus\desktop\components\Helper;

'columns' => [
    ...
    [
        'class' => 'yii\grid\ActionColumn',
        'template' => Helper::filterActionColumn('{view}{delete}{posting}'),
    ]
]
```

它将检查按钮的授权访问并显示或隐藏它。

要检查路由的访问权限，您可以使用

```php
use yiiplus\desktop\components\Helper;

if(Helper::checkRoute('delete')){
    echo Html::a(Yii::t('yiiplus/desktop', 'Delete'), ['delete', 'id' => $model->name], [
        'class' => 'btn btn-danger',
        'data-confirm' => Yii::t('yiiplus/desktop', 'Are you sure to delete this item?'),
        'data-method' => 'post',
    ]);
}
```
