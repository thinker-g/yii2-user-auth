<?php
namespace thinker_g\UserAuth\models\forms;

use yii\base\Model;
use thinker_g\UserAuth\interfaces\PasswordResettable;
use thinker_g\UserAuth\models\ars\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends CredentialForm
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => $this->getCredentialModelClass(),
                'targetAttribute' => 'primary_email',
                'filter' => ['>=', 'status', User::STATUS_PENDING],
                'message' => 'There is no user with such email.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $userModelClass = $this->getCredentialModelClass();
        $user = $userModelClass::findByLogin($this->email);

        if ($user) {
            if (!$user instanceof PasswordResettable) {
                throw new NotSupportedException(
                    get_class($user)
                    . ' must implement interface \\thinker_g\\UserAuth\\interfaces\\PasswordResettable .'
                );
            } else {
                if (!$userModelClass::isPasswordResetTokenValid($user->password_reset_token)) {
                    $user->generatePasswordResetToken();
                }

                if ($user->save()) {
                    return \Yii::$app->mailer->compose(['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['user' => $user])
                        ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                        ->setTo($this->email)
                        ->setSubject('Password reset for ' . \Yii::$app->name)
                        ->send();
                }
            }
        }

        return false;
    }
}
