<?php
namespace thinker_g\UserAuth\controllers\front;

use Yii;
use thinker_g\UserAuth\controllers\BaseAuthController;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;

/**
 * Frontend auth controller, provides in addition the password reset actions.
 *
 * @author Thinker_g
 */
class AuthController extends BaseAuthController
{
    /**
     * @inheritdoc
     * @var string
     */
    public $defaultAction = 'login';

    /**
     * Signup action.
     */
    public function actionSignup()
    {
        $model = Yii::createObject($this->module->modelSignupForm);
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render($this->viewID, [
            'model' => $model,
        ]);
    }

    /**
     * Request password reset action.
     * @return \yii\web\Response|string
     */
    public function actionRequestPasswordReset()
    {
        $model = Yii::createObject($this->module->modelPasswordResetRequestForm);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash(
                    'error',
                    'Sorry, we are unable to reset password for the provided email.'
                );
            }
        }

        return $this->render($this->viewID, [
            'model' => $model,
        ]);
    }

    /**
     * Reset password action
     * @param string $token
     * @throws BadRequestHttpException
     * @return \yii\web\Response|string
     */
    public function actionResetPassword($token)
    {
        try {
            if (is_string($this->module->modelResetPasswordForm)) {
                $this->module->modelResetPasswordForm = [
                    'class' => $this->module->modelResetPasswordForm
                ];
            }
            $this->module->modelResetPasswordForm['token'] = $token;
            $model = Yii::createObject($this->module->modelResetPasswordForm);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'Your password has been successfully reset.');

            return $this->goHome();
        }

        return $this->render($this->viewID, [
            'model' => $model,
        ]);
    }
}

?>