<?php
namespace thinker_g\UserAuth\models;

use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $userModelClass = 'thinker_g\UserAuth\models\User';
    public $password;
    public $token;

    /**
     * @var \common\models\User
     */
    private $_user;


    /**
     * @inheritdoc
     * @see \yii\base\Object::init()
     */
    public function init()
    {
        parent::init();
        if (empty($this->token) || !is_string($this->token)) {
            throw new InvalidParamException('Password reset token cannot be blank.');
        }
        $userModelClass = $this->userModelClass;
        $this->_user = $userModelClass::findByPasswordResetToken($this->token);
        if (!$this->_user) {
            throw new InvalidParamException('Wrong password reset token.');
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 5],
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save();
    }
}
