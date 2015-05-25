<?php
namespace thinker_g\UserAuth\models;

use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $userModelClass = 'thinker_g\UserAuth\models\User';
    public $reservedUsernames;
    public $username;
    public $email;
    public $password;
    public $repeatPassword;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password', 'repeatPassword'], 'required'],
            [['username', 'email'], 'filter', 'filter' => 'trim'],
            ['username', 'in',
                'not' => true,
                'range' => $this->reservedUsernames ? $this->reservedUsernames : [],
                'message' => '{attribute} "{value}" is reserved for system usage, you cannot use it.'
            ],
            ['username', 'unique',
                'targetClass' => $this->userModelClass,
            ],
            ['email', 'unique',
                'targetClass' => $this->userModelClass,
                'targetAttribute' => 'primary_email',
                'message' => 'This email address has already been taken.'
            ],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['password', 'string', 'min' => 5],
            ['email', 'email'],
            ['repeatPassword', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new $this->userModelClass;
            $user->username = $this->username;
            $user->primary_email = $this->email;
            $user->setPassword($this->password);
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
