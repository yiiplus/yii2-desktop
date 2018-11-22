<?php

use yiiplus\desktop\components\Configs;

/**
 * Migration table of {{%yp_desktop_log}}
 */
class m140602_111327_create_log_table extends \yii\db\Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $logTable = Configs::instance()->logTable;
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable($logTable, [
            'id' => Schema::TYPE_PK,
            'route' => Schema::TYPE_STRING . "(255) NOT NULL DEFAULT ''",
            'description' => Schema::TYPE_TEXT . " NULL",
            'created_at' => Schema::TYPE_INTEGER . "(10) NOT NULL",
            'user_id' => Schema::TYPE_INTEGER . "(10) NOT NULL DEFAULT '0'",
            'ip' => Schema::TYPE_BIGINT . "(20) NOT NULL DEFAULT '0'",
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(Configs::instance()->logTable);
    }
}
