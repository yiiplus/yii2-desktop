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
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\web\NotFoundHttpException;

/**
 * 继承\yii\base\Action
 * 增加findModel/返回action/构建返回action
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    liguangquan@163.com
 * @copyright 2006-2018 YiiPlus Ltd
 * @link      http://www.yiiplus.com
 */
class Action extends \yii\base\Action
{
    /**
     * 查询$Model
     */
    public $findModel;

    /**
     * action返回对象
     */
    public $returnAction;

    /**
     * 返回路径
     */
    public $returnUrl;


    /**
     * 查询Model 对象不存在抛出异常
     * 
     * @param 复合主键
     *
     * @return ActiveRecordInterface|Model the model found
     *
     * @throws NotFoundHttpException  if the model cannot be found
     * @throws InvalidConfigException on invalid configuration
     */
    public function findModel($id)
    {
        if ($this->findModel !== null) {
            return call_user_func($this->findModel, $id, $this);
        } elseif ($this->controller->hasMethod('findModel')) {
            return call_user_func([$this->controller, 'findModel'], $id, $this);
        } else {
            throw new InvalidConfigException('Either "' . get_class($this) . '::findModel" must be set or controller must declare method "findModel()".');
        }
    }

    /**
     * 检查所有控制器是否指定该id的操作
     *
     * @param string $id action ID.
     *
     * @return bool
     */
    public function actionExists($id)
    {
        $inlineActionMethodName = 'action' . Inflector::camelize($id);
        if (method_exists($this->controller, $inlineActionMethodName)) {
            return true;
        }
        if (array_key_exists($id, $this->controller->actions())) {
            return true;
        }
        return false;
    }

    /**
     * 设置返回的actionId
     *
     * @param string|null $actionId action ID, if not set current action will be used.
     */
    public function setReturnAction($actionId = null)
    {
        if ($actionId === null) {
            $actionId = $this->id;
        }
        if (strpos($actionId, '/') === false) {
            $actionId = $this->controller->getUniqueId() . '/' . $actionId;
        }
        $sessionKey = '__adminReturnAction';
        Yii::$app->getSession()->set($sessionKey, $actionId);
    }

    /**
     * 获取复合主键的action
     *
     * @param string $defaultActionId default action ID.
     *
     * @return string action ID.
     */
    public function getReturnAction($defaultActionId = 'index')
    {
        if ($this->returnAction !== null) {
            return $this->returnAction;
        }

        $sessionKey = '__adminReturnAction';
        $actionId = Yii::$app->getSession()->get($sessionKey, $defaultActionId);
        $actionId = trim($actionId, '/');
        if ($actionId === 'index') {
            return $actionId;
        }
        if (strpos($actionId, '/') !== false) {
            $controllerId = StringHelper::dirname($actionId);
            if ($controllerId !== $this->controller->getUniqueId()) {
                return 'index';
            }
            $actionId = StringHelper::basename($actionId);
        }
        if (!$this->actionExists($actionId)) {
            return 'index';
        }
        return $actionId;
    }

    /**
     * 创建返回路径 通过getReturnAction()
     *
     * @param string                           $defaultActionId default action ID.
     * @param ActiveRecordInterface|Model|null $model           model being processed by action.
     *
     * @return array|string URL
     */
    public function createReturnUrl($defaultActionId = 'index', $model = null)
    {
        if ($this->returnUrl !== null) {
            if (is_string($this->returnUrl)) {
                return $this->returnUrl;
            }
            if (!is_callable($this->returnUrl, true)) {
                return $this->returnUrl;
            }

            $args = func_get_args();
            array_shift($args);
            return call_user_func_array($this->returnUrl, $args);
        }

        $actionId = $this->getReturnAction($defaultActionId);
        $queryParams = Yii::$app->request->getQueryParams();
        unset($queryParams['id']);
        $url = array_merge(
            [$actionId],
            $queryParams
        );
        if (is_object($model) && in_array($actionId, ['view', 'update'], true)) {
            $url = array_merge(
                $url,
                ['id' => implode(',', array_values($model->getPrimaryKey(true)))]
            );
        }
        return $url;
    }

    /**
     * 设置一个Flash消息
     * 如果传递了纯字符串，它将被用作带有密钥“成功”的消息。
     * 可以将多个消息指定为数组，如果元素名不是整数，则将其用作键，
     * 否则，“成功”将被用作关键。
     * 如果空值通过，则不会设置闪存。
     * 特定的消息值可以是PHP回调，它应该返回实际消息。
     *
     * @param string|array|null $message flash message(s) to be set.
     * @param array             $params  extra params for the message parsing in format: key => value.
     */
    public function setFlash($message, $params = [])
    {
        if (empty($message)) {
            return;
        }

        $session = Yii::$app->session;

        foreach ((array)$message as $key => $value) {
            if (is_scalar($value)) {
                $value = preg_replace_callback("/{(\\w+)}/", function ($matches) use ($params) {
                    $paramName = $matches[1];
                    return isset($params[$paramName]) ? $params[$paramName] : $paramName;
                }, $value);
            } else {
                $value = call_user_func($value, $params);
            }

            if (is_int($key)) {
                $session->setFlash('success', $value);
            } else {
                $session->setFlash($key, $value);
            }
        }
    }
}
