<?php
/**
 * 慧诊
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    zhouyang <zhouyang@himoca.com>
 * @copyright 2017-2019 北京慧诊科技有限公司
 * @license   https://www.huizhen.com/licence.txt Licence
 * @link      http://www.huizhen.com
 */

namespace yiiplus\desktop\modules\gii\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yiiplus\desktop\components\Helper;

class GiiController extends \yii\gii\controllers\DefaultController
{

    /**
     * 首页
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