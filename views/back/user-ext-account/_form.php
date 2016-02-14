<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model thinker_g\UserAuth\models\ars\UserExtAccount */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-ext-account-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput($model->user_id ? ['readonly' => 'readonly'] : []) ?>

    <?= $form->field($model, 'from_source')->dropDownList($model::availableSources()) ?>

    <?= $form->field($model, 'access_token')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'open_uid')->textInput() ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
