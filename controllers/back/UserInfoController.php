<?php

namespace thinker_g\UserAuth\controllers\back;

use thinker_g\UserAuth\controllers\BaseAdminController;

/**
 * UserInfoController implements the CRUD actions for UserInfo model.
 */
class UserInfoController extends BaseAdminController
{
    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * Lists all UserInfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        return parent::actionIndex();
    }

    /**
     * Displays a single UserInfo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return parent::actionView($id);
    }

    /**
     * Creates a new UserInfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        return parent::actionCreate();
    }

    /**
     * Updates an existing UserInfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        return parent::actionUpdate($id);
    }

    /**
     * Deletes an existing UserInfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        return parent::actionDelete($id);
    }

    /**
     * Finds the UserInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     */
    protected function findModel($id)
    {
        return parent::findModel($id);
    }
}
