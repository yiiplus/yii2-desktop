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

namespace yiiplus\desktop\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yiiplus\desktop\components\Configs;

/**
 * 日志model
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Configs::instance()->logTable;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['user_id', 'ip'], 'integer'],
            [['route'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yiiplus/desktop', 'ID'),
            'route' => Yii::t('yiiplus/desktop', '路由'),
            'description' => Yii::t('yiiplus/desktop', '描述'),
            'created_at' => Yii::t('yiiplus/desktop', '创建时间'),
            'user_id' => Yii::t('yiiplus/desktop', '用户ID'),
            'ip' => Yii::t('yiiplus/desktop', '操作人ip'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => null
            ]
        ];
    }
}
