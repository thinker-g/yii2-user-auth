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
use yii\helpers\ArrayHelper;

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
     * @see \thinker_g\UserAuth\controllers\BaseAdminController::behaviors()
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'actionBlocker' => [
                'class' => 'thinker_g\Helpers\behaviors\ActionBlocker',
                'only' => ['index', 'create', 'delete'],
                'exception' => 'yii\web\NotFoundHttpException',
                'exceptionParams' => ['Page not found.'],
            ]
        ]);
    }

    /**
     * @inheritdoc
     * @see \thinker_g\Helpers\controllers\CrudController::findModel()
     */
    protected function findModel($condition = null, $actionID = null, $contextMap = null)
    {
        return \Yii::$app->getUser()->getIdentity();
    }

}
