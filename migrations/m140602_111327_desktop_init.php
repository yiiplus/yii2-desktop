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

use yii\db\Migration;
use yii\db\Schema;
use yiiplus\desktop\components\Configs;

/**
 * Migration table of log_table
 */
class m140602_111327_desktop_init extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        // create log table
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

        $menuTable = Configs::instance()->menuTable;
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        // create menu table
        $this->createTable($menuTable, [
            'id' => $this->primaryKey(),
            'name' => $this->string(128)->notNull(),
            'parent' => $this->integer(),
            'route' => $this->string(),
            'order' => $this->integer(),
            'icon' => $this->string(20),
            'data' => $this->binary(),
            "FOREIGN KEY ([[parent]]) REFERENCES {$menuTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
        ], $tableOptions);

        $userTable = Configs::instance()->userTable;
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        // create user table
        $this->createTable($userTable, [
            'id' => $this->primaryKey(),
            'username' => $this->string(32)->notNull(),
            'nickname' => $this->string(32)->notNUll()->comment('用户昵称'),
            'avatar' => $this->string()->notNUll()->comment('头像'),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string(),
            'email' => $this->string()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'last_login_at' => $this->integer()->notNUll()->comment('最后登录时间'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            ], $tableOptions);

        // insert user table data
        $this->batchInsert('{{%yp_desktop_user}}', 
            ['id', 'username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'status', 'created_at', 'updated_at'],
            [
                [1, 'webmaster', 'UKpH8-7IxZXSLzcOU7ooSaUHwPYq9BLp', '$2y$13$HylJs5OBsNBcnI4DCVQuqeGeIvr1/JqXrkOnhRtDKJsRoGGAfRu5e', null, 'webmaster@example.com', 10, 1504106471, 1504106471],
            ]
        );

        // insert menu table data
        $this->batchInsert('{{%yp_desktop_menu}}', 
            ['id', 'name', 'parent', 'route', 'icon', 'order', 'data'],
            [
                [1, '仪表盘', null, '/', 'fa-dashboard', 1, ''],
                [2, '系统设置', null, null, 'fa-cog', 2, ''],
                [3, '菜单列表', 2, '/admin/menu/index', 'fa-list', 3, ''],
                [4, '操作日志', 2, '/admin/log/index', 'fa-file', 5, ''],
                [5, '用户管理', 2, '/admin/user/index', 'fa-user', 2, ''],
                [6, '角色列表', 2, '/admin/role/index', 'fa-users', 4, ''],
                [7, '访问控制', 2, null, 'fa-laptop', 1, ''],
                [8, '路由列表', 7, '/admin/route/index', 'fa-circle-o', 3, ''],
                [9, '规则列表', 7, '/admin/rule/index', 'fa-circle-o', 4, ''],
                [10, '权限列表', 7, '/admin/permission/index', 'fa-circle-o', 1, ''],
            ]
        );

        if(YII_ENV_DEV) {
            $this->batchInsert('{{%yp_desktop_menu}}', 
                ['name', 'parent', 'route', 'icon', 'order', 'data'],
                [
                    ['GII', null, '/admin/gii/default/index', 'fa-google', 3, ''],
                    ['迁移', null, '/admin/migrations/default/index', 'fa-truck', 4, ''],
                    ['调试', null, '/debug/default/index', 'fa-bug', 5, ''],
                ]
            );
        }

        ## insert rbac table superman data
        $this->batchInsert('{{%auth_item}}', 
            ['name', 'type', 'description', 'rule_name', 'data', 'created_at', 'updated_at'],
            [
                ['/*', 2, null, null, null, 1487816675, 1487816675],
                ['超级管理员', 1, null, null, null, 1487817275, 1487817275],
            ]
        );

        $this->batchInsert('{{%auth_item_child}}', 
            ['parent', 'child'],
            [
                ['超级管理员', '/*'],
            ]
        );

        $this->batchInsert('{{%auth_assignment}}', 
            ['item_name', 'user_id', 'created_at'],
            [
                ['超级管理员', '1', 1487817340],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(Configs::instance()->logTable);

        $this->dropTable(Configs::instance()->menuTable);

        $this->dropTable(Configs::instance()->userTable);
    }
}
