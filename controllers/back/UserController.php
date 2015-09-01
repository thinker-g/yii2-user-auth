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
use yii\web\ForbiddenHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseAdminController
{
    public function actionWhoAmI()
    {
        if (is_null($_GET['id'] = \Yii::$app->getUser()->id)) {
            throw new ForbiddenHttpException('You must login to perform this action.');
        }
        return $this->runAction('view');
    }
}
