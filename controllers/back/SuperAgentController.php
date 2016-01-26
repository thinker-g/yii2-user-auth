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
use yii\web\NotFoundHttpException;

/**
 * SuperAgentController implements the CRUD actions for UserExtAccount model.
 */
class SuperAgentController extends BaseAdminController
{

    public $controllerMvMap = [
        [
            'model' => 'thinker_g\UserAuth\models\ars\SuperAgentAccount',
            'search' => 'thinker_g\UserAuth\models\ars\SuperAgentAccountSearch',
        ]
    ];

    /**
     * Finds the UserExtAccount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string|array $condition this will always be overridden by primary key and available agent types.
     * @return User the loaded model
     */
    protected function findModel($condition = null, $actionID = null, $contextMap = null)
    {
        $condition = static::getRequestedPk($this->getModelClass());
        $model = parent::findModel($condition, $actionID, $contextMap);
        /* $model = $modelClass::findOne([
            $modelClass::primaryKey()[0] => $condition,
            'from_source' => $modelClass::$availableSources
        ]); */
        if (!is_null($model)) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
