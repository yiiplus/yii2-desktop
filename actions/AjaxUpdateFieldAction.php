<?php
/**
 * AjaxUpdateFieldAction
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    liguangquan@163.com
 * @copyright 2006-2018 YiiPlus Ltd
 * @link      http://www.yiiplus.com
 */

namespace yiiplus\desktop\actions;

use Yii;
use yii\base\DynamicModel;
use yii\base\Exception;

/**
 * PositionBehavior
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    liguangquan@163.com
 * @copyright 2006-2018 YiiPlus Ltd
 * @link      http://www.yiiplus.com
 */
class AjaxUpdateFieldAction extends Action
{
    public $allowFields = [];

    public $findModel;

    public function run()
    {
        Yii::$app->response->format = 'json';
        $pk = Yii::$app->request->post('pk');
        $id = unserialize(base64_decode($pk));
        $post = Yii::$app->request->post();
        $formModel = DynamicModel::validateData(['id' => $id, 'name' => $post['name'], 'value' => $post['value']], [
            [['id'], 'required'],
            ['name', 'in', 'range' => $this->allowFields]
        ]);
        if ($formModel->hasErrors()) {
            throw new Exception(current($formModel->getFirstErrors()));
        }
        $model = $this->findModel($id);
        $model->updateAll([$post['name'] => $post['value']], ['id' => $id]);
        return ['status' => 1];
    }
}