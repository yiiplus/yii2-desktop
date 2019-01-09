# YII2-DESKTOP
集成 Adminlte 及 RBAC 的 Yii2 后台框架。

[![Latest Stable Version](https://poser.pugx.org/yiiplus/yii2-desktop/v/stable)](https://packagist.org/packages/yiiplus/yii2-desktop)
[![Total Downloads](https://poser.pugx.org/yiiplus/yii2-desktop/downloads)](https://packagist.org/packages/yiiplus/yii2-desktop)
[![License](https://poser.pugx.org/yiiplus/yii2-desktop/license)](https://packagist.org/packages/yiiplus/yii2-desktop)
[![Monthly Downloads](https://poser.pugx.org/yiiplus/yii2-desktop/d/monthly)](https://packagist.org/packages/yiiplus/yii2-desktop)

## 安装

安装此扩展程序的首选方法是通过 [composer](http://getcomposer.org/download/).

编辑运行

```bash
php composer.phar require --prefer-dist yiiplus/yii2-desktop "^2.0.0"
```

或添加配置到项目目录下的`composer.json`文件的 require 部分

```
"yiiplus/yii2-desktop": "^1.0.0"
```

## 基础配置

```php
'layout' => '@yiiplus/desktop/views/layouts/main.php',

...

'modules' => [
    'admin' => [
        'class' => 'yiiplus\desktop\Module',
    ],
],

...

'as access' => [
    'class' => 'yiiplus\desktop\components\AccessControl',
    'allowActions' => [
        'admin/user/login',
        'admin/user/logout',
    ]
],
```