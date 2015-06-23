<?php

namespace thinker_g\UserAuth\models\ars;

use Yii;

/**
 * This is the model class for table "{{%user_info}}".
 *
 * @property integer $user_id
 * @property integer $is_male
 * @property string $dob
 * @property string $board_type
 * @property string $ski_age
 *
 * @property User $user
 */
class UserInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            ['user_id', 'unique'],
            [['user_id', 'is_male'], 'integer'],
            [['dob'], 'safe'],
            [['board_type'], 'string'],
            [['ski_age'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'is_male' => Yii::t('app', 'Is Male'),
            'dob' => Yii::t('app', 'Dob'),
            'board_type' => Yii::t('app', 'Board Type'),
            'ski_age' => Yii::t('app', 'Ski Age'),
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
