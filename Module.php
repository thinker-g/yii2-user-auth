<?php
namespace thinker_g\UserAuth;

use thinker_g\Helpers\traits\NSedModuleViewPath;

/**
 * User management module.
 *
 * This module provides user management and basic authentication functionalities.
 * Following models are provided to make the module more extendable:
 * User: This model holds only id, username, primary_email and password_hash for a registered user.
 * UserExtAccount: This is external user account for advanced usage, such as oauth or high level user authentication.
 * UserInfo: This is the model to keep additional user inforamtion, such as birth, age, etc., which are not
 * relevant to the program logic.
 * SuperAgentAccount: This model extends UserExtAccount, and is for granting a user permisson to login to
 * the backend of the website with another password. Normally after adding a user (or a new user registered
 * from frontend), we'll just need to grant him this account with a "super admin" password stored in it, that user will
 * be able to login to backend. See "SuperLoginForm" for the details of the code.
 *
 * @author Thinker_g
 */
class Module extends \yii\base\Module
{
    use NSedModuleViewPath;

    /**
     * Module name.
     * @var string
     */
    public $name = "User Manager";

    /**
     * Sidebar menu items, view must invoke this attribute to generate navigation items on page.
     * Default configuration is for backend management console.
     * @var array
     */
    public $sidebarMenu = [
        ['label' => 'Splash page', 'url'=>['default/index']],
        ['label' => 'Users', 'url'=>['user/index']],
        ['label' => 'External Accounts', 'url' => ['user-ext-account/index']],
        ['label' => 'User Infos', 'url' => ['user-info/index']],
        ['label' => 'Super Agents', 'url' => ['super-agent/index']],
        ['label' => 'Add New User', 'url' => ['user/create']],
        ['label' => 'Who Am I', 'url' => ['user/who-am-i']],
    ];

    /**
     * Set it to "thinker_g\UserAuth\backcontrollers" while enabling backend console.
     * @inheritdoc
     * @var string
     */
    public $controllerNamespace = 'thinker_g\UserAuth\controllers\front';

    /**
     * Login form model configuration.
     * Set it to "thinker_g\UserAuth\models\SuperLoginForm" for authenticating user using SuperAgentAccount in backend.
     * @var string|array
     */
    public $modelLoginForm = 'thinker_g\UserAuth\models\forms\LoginForm';

    /**
     * Signup form model configuration.
     * Used in frontend.
     * @var string|array
     */
    public $modelSignupForm = 'thinker_g\UserAuth\models\forms\SignupForm';

    /**
     * Password reset request form configuration.
     * The user's email is required by this model. System will send a password reset email to the user.
     * @var string|array
     */
    public $modelPasswordResetRequestForm = 'thinker_g\UserAuth\models\forms\PasswordResetRequestForm';

    /**
     * Reset password form configuration.
     * User needs to input a new password into this form.
     * @var string|array
     */
    public $modelResetPasswordForm = 'thinker_g\UserAuth\models\forms\ResetPasswordForm';

    /**
     * Roles allowed to access the module.
     * @var unknown
     */
    public $roles = ['@'];

    /**
     * View map for frontend controllers.
     * @var array
     */
    public $mvMap = [
        'auth' => [
            'request-password-reset' => ['view' => 'requestPasswordReset'],
            'reset-password' => ['view' => 'resetPassword']
        ]
    ];

    /**
     * View map for backend controllers.
     * @var unknown
     */
    public $backMvMap = [
        'default' => [
            ['model' => 'thinker_g\UserAuth\models\ars\User'],
        ],
        'auth' => [
            'request-password-reset' => ['view' => 'requestPasswordResetToken'],
            'reset-password' => ['view' => 'resetPassword'],
        ],
        'user' => [
            [
                'model' => 'thinker_g\UserAuth\models\ars\User',
                'search' => 'thinker_g\UserAuth\models\ars\UserSearch',
            ],
            'who-am-i' => [
                'view' => 'view',
            ]
        ],
        'user-ext-account' => [
            [
                'model' => 'thinker_g\UserAuth\models\ars\UserExtAccount',
                'search' => 'thinker_g\UserAuth\models\ars\UserExtAccountSearch',
            ]
        ],
        'user-info' => [
            [
                'model' => 'thinker_g\UserAuth\models\ars\UserInfo',
                'search' => 'thinker_g\UserAuth\models\ars\UserInfoSearch',
            ]
        ],
        'super-agent' => [
            [
                'model' => 'thinker_g\UserAuth\models\ars\SuperAgentAccount',
                'search' => 'thinker_g\UserAuth\models\ars\SuperAgentAccountSearch',
            ]
        ],
    ];

    /**
     * @inheritdoc
     * @see \yii\base\Module::beforeAction()
     */
    public function beforeAction($action)
    {
        $continue = parent::beforeAction($action);
        $action->controller->getView()->params['sidebarMenu'] = $this->sidebarMenu;
        return $continue;
    }

}
