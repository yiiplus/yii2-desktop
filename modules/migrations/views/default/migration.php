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

echo "<?php\n";
?>

use yii\db\Migration;

class <?= $className ?> extends Migration
{
    public function up()
    {
<?= $up ?>

    }

    public function down()
    {
<?php if (!empty($down)):?>
<?= $down ?>
<?php else:?>
    echo "<?= $className ?> cannot be reverted.\n";
<?php endif;?>

    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
