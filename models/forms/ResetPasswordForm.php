<?php
/**
 * @link https://github.com/thinker-g/yii2-user-auth
 * @copyright Copyright (c) Thinker_g
 * @license MIT
 * @version v0.0.1
 * @author Thinker_g
 * @since v0.0.1
 */

namespace thinker_g\UserAuth\models\forms;

use yii\base\InvalidParamException;
use yii\base\NotSupportedException;
use yii\base\Model;
use Yii;
use thinker_g\UserAuth\interfaces\PasswordResettable;

/**
 * Password reset form
 */
class ResetPasswordForm extends CredentialForm
{
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
        $userModelClass = $this->getCredentialModelClass();
        $this->_user = $userModelClass::findByPasswordResetToken($this->token);

        if (!$this->_user) {
            throw new InvalidParamException('Invalid password reset token.');
        } elseif (!$this->_user instanceof PasswordResettable) {
            throw new NotSupportedException(
                get_class($this->_user)
                . ' must implement interface \\thinker_g\\UserAuth\\interfaces\\PasswordResettable .'
            );
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
