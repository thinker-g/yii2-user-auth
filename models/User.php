<?php
namespace thinker_g\UserAuth\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\db\ActiveQuery;
use thinker_g\UserAuth\interfaces\CredentialInterface;
use yii\validators\EmailValidator;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $primary_email
 * @property string $password_hash
 * @property integer $status
 * @property string $auth_key
 * @property string $password_reset_token
 * @property string $password Virtual attribute, does't exist in db table.
 * @property integer $created_at
 * @property integer $updated_at
 * @property array $userExtAccounts
 */
class User extends ActiveRecord implements IdentityInterface, CredentialInterface
{
    /**
     * User is deleted.
     * For further usage, status code 0-9 is preserved for unavailable status.
     * @var int
     */
    const STATUS_DELETED = 0;

    /**
     * User is registered, but still not finish the onboarding procedure yet.
     * The onboarding procedure might be something like email confirmation, initial email stream, etc.
     * For further usage, status code 10-19 is preserved for unavailable status.
     * @var int
     */
    const STATUS_PENDING = 10;

    /**
     * User is active, means normal users who completed all their onboarding procedure.
     * For further usage, status code 20-29 is preserved for unavailable status.
     * @var int
     */
    const STATUS_ACTIVE = 20;

    /**
     * Number of seconds before the password reset token is expired.
     * @var int
     */
    public static $passwordResetTokenExpire = 1800;

    /**
     * Attribute to temporarily store password.
     * This attribute should always be "null" unless an operation is changing password.
     * Whenever this attributes has value, the password_hash will be updated immediately by calling `setPassword()`.
     * @var string
     */
    private $_pswd;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'primary_email'], 'unique'],
            [['username', 'primary_email'], 'trim'],
            [['primary_email'], 'email'],
            [['username', 'password', 'primary_email'], 'string', 'min' => 5, 'max' => 255],
            [['password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 64],
            [['status'], 'default', 'value' => self::STATUS_PENDING],
            [['status'], 'in', 'range' => [
                self::STATUS_PENDING,
                self::STATUS_ACTIVE,
                self::STATUS_DELETED
            ]],
            [['created_at', 'last_login_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'username' => Yii::t('app', 'Username'),
            'primary_email' => Yii::t('app', 'Primary Email'),
            'Password' => Yii::t('app', 'Password'),
            'status' => Yii::t('app', 'Status'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_reset_token' => Yii::t('app', 'Password reset Token'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'userExtaccounts' => Yii::t('app', 'User Ext Accounts'),
        ];
    }

    /**
     * @inheritdoc
     * @see \yii\web\IdentityInterface::findIdentity()
     */
    public static function findIdentity($id)
    {
        return static::find()->where([
            'and',
            ['id' => $id],
            ['>=', 'status', self::STATUS_PENDING]
        ])->one();
    }

    /**
     * @inheritdoc
     * @see \yii\web\IdentityInterface::findIdentityByAccessToken()
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     * @see \thinker_g\UserAuth\interfaces\CredentialInterface::findByLogin()
     */
    public static function findByLogin($login)
    {
        return static::find()->where(['and',
            ['>=', 'status', self::STATUS_PENDING],
            ['or',
                ['username' => $login],
                ['primary_email' => $login],
            ],
        ])->one();
    }

    /**
     * Finds user by password reset token
     * @inheritdoc
     * @see \thinker_g\UserAuth\interfaces\CredentialInterface::findByPasswordResetToken()
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::find()->where([
            'and',
            ['password_reset_token' => $token],
            ['>=', 'status', self::STATUS_PENDING]
        ])->one();
    }

    /**
     * @inheritdoc
     * @see \thinker_g\UserAuth\interfaces\CredentialInterface::isPasswordResetTokenValid()
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = self::$passwordResetTokenExpire;
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     * @see \yii\web\IdentityInterface::getId()
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     * @see \yii\web\IdentityInterface::getAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     * @see \yii\web\IdentityInterface::validateAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     * @see \yii\web\IdentityInterface::validatePassword()
     */
    public function validatePassword($password)
    {
        return $this->password_hash && Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @inheritdoc
     * @see \thinker_g\UserAuth\interfaces\CredentialInterface::setPassword()
     */
    public function setPassword($password)
    {
        if (!$password) return;
        $this->_pswd = $password;
        $this->password_hash = Yii::$app->security->generatePasswordHash($this->_pswd);
        $this->generateAuthKey();
    }

    /**
     * @inheritdoc
     * @see \thinker_g\UserAuth\interfaces\CredentialInterface::getPassword()
     */
    public function getPassword()
    {
        return $this->_pswd;
    }

    /**
     * Generates "remember me" authentication key.
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @inheritdoc
     * @see \thinker_g\UserAuth\interfaces\CredentialInterface::generatePasswordResetToken()
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * @inheritdoc
     * @see \thinker_g\UserAuth\interfaces\CredentialInterface::removePasswordResetToken()
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Get Available status.
     * @return multitype:string
     */
    public static function availableStatus()
    {
        return [
            static::STATUS_PENDING => 'Pending',
            static::STATUS_ACTIVE => 'Active',
            static::STATUS_DELETED => 'Deleted',
        ];
    }

    /*
     * -- Relations begin --
     */

    /**
     * Get super agent account of current user.
     * @return ActiveQuery
     */
    public function getSuperAgentAcct()
    {
        return $this->hasOne(UserExtAccount::className(), ['user_id' => 'id'])
            ->where(['from_source' => UserExtAccount::SRC_SUPER_AGENT]);
    }

    /**
     * Get ext accounts of current user.
     * @return ActiveQuery
     */
    public function getUserExtAccounts()
    {
        return $this->hasMany(UserExtAccount::className(), ['user_id' => 'id']);
    }

    /**
     * Get additional information for current user.
     * @return ActiveQuery
     */
    public function getUserInfo()
    {
        return $this->hasOne(UserInfo::className(), ['user_id' => 'id']);
    }
    /*
     * -- Relations end --
     */
}
