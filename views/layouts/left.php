<?php
/**
 * yiiplus/yii2-desktop
 *
 * @category  PHP
 * @package   Yii2
 * @copyright 2018-2019 YiiPlus Ltd
 * @license   https://github.com/yiiplus/yii2-desktop/licence.txt Apache 2.0
 * @link      http://www.yiiplus.com
 */

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
?>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= Yii::$app->user->identity->avatar ? Yii::$app->request->hostInfo . '/' . Yii::$app->user->identity->avatar : $directoryAsset . '/img/user2-160x160.jpg' ?>"
                     class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->nickname ? Yii::$app->user->identity->nickname : Yii::$app->user->identity->username ?></p>
                <a href="#"><i class="fa fa-circle text-info"></i> <?= Yii::$app->user->identity->username ?></a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
                <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?php

        use yii\bootstrap\Nay;
        use yiiplus\desktop\components\MenuHelper;

        $callback = function ($menu) {
            $return = [
                'label' => $menu['name'],
                'url' => [$menu['route']],
                'icon' => isset($menu['icon']) ? $menu['icon'] : '',
            ];
            $menu['children'] && $return['items'] = $menu['children'];
            return $return;
        };
        
        //这里我们对一开始写的菜单menu进行了优化
        echo yiiplus\desktop\widgets\Menu::widget([
            'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
            'items' => MenuHelper::getAssignedMenu(Yii::$app->user->id, null, $callback),
        ]);
        ?>

    </section>

</aside>
