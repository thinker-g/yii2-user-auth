<?php
/**
 * @link https://github.com/thinker-g/yii2-user-auth
 * @copyright Copyright (c) Thinker_g
 * @license MIT
 * @version v0.0.1
 * @author Thinker_g
 * @since v0.0.1
 */
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
        ['label' => 'Add New User', 'url' => ['user/create']],
        ['label' => 'Super Agents', 'url' => ['super-agent/index']],
        ['label' => 'Who Am I', 'url' => ['user/who-am-i']],
    ];

    /**
     * Set it to "thinker_g\UserAuth\backcontrollers" while enabling backend console.
     * @inheritdoc
     * @var string
     */
    public $controllerNamespace = 'thinker_g\UserAuth\controllers\front';

    /**
     * Roles allowed to access the module.
     * @var unknown
     */
    public $roles = ['@'];

    /**
     * View map for frontend controllers.
     * @var array
     */
    public $mvMap = [];

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

    /**
     * Get current version of the module.
     * @return string
     */
    public function getVersion()
    {
        return 'v0.0.1';
    }
}
