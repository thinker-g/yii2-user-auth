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
use yii\di\Instance;
use yii\base\NotSupportedException;
use Yii;

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
     * @var string Module name.
     */
    public $name = "User Manager";

    /**
     * @var array Sidebar menu items.
     * This will be added to the view's [[params]] attributes indexed by key 'sidebarMenu'.
     * Views can invoke that to generate navigation items on page.
     * Default configuration is for backend management console.
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
     * @inheritdoc
     * @var string
     * Set it to "thinker_g\UserAuth\controllers\back" while using backend console
     */
    public $controllerNamespace = 'thinker_g\UserAuth\controllers\front';

    /**
     * @var array Roles allowed to access the module.
     */
    public $roles = ['@'];

    /**
     * @var array View map for frontend controllers.
     */
    public $mvMap = [];

    /**
     * @var array Oauth adaptor configuration array, indexed by from_source.
     * @example
     * [
     *     'linkedin' => [
     *         'class' => 'thinker_g\UserAuth\oauthAdaptors\
     *     ]
     * ]
     */
    public $oauthAdaptors = [];

    public $oauthAdaptorIdParam = 'from_source';

    private $_oauthAdaptors = [];

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

    /**
     *
     * @param string $adaptorId
     */
    public function getOauthAdaptor($adaptorId, $refresh = false)
    {
        if (!array_key_exists($adaptorId, $this->oauthAdaptors)) {
            return null;
        } elseif ($refresh || !array_key_exists($adaptorId, $this->_oauthAdaptors)) {
            $adaptor = Yii::createObject($this->oauthAdaptors[$adaptorId]);
            if (!Instance::ensure($adaptor, 'thinker_g\UserAuth\Interfaces\Oauth2Adaptor')) {
                throw new NotSupportedException("Adaptor must implement interface \thinker_g\UserAuth\Interfaces\OauthAdaptor");
            }
            $this->_oauthAdaptors[$adaptorId] = $adaptor;
        }
        return $this->_oauthAdaptors[$adaptorId];
    }
}
