<?php

namespace yiiplus\desktop\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yiiplus\desktop\models\Menu;
use yiiplus\desktop\models\searchs\Menu as MenuSearch;
use yiiplus\desktop\components\Helper;

class MenuController extends Controller
{
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

    public function actionIndex()
    {
        $searchModel = new MenuSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    public function actionCreate()
    {
        $model = new Menu;

        if ($model->load(Yii::$app->request->post())) {
            $parentModel = Menu::find()->where(['name' => $model->parent_name])->select(['id'])->one();
            $model->parent = $parentModel->id;
            $model->save();
            Helper::invalidate();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', ['model' => $model]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->menuParent) {
            $model->parent_name = $model->menuParent->name;
        }
        if ($model->load(Yii::$app->request->post())) {
            $parentModel = Menu::find()->where(['name' => $model->parent_name])->select(['id'])->one();
            $model->parent = $parentModel->id;
            $model->save();
            Helper::invalidate();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Helper::invalidate();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
