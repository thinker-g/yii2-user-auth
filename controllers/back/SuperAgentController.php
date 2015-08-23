<?php
namespace thinker_g\UserAuth\controllers\back;

use thinker_g\UserAuth\controllers\BaseAdminController;
use yii\web\NotFoundHttpException;

/**
 * SuperAgentController implements the CRUD actions for UserExtAccount model.
 */
class SuperAgentController extends BaseAdminController
{

    /**
     * Finds the UserExtAccount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     */
    protected function findModel($id)
    {
        if (is_array($modelClass = $this->getModelClass(static::KEY_MODEL))) {
            $modelClass = $modelClass['class'];
        }
        $model = $modelClass::findOne([
            $modelClass::primaryKey()[0] => $id,
            'from_source' => $modelClass::availableSources()
        ]);
        if (!is_null($model)) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
