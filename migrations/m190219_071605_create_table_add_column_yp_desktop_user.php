<?php

use yii\db\Migration;

class m190219_071605_create_table_add_column_yp_desktop_user extends Migration
{
    public function up()
    {
        $this->addColumn('yp_desktop_user', 'nickname', $this->string(32)->notNUll()->comment('用户昵称'));
        $this->addColumn('yp_desktop_user', 'avatar', $this->string()->notNUll()->comment('头像'));
        $this->addColumn('yp_desktop_user', 'last_login_at', $this->integer()->notNUll()->comment('最后登录时间'));
    }

    public function down()
    {
        $this->dropColumn('yp_desktop_user', 'nickname');
        $this->dropColumn('yp_desktop_user', 'avatar');
        $this->dropColumn('yp_desktop_user', 'last_login_at');
    }
}
