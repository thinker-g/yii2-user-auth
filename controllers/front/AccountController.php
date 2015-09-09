<?php
/**
 * @link https://github.com/thinker-g/yii2-user-auth
 * @copyright Copyright (c) Thinker_g
 * @license MIT
 * @version v0.0.1
 * @author Thinker_g
 * @since v0.0.1
 */

namespace thinker_g\UserAuth\controllers\front;

use thinker_g\UserAuth\controllers\BaseAdminController;

/**
 * Default frontend controller.
 *
 * @author Thinker_g
 */
class AccountController extends BaseAdminController
{
    public $defaultAction = 'view';

    public $moduleMvMapAttr = 'mvMap';

    /**
     * @inheritdoc
     * @see \thinker_g\Helpers\controllers\CrudController::actionUpdate()
     */
    public function actionUpdate()
    {
        // TODO Auto-generated method stub
    }

    /**
     * @inheritdoc
     * @see \thinker_g\Helpers\controllers\CrudController::actionView()
     */
    public function actionView()
    {
        return $this->render($this->viewID, [
            'model' => \Yii::$app->getUser()->getIdentity()
        ]);
    }

}
