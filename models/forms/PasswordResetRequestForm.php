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

use yii\base\Model;
use thinker_g\UserAuth\interfaces\PasswordResettable;
use thinker_g\UserAuth\models\ars\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends CredentialForm
{
    /**
     * @var array View id of password reset mail.
     * This should be an associated array indexed by 'html' and 'text' for different type of emails sent by 'mailer' component.
     * This will be passed into the 1st parameter of [[\yii\mail\MailerInterface::compose()]].
     */
    public $mailerView = [
        'html' => 'passwordResetToken-html',
        'text' => 'passwordResetToken-text'
    ];

    /**
     * @var array The from information of password reset email.
     * This should be an array whose key is the from email address and value is the from name.
     * This will be passed into the parameter of [[\yii\mail\MailerInterface::setFrom()]].
     */
    public $mailerFrom = ['noreply@example.com' => 'Support Service'];
    /**
     * @var string The subject of the password reset email.
     * This will be passed into the parameter of [[\yii\mail\MailerInterface::setSubject()]].
     */
    public $mailerSubject = 'Reset Account Password';

    /**
     * @var string Email address submitted.
     */
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
                'filter' => ['>=', 'status', User::STATUS_ALIVE],
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
                    return \Yii::$app->mailer->compose($this->mailerView, ['user' => $user])
                        ->setFrom($this->mailerFrom)
                        ->setTo($this->email)
                        ->setSubject($this->mailerSubject)
                        ->send();
                }
            }
        }

        return false;
    }
}
