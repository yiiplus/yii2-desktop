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
 * Migration table of menu_table
 */
class m140602_111327_create_menu_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('admin_menu', 'icon', $this->string(32)->notNUll()->comment('图标'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('admin_menu', 'icon');
    }
}
