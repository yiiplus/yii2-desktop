<?php

namespace yiiplus\desktop\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

use yiiplus\desktop\models\Menu;
use yiiplus\desktop\models\searchs\Menu as MenuSearch;
use yiiplus\desktop\components\Helper;

class MenuController extends Controller
{
    /**
     * behaviors
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * actions
     *
     * @return array
     */
    public function actions()
    {
        return [
            'ajax-update-field' => [
                'class' => 'yiiplus\\desktop\\actions\\AjaxUpdateFieldAction',
                'allowFields' => ['order'],
                'findModel' => [$this, 'findModel']
            ],
            'position' => [
                'class' => 'yiiplus\\desktop\\actions\\Position',
                'returnUrl' => Url::current()
            ]
        ];
    }

    /**
     * 列表
     *
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $searchModel = new MenuSearch;
        $query = Menu::find()->orderBy('order asc');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
        ]);
    }

    /**
     * 详情
     *
     * @param int $id 列表id
     *
     * @return string|\yii\web\Response
     */
    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    /**
     * 新增
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Menu;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->parent_name) {
                $parentModel = Menu::find()->where(['name' => $model->parent_name])->select(['id'])->one();
                $model->parent = $parentModel->id;
            }
            $model->save();
            Helper::invalidate();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', ['model' => $model]);
        }
    }

    /**
     * 修改
     *
     * @param int $id 列表id
     *
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->menuParent) {
            $model->parent_name = $model->menuParent->name;
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->parent_name) {
                $parentModel = Menu::find()->where(['name' => $model->parent_name])->select(['id'])->one();
                $model->parent = $parentModel->id;
            }
            $model->save();
            Helper::invalidate();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }

    /**
     * 删除
     *
     * @param int $id 列表id
     *
     * @return string|\yii\web\Response
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Helper::invalidate();

        return $this->redirect(['index']);
    }

    /**
     * 查询findModel
     *
     * @param int $id 列表id
     *
     * @return string|\yii\web\Response
     */
    public function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
