<?php

namespace yiiplus\desktop\actions;

class Delete extends Action
{
    public function run($id)
    {
        $this->findModel($id)->delete();
        \Yii::$app->session->setFlash('success', '操作成功');
        return \Yii::$app->controller->redirect(\Yii::$app->request->getReferrer());
    }
}