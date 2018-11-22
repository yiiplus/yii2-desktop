<?php

namespace yiiplus\desktop\models;

use yii\behaviors\TimestampBehavior;
use yiiplus\desktop\components\Configs;

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
            'route' => Yii::t('yiiplus/desktop', 'LogRoute'),
            'description' => Yii::t('yiiplus/desktop', 'Description'),
            'created_at' => Yii::t('yiiplus/desktop', 'CreatedAt'),
            'user_id' => Yii::t('yiiplus/desktop', 'LogUid'),
            'ip' => Yii::t('yiiplus/desktop', 'LogIp'),
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
