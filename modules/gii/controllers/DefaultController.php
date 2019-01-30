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

namespace yiiplus\desktop\modules\gii\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yiiplus\desktop\components\Helper;

/**
 * gii
 */
class DefaultController extends \yii\gii\controllers\DefaultController
{

    /**
     * 首页
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}