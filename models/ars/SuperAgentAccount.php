<?php
/**
 * @link https://github.com/thinker-g/yii2-user-auth
 * @copyright Copyright (c) Thinker_g
 * @license MIT
 * @version v0.0.1
 * @author Thinker_g
 * @since v0.0.1
 */

namespace thinker_g\UserAuth\models\ars;

use Yii;

/**
 * This is the model class for table "{{%user_ext_account}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $from_source
 * @property string $access_token
 * @property integer $ext_user_id
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 * @property string $password
 * @property User $user
 */
class SuperAgentAccount extends UserExtAccount
{
    private $_superPassword;

    public function rules()
    {
        return [
            ['user_id', 'integer'],
            [['user_id', 'password', 'from_source'], 'required'],
            [['email', 'from_source'], 'string', 'max' => 255],
            [['user_id', 'from_source'], 'unique', 'targetAttribute' => ['user_id', 'from_source']],
        ];
    }

    public function setPassword($password)
    {
        if (!$password) return;
        $this->_superPassword = $password;
        $this->access_token = Yii::$app->security->generatePasswordHash($this->_superPassword);
    }

    public function getPassword()
    {
        return $this->_superPassword;
    }

    /**
     * Get agent types in array, where keys are type ids, values are type name.
     *
     * @return array
     */
    public static function availableSources()
    {
        return array_combine($values = [
            'super_admin',
            'super_agent',
        ], $values);
    }

    /**
     * @inheritdoc
     * @see \thinker_g\UserAuth\models\ars\UserExtAccount::attributeLabels()
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'Target User ID'),
            'from_source' => Yii::t('app', 'Agent Type'),
        ];
    }
}
