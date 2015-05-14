<?php

namespace thinker_g\UserAuth\models;

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
 *
 * @property User $user
 */
class UserExtAccount extends \yii\db\ActiveRecord
{
    const SRC_SUPER_AGENT = 'super_agent';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_ext_account}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'ext_user_id'], 'integer'],
            [['from_source', 'access_token'], 'required'],
            [['user_id', 'from_source'], 'unique', 'targetAttribute' => ['user_id', 'from_source']],
            [['created_at', 'updated_at'], 'safe'],
            [['from_source'], 'string', 'max' => 64],
            [['access_token', 'email'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'from_source' => Yii::t('app', 'From Source'),
            'access_token' => Yii::t('app', 'Access Token'),
            'ext_user_id' => Yii::t('app', 'Ext User ID'),
            'email' => Yii::t('app', 'Email'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function availableSources()
    {
        return [
            static::SRC_SUPER_AGENT => static::SRC_SUPER_AGENT
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
