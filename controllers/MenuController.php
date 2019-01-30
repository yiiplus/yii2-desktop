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
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

use yiiplus\desktop\models\Menu;
use yiiplus\desktop\models\searchs\Menu as MenuSearch;
use yiiplus\desktop\components\Helper;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends Controller
{
    /**
     * 菜单移动处理actions
     *
     * @return array
     */
    public function actions()
    {
        return [
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
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

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
    public function actionCreate($id = null)
    {
        $model = new Menu;
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            Helper::invalidate();
            return $this->redirect(['index']);
        } else {
            $model->parent = $id ? : null;
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
            $model->save();
            Helper::invalidate();
            return $this->redirect(['index']);
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
            throw new NotFoundHttpException(Yii::t('yiiplus/desktop', '请求的页面不存在'));
        }
    }
}
