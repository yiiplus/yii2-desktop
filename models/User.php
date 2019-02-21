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
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yiiplus\desktop\components\Configs;

/**
 * User model
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * 激活状态
     */
    const STATUS_INACTIVE = 0;

    /**
     * 未激活状态
     */
    const STATUS_ACTIVE = 10;

    /**
     * 默认头像
     */
    const DEFAULT_AVATAR_URL = '/img/user2-160x160.jpg';

    /**
     * 密码
     */
    public $password;

    /**
     * 确认密码
     */
    public $repassword;

    /**
     * 角色
     */
    public $role;

    /**
     * 权限
     */
    public $permission;

    /**
     * 类型
     */
    public $type;

    /**
     * Declares the name of the database table associated with this AR class.
     *
     * @return string the table name
     */
    public static function tableName()
    {
        return Configs::instance()->userTable;
    }

    /**
     * Makes sure that the behaviors declared in [[behaviors()]] are attached to this component.
     * 
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * Returns the validation rules for attributes.
     *
     * @return array validation rules
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],

            ['username', 'required'],
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'unique', 'message' => '登录名已存在'],
            ['username', 'string', 'min' => 2, 'max' => 10],

            ['nickname', 'required'],
            ['nickname', 'filter', 'filter' => 'trim'],
            ['nickname', 'unique', 'message' => '昵称已存在'],
            ['nickname', 'string', 'min' => 2, 'max' => 10],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'yiiplus\desktop\models\User', 'message' => '邮箱已存在'],

            [['password', 'repassword'], 'required'],
            ['password', 'string', 'min' => 6],
            ['repassword', 'compare', 'compareAttribute' => 'password', 'message' => '密码不一致'],

            [['role', 'permission', 'type', 'last_login_at'], 'safe'],
        ];
    }

    /**
     * Returns the list of all attribute names of the model.
     *
     * @return array list of attribute names.
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yiiplus/desktop', 'ID'),
            'username' => Yii::t('yiiplus/desktop', '登录名'),
            'nickname' => Yii::t('yiiplus/desktop', '昵称'),
            'email' => Yii::t('yiiplus/desktop', '邮箱'),
            'avatar' => Yii::t('yiiplus/desktop', '头像'),
            'password' => Yii::t('yiiplus/desktop', '密码'),
            'repassword' => Yii::t('yiiplus/desktop', '确认密码'),
            'role' => Yii::t('yiiplus/desktop', '角色'),
            'permission' => Yii::t('yiiplus/desktop', '权限'),
            'created_at' => Yii::t('yiiplus/desktop', '创建时间'),
            'updated_at' => Yii::t('yiiplus/desktop', '更新时间'),
            'last_login_at' => Yii::t('yiiplus/desktop', '最后登录时间'),
        ];
    }

    /**
     * 根据ID获取用户信息
     * 
     * @param int|string $id 用户ID
     * 
     * @return IdentityInterface|User|null
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * 根据用户token获取用户信息
     * 
     * @param mixed string $token TOKEN
     * @param null  int    $type  类型
     * 
     * @return void|IdentityInterface
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException(Yii::t('yiiplus/desktop', '"findIdentityByAccessToken"方法未实现'));
    }

    /**
     * Finds user by username
     *
     * @param string $username 用户名
     * 
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * 
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * 
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * 获取ID
     * 
     * @return int|mixed|string
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * 获取authkey
     * 
     * @return mixed|string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * 验证authkey
     * 
     * @param string $authKey authkey
     * 
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * 
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * 
     *  @return null
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     * 
     *  @return null
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     * 
     *  @return null
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     * 
     * @return null
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Returns the database connection used by this AR class.
     *
     * @return null|object|\yii\db\Connection
     */
    public static function getDb()
    {
        return Configs::userDb();
    }
}
