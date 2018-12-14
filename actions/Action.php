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
     * session键值
     *
     * @var string
     */
    const SESSION_KEY = '__adminReturnAction';

    /**
     * 查询$Model
     *
     * @var object
     */
    public $findModel;

    /**
     * action返回对象
     *
     * @var object
     */
    public $returnAction;

    /**
     * 返回路径
     *
     * @var string
     */
    public $returnUrl;

    /**
     * 查询Model 对象不存在抛出异常
     * 
     * @param 复合主键
     *
     * @return ActiveRecordInterface|数据不存在
     *
     * @throws InvalidConfigException异常
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
     * 检查是否存在该id的控制器操作
     *
     * @param string $id actionID
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
     * 设置复合主键的action
     *
     * @param string|null $actionId actionID
     */
    public function setReturnAction($actionId = null)
    {
        if ($actionId === null) {
            $actionId = $this->id;
        }
        if (strpos($actionId, '/') === false) {
            $actionId = $this->controller->getUniqueId() . '/' . $actionId;
        }
        Yii::$app->getSession()->set(self::SESSION_KEY, $actionId);
    }

    /**
     * 获取复合主键的action
     *
     * @param string $defaultActionId 默认actionID
     *
     * @return string actionID.
     */
    public function getReturnAction($defaultActionId = 'index')
    {
        if ($this->returnAction !== null) {
            return $this->returnAction;
        }

        $actionId = Yii::$app->getSession()->get(self::SESSION_KEY, $defaultActionId);
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
     * @param string                           $defaultActionId 默认actionID
     * @param ActiveRecordInterface|Model|null $model           object
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
     * @param string|array|null $message 提示信息必须填写
     * @param array             $params  可选
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
