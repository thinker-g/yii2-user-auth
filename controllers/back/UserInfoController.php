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

use thinker_g\UserAuth\controllers\BaseAdminController;

/**
 * UserInfoController implements the CRUD actions for UserInfo model.
 */
class UserInfoController extends BaseAdminController
{
    public $controllerMvMap = [
        [
            'model' => 'thinker_g\UserAuth\models\ars\UserInfo',
            'search' => 'thinker_g\UserAuth\models\ars\UserInfoSearch',
        ],
    ];
}
