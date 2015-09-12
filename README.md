# User Auth [v0.0.1]
A Yii 2.0 framework based user/account management module.

NOTE: This module requires composer package `thinker-g/yii2-helpers`, please issue following command on main project to get dependencies.
```bash
php composer.phar require thinker-g/yii2-helpers
```

[*Doc is under development.*]

# Installation Tips:
1. Please setup `[[identityClass]]` of your "*user*" component to `thinker_g\UserAuth\models\ars\User` so the backend of this module can manage users.
2. Setup `[[loginUrl]]` of your "*user*" component to `['user-auth/auth/login]`, to allow the framework redirect users to the module's login page when login is needed, where the `user-auth` is the id you gave to this module in your application.

# Minimal installation
## console
`console/config/main.php`
Used for creating the very first "super user", and grant super-agent accounts.

```php
return [
    'controllerMap' => [
        // ...
        'user' => [
            'class' => 'thinker_g\UserAuth\console\UserCommand',
            'userModel' => 'thinker_g\UserAuth\models\ars\User',
        ],
        // ...
    ],
];

```

## frontend
`frontend/config/main.php`
```php

return [
    // ...
    'modules' => [
        // begin - user
        'user' => 'thinker_g\UserAuth\Module',
        // ...
    ], // End of modules
    // ...
];

```

## backend
`backend/config/main.php`
```php
return [
    // ...
    'modules' => [
        // begin - user
        'user' => [
            'class' => 'thinker_g\UserAuth\Module',
            'controllerNamespace' => 'thinker_g\UserAuth\controllers\back',
            'layout' => '@vendor/thinker-g/yii2-helpers/views/bs3/layouts/lsidebarmenu',
            'mvMap' => [
                'auth' => [
                    'login' => [
                        'model' => [
                            'class' => 'thinker_g\UserAuth\models\forms\LoginForm',
                            'passwordValidator' => ['validateAgentPassword'],
                            'rememberMe' => false,
                        ],
                    ],
                ],
        ], // end - user
        // ...
    ], // End of modules
    // ...
];

```