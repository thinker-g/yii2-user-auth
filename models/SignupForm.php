<?php
namespace thinker_g\UserAuth\models;

use yii\base\Model;
use yii\base\NotSupportedException;
use Yii;
use thinker_g\UserAuth\interfaces\CredentialInterface;

/**
 * Signup form
 */
class SignupForm extends Model
{
    /**
     * User model configuration.
     * @var string|array
     */
    public $userModelClass = 'thinker_g\UserAuth\models\User';

    /**
     * Attribute map between this class and the configured user model class,
     * where keys are the form model attributes and corresponding values belong to the specified user model.
     * This will be used to assign values from the form to the created user model for saving data.
     * @var array
     */
    public $userAttrMap = [
        'username' => 'username',
        'password' => 'password',
        'email' => 'primary_email'
    ];

    /**
     * Names listed in this array won't be allowed to register.
     * @var array
     */
    public $reservedUsernames;

    /**
     * Emails listed in this array won't be allowed to register.
     * @var array
     */
    public $reservedEmails;

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
                'message' => '{attribute} "{value}" is reserved for system usage, please choose another one.'
            ],
            ['email', 'in',
                'not' => true,
                'range' => $this->reservedEmails ? $this->reservedEmails : [],
                'message' => '{attribute} "{value}" is reserved for system usage, please choose another one.'
            ],
            ['username', 'unique',
                'targetClass' => $this->userModelClass,
            ],
            ['email', 'unique',
                'targetClass' => $this->userModelClass,
                'targetAttribute' => 'primary_email',
                'message' => 'This email address has already been taken.'
            ],
            [['username','password'], 'string', 'min' => 5, 'max' => 255],
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
            $user = Yii::createObject($this->userModelClass);
            if (!$user instanceof CredentialInterface) {
                throw new NotSupportedException(
                    get_class($user)
                    . ' must implement interface \\thinker_g\\UserAuth\\interfaces\\CredentialInterface.'
                );
            }
            foreach ($this->userAttrMap as $formAttr => $userAttr) {
                $user->{$userAttr} = $this->{$formAttr};
            }
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
