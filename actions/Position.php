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
     */
    public $positionParam = 'at';

    /**
     * Updates existing record specified by id.
     *
     * @param mixed $id id of the model to be deleted.
     *
     * @return mixed response.
     *
     * @throws BadRequestHttpException       on invalid request.
     * @throws MethodNotAllowedHttpException on invalid request.
     */
    public function run($id)
    {
        if (!Yii::$app->request->isPost) {
            throw new MethodNotAllowedHttpException('Method Not Allowed. This url can only handle post');
        }
        //获取菜单移动方向
        $position = Yii::$app->request->getQueryParam($this->positionParam, null);
        if (empty($position)) {
            throw new BadRequestHttpException(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $this->positionParam]));
        }

        $model = $this->findModel($id);
        
        $this->positionModel($model, $position);

        return $this->respondSuccess($model);
    }

    /**
     * 定位Model
     *
     * @param object $model    model
     * @param string $position 移动方向 first/last/prev/next
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
                    throw new BadRequestHttpException(Yii::t('yii', '{attribute} is invalid.', ['attribute' => $this->positionParam]));
                }
        }
    }

    /**
     * 成功返回
     *
     * @param object $model Model
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
     * @param string $defaultActionId 列表名称
     * @param object $model Model
     *
     * @return url
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
