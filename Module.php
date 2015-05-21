<?php

namespace thinker_g\UserAuth;

use thinker_g\Helpers\traits\NSedModuleViewPath;

class Module extends \yii\base\Module
{
    use NSedModuleViewPath;
    public $name = "User Manager";
    public $baseBackendNavMenu = [
        ['label' => 'Splash page', 'url'=>['default/index']],
        ['label' => 'Users', 'url'=>['user/index']],
        ['label' => 'External Accounts', 'url' => ['user-ext-account/index']],
        ['label' => 'User Infos', 'url' => ['user-info/index']],
        ['label' => 'Super Agents', 'url' => ['super-agent/index']],
    ];
    /**
     * Use thinker_g\UserAuth\backcontrollers while enabling backend console.
     * @var string
     */
    public $controllerNamespace = 'thinker_g\UserAuth\controllers\front';

    public $modelLoginForm = 'thinker_g\UserAuth\models\LoginForm';
    public $modelSignupForm = 'thinker_g\UserAuth\models\SignupForm';
    public $modelPasswordResetRequestForm = 'thinker_g\UserAuth\models\PasswordResetRequestForm';
    public $modelResetPasswordForm = 'thinker_g\UserAuth\models\ResetPasswordForm';

    public $roles = ['@'];

    public $mvMap = [
        'auth' => [
            'request-password-reset' => ['view' => 'requestPasswordReset'],
            'reset-password' => ['view' => 'resetPassword']
        ]
    ];

    public $backMvMap = [
        'auth' => [
            'request-password-reset' => ['view' => 'requestPasswordResetToken'],
            'reset-password' => ['view' => 'resetPassword'],
        ],
        'user' => [
            [
                'model' => 'thinker_g\UserAuth\models\User',
                'search' => 'thinker_g\UserAuth\models\UserSearch'
            ],
        ],
        'user-ext-account' => [
            [
                'model' => 'thinker_g\UserAuth\models\UserExtAccount',
                'search' => 'thinker_g\UserAuth\models\UserExtAccountSearch',
            ]
        ],
        'user-info' => [
            [
                'model' => 'thinker_g\UserAuth\models\UserInfo',
                'search' => 'thinker_g\UserAuth\models\UserInfoSearch',
            ]
        ],
        'super-agent' => [
            [
                'model' => 'thinker_g\UserAuth\models\SuperAgentAccount',
                'search' => 'thinker_g\UserAuth\models\SuperAgentAccountSearch',
            ]
        ],
    ];
}
