<?php

namespace yiiplus\desktop\modules\migrations\controllers;

use Yii;
use yii\base\Object;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yiiplus\desktop\modules\migrations\models\MigrationUtility;
use yiiplus\desktop\modules\migrations\models\AppUtility;
use yiiplus\desktop\modules\migrations\models\Database;

class MigrationController extends Controller
{
    /**
     * 数据迁移
     *
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionIndex()
    {
        set_time_limit(0);
        $model = new MigrationUtility();
        $upStr = new OutputString();
        $downStr = new OutputString();

        if ($model->load(\Yii::$app->getRequest()->post())) {
            if (empty($model->tableSchemas) && empty($model->tableDatas)) {
                Yii::$app->getSession()->setFlash('error', Yii::t('yiiplus/desktop', '请选择迁移表结构或数据'));
                return $this->redirect('/migrations/migration/index');
            }

            if (empty($model->migrationPath) || empty($model->migrationName)) {
                Yii::$app->getSession()->setFlash('error', Yii::t('yiiplus/desktop', '迁移名称或路径为空'));
                return $this->redirect('/migrations/migration/index');
            }

            if (empty($model->database)) {
                Yii::$app->getSession()->setFlash('error', Yii::t('yiiplus/desktop', '未选择数据库'));
                return $this->redirect('/migrations/migration/index');
            }
            //获取数据库连接
            $connect = MigrationUtility::getConnect($model->database);

            if (!empty($model->tableSchemas)) {
                //获取表结构语句
                list ($up, $down) = $this->generalTableSchemas($model->tableSchemas, $model->tableOption, $connect);
                $upStr->outputStringArray = array_merge($upStr->outputStringArray, $up->outputStringArray);
                $downStr->outputStringArray = array_merge($downStr->outputStringArray, $down->outputStringArray);
            }

            if (!empty($model->tableDatas)) {
                //获取表数据
                list ($up, $down) = $this->generalTableDatas($model->tableDatas, $connect);
                $upStr->outputStringArray = array_merge($upStr->outputStringArray, $up->outputStringArray);
                $downStr->outputStringArray = array_merge($downStr->outputStringArray, $down->outputStringArray);
            }

            //获取路径别名
            $path = Yii::getAlias($model->migrationPath);
            if (!is_dir($path)) {
                //路径如果不存在创建文件夹
                FileHelper::createDirectory($path);
            }

            $name = 'm' . gmdate('ymd_His') . '_' . $model->migrationName;
            $file = $path . DIRECTORY_SEPARATOR . $name . '.php';

            $content = $this->renderFile(dirname(__DIR__) . '/views/migration/migration.php', [
                'className' => $name,
                'up' => $upStr->output(),
                'down' => $downStr->output()
            ]);
            file_put_contents($file, $content);
            Yii::$app->session->setFlash("success", Yii::t('yiiplus/desktop', "迁移成功，保存在") . $file);
            return $this->redirect('/migrations/migration/index');
        }

        $database = MigrationUtility::getNowDatabase();
        if ($model->load(\Yii::$app->getRequest()->get())) {
            $database = $model->database;
        }

        return $this->render('index', [
            'model' => $model,
            'database' => $database
        ]);
    }

    /**
     * 去掉表前缀
     *
     * @param string   $name    表名
     * @param resource $connect 数据库连接句柄
     *
     * @return mixed
     */
    public function getTableName($name, $connect)
    {
        $prefix = $connect->tablePrefix;
        return str_replace($prefix, '', $name);
    }

    /**
     * 获取创建表删除表语句
     *
     * @param array    $tables      迁移表数组
     * @param string   $tableOption 表属性语句
     * @param resource $connect     数据库连接句柄
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public function generalTableSchemas($tables, $tableOption, $connect)
    {
        $initialTabLevel = 2;
        $upStr = new OutputString([
            'tabLevel' => $initialTabLevel
        ]);

        $upStr->addStr('$this->execute(\'SET foreign_key_checks = 0\');');
        $upStr->addStr(' ');
        $tableNameArr = [];
        foreach ($tables as $table) {
            $upStr->tabLevel = $initialTabLevel;

            $tablePrepared = $this->getTableName($table, $connect);
            $tableNameArr[] = "'" . $tablePrepared . "'";
            // 添加表结构
            $upStr->addStr('$this->createTable(\'{{%' . $tablePrepared . '}}\', [');
            $upStr->tabLevel ++;
            $tableSchema = $connect->getTableSchema($table, $connect);
            
            foreach ($tableSchema->columns as $column) {
                $appUtility = new AppUtility($column);
                $upStr->addStr($appUtility->string . "',");
            }
            if (!empty($tableSchema->primaryKey)) {
                $upStr->addStr("'PRIMARY KEY (`" . implode("`,`", $tableSchema->primaryKey) . "`)'");
            }

            $upStr->tabLevel--;
            $upStr->addStr('], "' . $tableOption . '");');


            // 添加索引
            $tableIndexes = $connect->createCommand('SHOW INDEX FROM `' . $table . '`')->queryAll();
            $indexs = [];
            foreach ($tableIndexes as $item) {
                if ($item['Key_name'] == 'PRIMARY') {
                    continue;
                }
                if (! isset($indexs[$item["Key_name"]])) {
                    $indexs[$item['Key_name']] = [];
                    $indexs[$item['Key_name']]['unique'] = ($item['Non_unique']) ? 0 : 1;
                }
                $indexs[$item['Key_name']]['columns'][] = $item['Column_name'];
            }

            if (!empty($indexs)) {
                $upStr->addStr(' ');
            }

            foreach ($indexs as $index => $item) {
                $str = '$this->createIndex(\'' . $index . '\',\'{{%' . $tablePrepared . '}}\',\'' . implode(', ', $item['columns']) . '\',' . $item['unique'] . ');';
                $upStr->addStr($str);
            }

            $upStr->addStr(' ');
        }
        $tableNameStr = '(' . implode(',', $tableNameArr) . ')';
        //添加外键
        $sql = "SELECT tb1.CONSTRAINT_NAME, tb1.TABLE_NAME, tb1.COLUMN_NAME,
            tb1.REFERENCED_TABLE_NAME, tb1.REFERENCED_COLUMN_NAME, tb2.MATCH_OPTION,
        
            tb2.UPDATE_RULE, tb2.DELETE_RULE
        
            FROM information_schema.KEY_COLUMN_USAGE AS tb1
            INNER JOIN information_schema.REFERENTIAL_CONSTRAINTS AS tb2 ON
            tb1.CONSTRAINT_NAME = tb2.CONSTRAINT_NAME AND tb1.CONSTRAINT_SCHEMA = tb2.CONSTRAINT_SCHEMA
            WHERE TABLE_SCHEMA = DATABASE()
            AND REFERENCED_TABLE_SCHEMA = DATABASE() AND REFERENCED_COLUMN_NAME IS NOT NULL
            AND tb1.TABLE_NAME in $tableNameStr";

        $foreignKeys = $connect->createCommand($sql)->queryAll();
        foreach ($foreignKeys as $fk) {
            $str = '$this->addForeignKey(';
            $str .= '\'' . $fk['CONSTRAINT_NAME'] . '\', ';
            $str .= '\'{{%' . $this->getTableName($fk['TABLE_NAME']) . '}}\', ';
            $str .= '\'' . $fk['COLUMN_NAME'] . '\', ';
            $str .= '\'{{%' . $this->getTableName($fk['REFERENCED_TABLE_NAME'])  . '}}\', ';
            $str .= '\'' . $fk['REFERENCED_COLUMN_NAME'] . '\', ';
            $str .= '\'' . $fk['DELETE_RULE'] . '\', ';
            $str .= '\'' . $fk['UPDATE_RULE'] . '\' ';
            $str .= ');';
            $upStr->addStr($str);
        }


        $upStr->addStr(' ');
        $upStr->addStr('$this->execute(\'SET foreign_key_checks = 1;\');');

        $downStr = new OutputString([
            'tabLevel' => $initialTabLevel
        ]);
        /* DROP TABLE */
        $downStr->addStr('$this->execute(\'SET foreign_key_checks = 0\');');
        foreach ($tables as $table) {
            if (! empty($table)) {
                $downStr->addStr('$this->dropTable(\'{{%' . $tablePrepared . '}}\');');
            }
        }
        $downStr->addStr('$this->execute(\'SET foreign_key_checks = 1;\');');
        return [
            $upStr,
            $downStr
        ];
    }

    /**
     * 生成表数据语句
     *
     * @param array    $tables  表数组
     * @param resource $connect 数据库连接句柄
     *
     * @return array
     *
     * @throws \yii\db\Exception
     */
    public function generalTableDatas($tables, $connect)
    {
        $initialTabLevel = 2;
        $upStr = new OutputString([
            'tabLevel' => $initialTabLevel
        ]);
        $upStr->addStr('$this->execute(\'SET foreign_key_checks = 0\');');
        $upStr->addStr(' ');
        foreach ($tables as $table) {
            $tablePrepared = $this->getTableName($table, $connect);

            $upStr->addStr('/* Table ' . $table . ' */');
            $tableSchema = \Yii::$app->db->getTableSchema($table);
            $data = $connect->createCommand('SELECT * FROM `' . $table . '`')->queryAll();
            $out = '$this->batchInsert(\'{{%' . $tablePrepared . '}}\',[';
            foreach ($tableSchema->columns as $column) {
                $out .= "'" . $column->name . "',";
            }
            $out = rtrim($out, ',') . '],[';
            foreach ($data as $row) {
                $out .= '[';
                foreach ($row as $field) {
                    if ($field === null) {
                        $out .= "null,";
                    } else {
                        $out .= "'" . addcslashes($field, "'") . "',";
                    }
                }
                $out = rtrim($out, ',') . "],\n";
            }
            $out = rtrim($out, ',') . ']);';
            $upStr->addStr($out);
            $upStr->addStr(' ');
        }
        $upStr->addStr('$this->execute(\'SET foreign_key_checks = 1;\');');
        $downStr = new OutputString();
        return [
            $upStr,
            $downStr
        ];
    }
}

/**
 * 输出类
 *
 * Class OutputString
 * @package yiiplus\desktop\modules\migrations\controllers
 */
class OutputString extends Object
{
    /*
     * 回车
     */
    public $nw = "\n";

    /*
     * tab
     */
    public $tab = "\t";

    /*
     * 输出数组
     */
    public $outputStringArray = [];

    /*
     * tab数
     */
    public $tabLevel = 0;

    /**
     * 添加字符串
     *
     * @param string $str 待处理字符串
     */
    public function addStr($str)
    {
        $str = str_replace($this->tab, '', $str);
        $this->outputStringArray[] = str_repeat($this->tab, $this->tabLevel) . $str;
    }

    /**
     * 输出字符串
     *
     * @return string
     */
    public function output()
    {
        return implode($this->nw, $this->outputStringArray);
    }
}