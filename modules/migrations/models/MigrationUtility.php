<?php
namespace yiiplus\desktop\modules\migrations\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;

class MigrationUtility extends Model
{

    public $migrationName = '';

    public $migrationPath = '@console/migrations';

    public $tableSchemas;

    public $tableDatas;

    public $dataBases;

    /**
     * @var string
     */
    public $tableOption = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    /**
     * @return array
     */
    function rules()
    {
        return [
            [["migrationName","migrationPath","tableSchemas","tableDatas","tableOption", "dataBases"],'safe']
        ];
    }

    public static function getTableNames()
    {
        $tables = \Yii::$app->db->getSchema()->getTableNames('', TRUE);
        $tables = array_combine($tables,$tables);
        // 移除migration表
        ArrayHelper::remove($tables, \Yii::$app->db->getSchema()->getRawTableName('{{%migration}}'));
        return $tables;
    }

    public static function getDatabases()
    {
        $dataBases = \Yii::$app->db->createCommand('show databases')->queryAll();
        return array_column($dataBases, 'Database');
    }

}
