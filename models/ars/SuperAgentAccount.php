<?php

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
            [['user_id', 'ext_user_id'], 'integer'],
            [['user_id', 'from_source'], 'unique', 'targetAttribute' => ['user_id', 'from_source']],
            [['password', 'created_at', 'updated_at'], 'safe'],
            [['from_source'], 'string', 'max' => 64],
            [['access_token', 'email'], 'string', 'max' => 255]
        ];
    }

    public function __set($name, $value)
    {
        if ($name == 'from_source') {
            $value = static::SRC_SUPER_AGENT;
        }
        parent::__set($name, $value);
    }

    public function beforeSave($insert)
    {
        $this->from_source = static::SRC_SUPER_AGENT;
        return parent::beforeSave($insert);
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
}
