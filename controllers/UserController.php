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

namespace yiiplus\desktop\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\base\InvalidParamException;
use yii\base\UserException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\mail\BaseMailer;
use yiiplus\desktop\models\form\Login;
use yiiplus\desktop\models\User;
use yiiplus\desktop\models\searchs\User as UserSearch;
use yiiplus\desktop\components\Helper;
use yiiplus\desktop\models\form\Signup;
use yiiplus\desktop\components\Configs;
use yiiplus\desktop\models\Assignment;
use yiiplus\desktop\models\AuthItem;
use yii\web\UploadedFile;
use yii\rbac\Item;
use yii\helpers\Html;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    private $_oldMailPath;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'activate' => ['post'],
                    'disable' => ['post'],
                    'delete' => ['post'],
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (Yii::$app->has('mailer') && ($mailer = Yii::$app->getMailer()) instanceof BaseMailer) {
                /* @var $mailer BaseMailer */
                $this->_oldMailPath = $mailer->getViewPath();
                $mailer->setViewPath('@yiiplus/desktop/mail');
            }
            return true;
        }
        return false;
    }

    public function afterAction($action, $result)
    {
        if ($this->_oldMailPath !== null) {
            Yii::$app->getMailer()->setViewPath($this->_oldMailPath);
        }
        return parent::afterAction($action, $result);
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => 'yiiplus\desktop\actions\Table',
                'modelClass' => 'yiiplus\desktop\models\User',
                'title' => Yii::t('yiiplus/desktop', '用户管理'),
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_DESC
                    ]
                ],
                'columns' => [
                    ['checkbox' => true],
                    'id',
                    'username',
                    'nickname',
                    [
                        'field' => 'avatar',
                        'value' => function ($row, $pk, $index) {
                            return Html::img(
                                Yii::$app->request->hostInfo . '/' . $row['avatar'],
                                [
                                    'class' => 'img',
                                    'width' => 120,
                                    'height' => 120,
                                ]
                            );
                        },
                    ],
                    [
                        'field' => 'role',
                        'format' => ['raw'],
                        'value' => function ($row, $pk, $index) {
                            $roles = \yiiplus\desktop\models\AuthItem::getItemByUser($row['id'])['role'];
                            $str = '';
                            foreach ($roles as $role) {
                                $str .= "<span  class='label label-success'>" . $role . "</span></br>";
                            }
                            return $str;
                        },
                    ],
                    [
                        'field' => 'permission',
                        'format' => ['raw'],
                        'value' => function ($row, $pk, $index) {
                            $roles = \yiiplus\desktop\models\AuthItem::getItemByUser($row['id'])['permission'];
                            $str = '';
                            foreach ($roles as $role) {
                                $str .= "<span  class='label label-success'>" . $role . "</span></br>";
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
                        'field' => 'last_login_at',
                        'value' => function ($row, $pk, $index) {
                            return $row['last_login_at'] ? date('Y-m-d H:i:s', $row['last_login_at']) : '';
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

    public function actionLogin()
    {
        if (!Yii::$app->getUser()->isGuest) {
            return $this->goHome();
        }

        $model = new Login();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', ['model' => $model]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->getUser()->logout();

        return $this->goHome();
    }

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    public function actionCreate()
    {
        $model = new User();
        if ($model->load(Yii::$app->getRequest()->post())) {
            //是否上传图片
            $avatar = UploadedFile::getInstance($model, 'avatar');
            if ($avatar) {
                $folder = date('Ymd') . '/';
                if (!is_dir($folder)) {
                    mkdir($folder, 0777, true);
                }
                $name = md5($model->nickname) . '.' . $avatar->getExtension();
                $path = $folder . $name;
                $avatar->saveAs($path);
                $model->avatar = $path;
            }
            $model->password_hash = Yii::$app->security->generatePasswordHash($model->password);
            $model->auth_key = Yii::$app->security->generateRandomString();
            $model->last_login_at = time();
            $ownRole = $model->role ? $model->role : [];
            $ownPermission = $model->permission ? $model->permission : [];

            if ($model->save()) {
                $assignment = new Assignment($model->id);
                $new = array_merge($ownRole, $ownPermission);
                if ($new) {
                    $assignment->assign($new);
                }
                return $this->goHome();
            }
        }
        $allItems = AuthItem::getAllItems();
        $ownItem = AuthItem::getItemByUser($model->id);
        $model->role = $ownItem['role'];
        $model->permission = $ownItem['permission'];
        return $this->render('create', ['model' => $model, 'roles' => $allItems['roles'], 'permissions' => $allItems['permissions']]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->type = Item::TYPE_ROLE;
        $assignment = new Assignment($model->id);
        if ($model->load(Yii::$app->request->post())) {
            //是否上传图片
            $avatar = UploadedFile::getInstance($model, 'avatar');
            if ($avatar) {
                $folder = date('Ymd') . '/';
                if (!is_dir($folder)) {
                    mkdir($folder, 0777, true);
                }
                $name = md5($model->nickname) . '.' . $avatar->getExtension();
                $path = $folder . $name;
                $avatar->saveAs($path);
                $model->avatar = $path;
            }
            //密码和数据库不一致则为修改密码
            if ($model->password != $model->password_hash) {
                $model->password_hash = $model->setPassword($model->password);
            }

            //新的角色权限列表
            $old = array_keys($assignment->getItems()['assigned']);
            if ($old) {
                $assignment->revoke($old);
            }

            $ownRole = $model->role ? $model->role : [];
            $ownPermission = $model->permission ? $model->permission : [];
            $new = array_merge($ownRole, $ownPermission);
            if ($new) {
                $assignment->assign($new);
            }

            if ($model->save()) {
                Helper::invalidate();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->password = $model->password_hash;
            $model->repassword = $model->password_hash;
            if ($model->avatar) {
                $model->avatar = Yii::$app->request->hostInfo . '/' . $model->avatar;
            }

            $allItems = AuthItem::getAllItems();
            $ownItem = AuthItem::getItemByUser($model->id);
            $model->role = $ownItem['role'];
            $model->permission = $ownItem['permission'];
            return $this->render('update', ['model' => $model, 'assignment' => $assignment, 'roles' => $allItems['roles'], 'permissions' => $allItems['permissions']]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * 重置密码
     * @param integer $id
     * @return type
     * @throws UserException
     * @throws NotFoundHttpException
     */
    public function actionResetPassword()
    {

    }

    /**
     * 激活用户
     * @param integer $id
     * @return type
     * @throws UserException
     * @throws NotFoundHttpException
     */
    public function actionActivate($id)
    {
        /* @var $user User */
        $user = $this->findModel($id);
        $user->status = User::STATUS_ACTIVE;
        if ($user->save()) {
            return $this->redirect(['index']);
        } else {
            $errors = $user->firstErrors;
            throw new UserException(reset($errors));
        }
    }

    /**
     * 禁用用户
     * @param integer $id
     * @return type
     * @throws UserException
     * @throws NotFoundHttpException
     */
    public function actionInactive($id)
    {
        /* @var $user User */
        $user = $this->findModel($id);
        $user->status = User::STATUS_INACTIVE;
        if ($user->save()) {
            return $this->redirect(['index']);
        } else {
            $errors = $user->firstErrors;
            throw new UserException(reset($errors));
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('yiiplus/desktop', '请求的页面不存在'));
        }
    }
}
