# 设置 Adminlte 皮肤

默认情况下，扩展程序为AdminLTE使用蓝色皮肤。 您可以在配置文件中更改它。

```php
'components' => [
    'assetManager' => [
        'bundles' => [
            'dmstr\web\AdminLteAsset' => [
                'skin' => 'skin-black',
            ],
        ],
    ],
],
```

> 注意：仅当您通过配置覆盖外观时才使用 `AdminLteHelper::skinClass()` 否则你将无法获得正确的css类。

以下是可用皮肤的列表：

```
"skin-blue",
"skin-black",
"skin-red",
"skin-yellow",
"skin-purple",
"skin-green",
"skin-blue-light",
"skin-black-light",
"skin-red-light",
"skin-yellow-light",
"skin-purple-light",
"skin-green-light"
```

Disabling skin file loading, when using bundled assets

```php
Yii::$container->set(
    AdminLteAsset::className(),
    [
        'skin' => false,
    ]
);
```

If you want to use native DOM of headers AdminLTE

```
<h1>
    About <small>static page</small>
</h1>
```

then you can follow the code:

```
/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = 'About';

$this->beginBlock('content-header'); ?>
About <small>static page</small>
<?php $this->endBlock(); ?>

<div class="site-about">
    <p> This is the About page. You may modify the following file to customize its content: </p>
    <code><?= __FILE__ ?></code>
</div>
```