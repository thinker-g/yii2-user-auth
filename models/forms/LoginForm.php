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

use Yii;
use yii\base\Model;
use yii\base\NotSupportedException;
use thinker_g\UserAuth\interfaces\CredentialIdentity;
use thinker_g\UserAuth\interfaces\Authenticatable;
use yii\web\IdentityInterface;

/**
 * Login form
 */
class LoginForm extends CredentialForm
{

    /**
     * @var string|array Validator method name used by password validation.
     * Two available built-in validators:
     * 1. `validatePrimaryPassword`: The default validtor, reads password hash from the "user" model.
     * 2. `validateAgentPassword`: Will use corresponding "user_ext_account" and read password hash from its
     *    "access_token". This can be an array, an extra key: "params" is recognizable.
     *    Example with default values:
     *    ~~~
     *    [
     *        'validateAgentPassword',
     *        'params' => [
     *            'agentAcctModelClass' => 'thinker_g\UserAuth\models\ars\SuperAgentAccount',
     *            'agentType' => 'super_admin'
     *        ]
     *    ]
     *    ~~~
     */
    public $passwordValidator = 'validatePrimaryPassword';

    /**
     * @var string Username provided by browser.
     */
    public $username;

    /**
     * @var string Password provided by browser.
     */
    public $password;

    /**
     * @var bool Whether to keep current user logged in.
     * See option [[autoLoginDuration]] for the duration.
     */
    public $rememberMe = true;

    /**
     * @var int How long, in seconds, to keep current user logged in.
     * Default to 7 days. This parameter will only be read when [[rememberMe]] is true.
     */
    public $rememberMeDuration = 604800;

    private $_user = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        if (is_string($this->passwordValidator)) {
            $this->passwordValidator = [$this->passwordValidator];
        }
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            array_merge(['password'], $this->passwordValidator),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePrimaryPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, 'Invalid username or password.');
            } elseif (!$user instanceof IdentityInterface) {
                throw new NotSupportedException(
                    get_class($user)
                    . ' must implement interface \\yii\\web\\IdentityInterface.'
                );
            } elseif (!$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Validates the password stored in super agent account.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params Keys "acctModelClass" & "agentType" can be recognized.
     */
    public function validateAgentPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($user = $this->getUser()) {
                // Validate user account.
                $params = array_merge([
                    'agentAcctModelClass' => 'thinker_g\UserAuth\models\ars\SuperAgentAccount',
                    'agentType' => 'super_admin',
                ], $params ? $params : []);
                $superAcct = $user->hasOne($params['agentAcctModelClass'], ['user_id' => 'id'])
                    ->where(['from_source' => $params['agentType']])
                    ->one();
                if (is_null($superAcct)) {
                    return $this->addError($attribute, 'Account not found.');
                } elseif (!$superAcct->access_token) {
                    return $this->addError($attribute, 'Invalid account.');
                } elseif (!Yii::$app->security->validatePassword($this->password, $superAcct->access_token)) {
                    return $this->addError($attribute, 'Incorrect password');
                } // else {}

            } else {
                // User not found.
                return $this->addError($attribute, 'User not found.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            //todo rememberMeDuration default value not implemented.
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? $this->rememberMeDuration : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $userModel = Yii::createObject($this->getCredentialModelClass());
            if (!$userModel instanceof Authenticatable) {
                throw new NotSupportedException(
                    get_class($userModel)
                    . ' must implement interface \\thinker_g\\UserAuth\\interfaces\\Authenticatable.'
                );
            }
            $this->_user = $userModel::findByLogin($this->username);
        }

        return $this->_user;
    }
}
