<?php
/**
 * yiiplus\desktop
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
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Response;

/**
 * 菜单移动
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    liguangquan@163.com
 * @copyright 2006-2018 YiiPlus Ltd
 * @link      http://www.yiiplus.com
 */
class Position extends Action
{
    /**
     * 获取参数键值
     *
     * @var string
     */
    public $positionParam = 'at';

    /**
     * 更新ID指定的现有记录
     *
     * @param mixed $id ModelId
     *
     * @return mixed response.
     *
     * @throws BadRequestHttpException       on invalid request.
     * @throws MethodNotAllowedHttpException on invalid request.
     */
    public function run($id)
    {
        if (!Yii::$app->request->isPost) {
            throw new MethodNotAllowedHttpException(Yii::t('yiiplus/desktop', '该方法只接受post传参'));
        }
        // 获取菜单移动方向
        $position = Yii::$app->request->getQueryParam($this->positionParam, null);
        if (empty($position)) {
            throw new BadRequestHttpException(Yii::t('yiiplus/desktop', '{attribute}不能为空', ['attribute' => $this->positionParam]));
        }

        $model = $this->findModel($id);
        
        $this->positionModel($model, $position);

        return $this->respondSuccess($model);
    }

    /**
     * 定位Model
     *
     * @param object $model    model
     * @param string $position 移动方向
     *
     * @return yiiplus\desktop\behaviors\PositionBehavior
     */
    protected function positionModel($model, $position)
    {
        switch (strtolower($position)) {
            case 'up':
            case 'prev':
                $model->movePrev();
                break;
            case 'down':
            case 'next':
                $model->moveNext();
                break;
            case 'top':
            case 'first':
                $model->moveFirst();
                break;
            case 'bottom':
            case 'last':
                $model->moveLast();
                break;
            default:
                if (is_numeric($position)) {
                    $model->moveToPosition($position);
                } else {
                    throw new BadRequestHttpException(Yii::t('yiiplus/desktop', '{attribute}无效.', ['attribute' => $this->positionParam]));
                }
        }
    }

    /**
     * 成功返回
     *
     * @param object $model Model对象
     *
     * @return redirect
     */
    protected function respondSuccess($model)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'success' => true
            ];
        }

        return $this->controller->redirect($this->createReturnUrl('view', $model));
    }

    /**
     * 构成返回路径
     *
     * @param string $defaultActionId 默认actionId
     * @param object $model           Model
     *
     * @return string
     */
    public function createReturnUrl($defaultActionId = 'index', $model = null)
    {
        if ($this->returnUrl !== null) {
            return parent::createReturnUrl($defaultActionId, $model);
        }

        $url = parent::createReturnUrl($defaultActionId, $model);
        unset($url[$this->positionParam]);
        return $url;
    }
}
