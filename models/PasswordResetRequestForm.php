<?php
namespace thinker_g\UserAuth\models;

use yii\base\Model;
use thinker_g\UserAuth\interfaces\PasswordResettable;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $userModelClass = 'thinker_g\UserAuth\models\User';
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
                'targetClass' => $this->userModelClass,
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
        $userModelClass = $this->userModelClass;
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
