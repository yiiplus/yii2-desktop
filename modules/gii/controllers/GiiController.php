<?php
namespace yiiplus\desktop\modules\gii\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yiiplus\desktop\components\Helper;

class GiiController extends \yii\gii\controllers\DefaultController
{

    /**
     * gii首页面板
     * 
     * @return string
     */
    public function actionIndex()
    {
        //引入布局配置文件
        $this->layout = '@base/vendor/yiiplus/yii2-desktop/views/layouts/main.php';
        return $this->render('index');
    }
}