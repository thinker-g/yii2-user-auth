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
 * @property string $open_uid
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class UserExtAccount extends \yii\db\ActiveRecord
{

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
            [['user_id'], 'integer'],
            [['from_source', 'access_token'], 'required'],
            [['user_id', 'from_source'], 'unique', 'targetAttribute' => ['user_id', 'from_source']],
            [['created_at', 'updated_at'], 'safe'],
            [['from_source'], 'string', 'max' => 64],
            [['access_token', 'email', 'open_uid'], 'string', 'max' => 255]
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
            'open_uid' => Yii::t('app', 'Open UID'),
            'email' => Yii::t('app', 'Email'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Available open-auth account sources.
     *
     * @return array
     */
    public static function availableSources()
    {
        return [
            'facebook' => 'Facebook',
            'twitter' => 'Twitter',
            'google_account' => 'Google Account',
            'sina_weibo' => 'Sina Weibo',
            'qq' => 'QQ',
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
