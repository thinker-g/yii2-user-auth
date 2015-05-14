<?php
namespace thinker_g\UserAuth\models;

use Yii;

/**
 *
 * @author Thinker_g
 */
class SuperLoginForm extends LoginForm
{
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!($user && ($superAcct = $user->superAgentAcct) && $superAcct->access_token)) {
                $this->addError($attribute, 'Invalide Credential');
                return;
            }
            if (!Yii::$app->security->validatePassword($this->password, $superAcct->access_token)) {
                $this->addError($attribute, 'Incorrect password');
            }
        }
    }
}

?>