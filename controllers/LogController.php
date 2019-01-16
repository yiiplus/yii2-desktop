<?php

namespace yiiplus\desktop\controllers;

use Yii;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yiiplus\desktop\models\Log as DesktopLog;

class LogController extends Controller
{
    public function actions()
    {
       return [
            'index' => [
                'class' => 'yiiplus\desktop\actions\Table',
                'modelClass' => 'yiiplus\desktop\models\Log',
                // 'title' => Yii::t('yiiplus/desktop', '日志管理'),
                // 'sort' => [
                //     'defaultOrder' => [
                //         'id' => SORT_DESC
                //     ]
                // ],
                // 'columns' => [
                //     ['checkbox' => true],
                //     'id',
                //     'route',
                //     [
                //         'field' => 'user_id',
                //         'value' => function($row, $pk, $index) {
                //             static $obj;
                //             if (is_null($obj[$row['user_id']])) {
                //                 $obj[$row['user_id']] = \yiiplus\desktop\models\User::findOne($row['user_id'])->username;
                //             }
                //             return $obj[$row['user_id']];
                //         },
                //         'searchable' => false,
                //     ],
                //     [
                //         'field' => 'ip',
                //         'value' => function($row, $pk, $index) {
                //         return long2ip($row['ip']);
                //         },
                //         'searchable' => function($value) {
                //         return ['ip' => ip2long($value)];
                //         },
                //         'sortable' => true,
                //     ],
                //     'created_at:datetime',
                //     [
                //         'field' => '_operate',
                //         'title' => Yii::t('yiiplus/desktop', '操作'),
                //         'value' => function($row, $pk, $index) {
                //             static $column;
                //             if (is_null($column)) {
                //                 $column = Yii::createObject([
                //                     'class' => 'yiiplus\desktop\table\ActionColumn',
                //                     'template' => '{view}',
                //                 ]);
                //             }
                //             return $column->renderDataCell($row, $pk, $index);
                //         },
                //     ],
                // ],
            ],
        ];
    }

    /**
     * Displays a single DesktopLog model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the DesktopLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return DesktopLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DesktopLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
