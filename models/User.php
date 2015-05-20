<?php
namespace thinker_g\UserAuth\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\db\Expression as DbExpression;
use yii\db\ActiveQuery;

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
 *
 */
class User extends ActiveRecord implements IdentityInterface
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
     * User is active. Means normal users who completed all their onboarding procedure.
     * For further usage, status code 20-29 is preserved for unavailable status.
     * @var int
     */
    const STATUS_ACTIVE = 20;

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
            [['password', 'created_at', 'last_login_at'], 'safe'],
            [['status'], 'default', 'value' => self::STATUS_PENDING],
            [['status'], 'in', 'range' => [
                self::STATUS_PENDING,
                self::STATUS_ACTIVE,
                self::STATUS_DELETED
            ]],
            [['username', 'primary_email', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 64]
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
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::find()->where([
            'and',
            ['username' => $username],
            ['>=', 'status', self::STATUS_PENDING]
        ])->one();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
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
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password_hash && Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        if (!$password) return;
        $this->_pswd = $password;
        $this->password_hash = Yii::$app->security->generatePasswordHash($this->_pswd);
    }

    /**
     * Password Getter
     *
     * @return string $password
     */
    public function getPassword()
    {
        return $this->_pswd;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
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
