<?php

use yii\db\Migration;

class m190219_071605_create_table_add_column_yp_desktop_user extends Migration
{
    public function up()
    {
        $tableName = isset(Yii::$app->params['admin_user_table']) ? Yii::$app->params['admin_user_table'] : 'yp_desktop_user';
        $this->addColumn($tableName, 'nickname', $this->string(32)->notNUll()->comment('用户昵称'));
        $this->addColumn($tableName, 'avatar', $this->string()->notNUll()->comment('头像'));
        $this->addColumn($tableName, 'last_login_at', $this->integer()->notNUll()->comment('最后登录时间'));
    }

    public function down()
    {
        $tableName = isset(Yii::$app->params['admin_user_table']) ? Yii::$app->params['admin_user_table'] : 'yp_desktop_user';
        $this->dropColumn($tableName, 'nickname');
        $this->dropColumn($tableName, 'avatar');
        $this->dropColumn($tableName, 'last_login_at');
    }
}
