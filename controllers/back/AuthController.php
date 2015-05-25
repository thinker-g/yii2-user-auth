<?php
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
    public $defaultAction = 'login';

    /**
     * @inheritdoc
     * @var string
     */
    public $moduleAttr = 'backMvMap';

}

?>