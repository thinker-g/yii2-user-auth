<?php
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
        return $this->actionView();
    }
}
