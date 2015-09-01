<?php
/**
 * @link https://github.com/thinker-g/yii2-user-auth
 * @copyright Copyright (c) Thinker_g
 * @license MIT
 * @version v0.0.1
 * @author Thinker_g
 * @since v0.0.1
 */

namespace thinker_g\UserAuth\controllers\back;

use Yii;
use thinker_g\UserAuth\controllers\BaseAuthController;

/**
 * Backend authentication controller.
 * Uses the same methods with another model-view mapping configuration.
 * @author Thinker_g
 *
 */
class AuthController extends BaseAuthController
{

    /**
     * @inheritdoc
     * @var string
     */
    public $moduleMvMapAttr = 'backMvMap';

}

?>