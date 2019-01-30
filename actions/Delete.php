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

namespace yiiplus\desktop\actions;

/**
 * 删除动作
 */
class Delete extends Action
{
    public function run($id)
    {
        $this->findModel($id)->delete();
        \Yii::$app->session->setFlash('success', Yii::t('yiiplus/desktop', '操作成功'));
        return \Yii::$app->controller->redirect(\Yii::$app->request->getReferrer());
    }
}