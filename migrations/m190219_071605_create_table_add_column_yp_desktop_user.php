<?php

use yii\db\Migration;

class m190219_071605_create_table_add_column_yp_desktop_user extends Migration
{
    public function up()
    {
        $this->addColumn('admin_user', 'nickname', $this->string(32)->notNUll()->comment('用户昵称'));
        $this->addColumn('admin_user', 'avatar', $this->string()->notNUll()->comment('头像'));
        $this->addColumn('admin_user', 'last_login_at', $this->integer()->notNUll()->comment('最后登录时间'));
    }

    public function down()
    {
        $this->dropColumn('admin_user', 'nickname');
        $this->dropColumn('admin_user', 'avatar');
        $this->dropColumn('admin_user', 'last_login_at');
    }
}
