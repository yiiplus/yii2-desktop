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

namespace yiiplus\desktop\components;

use Yii;
use yiiplus\desktop\models\AuthItem;
use yiiplus\desktop\models\searchs\AuthItem as AuthItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\NotSupportedException;
use yii\filters\VerbFilter;
use yii\rbac\Item;
use yiiplus\desktop\models\Item as It;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 *
 * @property integer $type
 * @property array $labels
 *
 * @author gengxiankun <gengxiankun@126.com>
 * @since 2.0.0
 */
class ItemController extends Controller
{

    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * @return array the behavior configurations.
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'assign' => ['post'],
                    'remove' => ['post'],
                ],
            ],
        ];
    }

    /**
     * ITEM表格渲染
     *
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => 'yiiplus\desktop\actions\Table',
                'modelClass' => 'yiiplus\desktop\models\Item',
                'title' => Yii::t('yiiplus/desktop', Yii::$app->controller->id == 'role' ? '角色管理' : '权限管理'),
                'columns' => [
                    ['checkbox' => true],
                    'name',
                    [
                        'field' => 'role',
                        'format' => 'raw',
                        'value' => function ($row, $pk, $index) {
                            static $manager;
                            if (!isset($manager)) {
                                $manager = Configs::authManager();
                            }

                            $roles = Yii::$app->controller->id == It::TYPE_ROLE ? array_keys($manager->getChildRoles($row['name'])) : [];
                            $str = '';
                            foreach ($roles as $role) {
                                $str .= "<span  class='label label-success'>" . $role . "</span></br>";
                            }
                            return $str;
                        },
                        'visible' => Yii::$app->controller->id == It::TYPE_ROLE,
                    ],
                    [
                        'field' => 'permission',
                        'format' => 'raw',
                        'value' => function ($row, $pk, $index) {
                            static $manager;
                            if (!isset($manager)) {
                                $manager = Configs::authManager();
                            }

                            static $obj;
                            if (!isset($obj[$row['name']])) {
                                $obj[$row['name']] = array_keys($manager->getPermissionsByRole($row['name']));
                            }
                            $str = '';
                            foreach ($obj[$row['name']] as $item) {
                                if ($item['0'] != '/') {
                                    $str .= "<span  class='label label-success'>" . $item . "</span></br>";
                                }
                            }
                            return $str;
                        },
                    ],
                    [
                        'field' => 'route',
                        'format' => 'raw',
                        'value' => function ($row, $pk, $index) {
                            static $manager;
                            if (!isset($manager)) {
                                $manager = Configs::authManager();
                            }

                            static $obj;
                            if (!isset($obj[$row['name']])) {
                                $obj[$row['name']] = array_keys($manager->getPermissionsByRole($row['name']));
                            }
                            $str = '';
                            foreach ($obj[$row['name']] as $item) {
                                if ($item['0'] == '/') {
                                    $str .= "<span  class='label label-success'>" . $item . "</span></br>";
                                }
                            }
                            return $str;
                        },
                    ],
                    [
                        'field' => 'created_at',
                        'value' => function ($row, $pk, $index) {
                            return date('Y-m-d H:i:s', $row['created_at']);
                        },
                    ],
                    [
                        'field' => 'updated_at',
                        'value' => function ($row, $pk, $index) {
                            return date('Y-m-d H:i:s', $row['updated_at']);
                        },
                    ],
                    [
                        'field' => '_operate',
                        'title' => Yii::t('yiiplus/desktop', '操作'),
                        'value' => function ($row, $pk, $index) {
                            static $column;
                            if (is_null($column)) {
                                $column = Yii::createObject([
                                    'class' => 'yiiplus\desktop\widgets\table\ActionColumn',
                                    'template' => '{view} {update} {delete}',
                                ]);
                            }
                            return $column->renderDataCell($row, $pk, $index);
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays a single AuthItem model.
     *
     * @param  string $id ID
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthItem(null);
        $model->type = $this->type;
        if ($model->load(Yii::$app->getRequest()->post())) {
            if ($model->save()) {
                if ($model->assignedItem) {
                    $model->addChildren($model->assignedItem);
                }
                return $this->redirect(['view', 'id' => $model->name]);
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param  string $id ID
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->getRequest()->post())) {
            if ($model->save()) {
                if ($model->assignedItem) {
                    $model->addChildren($model->assignedItem);
                }

                if ($model->availableItem) {
                    $model->removeChildren($model->availableItem);
                }
                return $this->redirect(['view', 'id' => $model->name]);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param  string $id ID
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        Configs::authManager()->remove($model->item);
        Helper::invalidate();

        return $this->redirect(['index']);
    }

    /**
     * Assign items
     *
     * @param string $id ID
     *
     * @return array
     */
    public function actionAssign($id)
    {
        $items = Yii::$app->getRequest()->post('items', []);
        $model = $this->findModel($id);
        $success = $model->addChildren($items);
        Yii::$app->getResponse()->format = 'json';

        return array_merge($model->getItems(), ['success' => $success]);
    }

    /**
     * Assign or remove items
     *
     * @param string $id ID
     *
     * @return array
     */
    public function actionRemove($id)
    {
        $items = Yii::$app->getRequest()->post('items', []);
        $model = $this->findModel($id);
        $success = $model->removeChildren($items);
        Yii::$app->getResponse()->format = 'json';

        return array_merge($model->getItems(), ['success' => $success]);
    }


    /**
     * 路径
     *
     * @return string
     */
    public function getViewPath()
    {
        return $this->module->getViewPath() . DIRECTORY_SEPARATOR . 'item';
    }

    /**
     * Label use in view
     *
     * @return null
     * @throws NotSupportedException
     */
    public function labels()
    {
        throw new NotSupportedException(get_class($this) . Yii::t('yiiplus/desktop', '不支持该标签'));
    }

    /**
     * Type of Auth Item.
     *
     * @return integer
     */
    public function getType()
    {

    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id ID
     *
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $auth = Configs::authManager();
        $item = $this->type === Item::TYPE_ROLE ? $auth->getRole($id) : $auth->getPermission($id);
        if ($item) {
            return new AuthItem($item);
        } else {
            throw new NotFoundHttpException(Yii::t('yiiplus/desktop', '请求的页面不存在'));
        }
    }
}
