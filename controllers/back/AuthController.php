<?php
namespace thinker_g\UserAuth\controllers\back;

use Yii;
use thinker_g\UserAuth\controllers\BaseAuthController;

/**
 *
 * @author Thinker_g
 *
 */
class AuthController extends BaseAuthController
{
    public $defaultAction = 'login';
    public $moduleAttr = 'backMvMap';

}

?>