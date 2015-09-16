<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model thinker_g\UserAuth\models\ars\UserInfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-info-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'is_male')->textInput() ?>

    <?= $form->field($model, 'dob')->textInput() ?>

    <?= $form->field($model, 'board_type')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ski_age')->textInput(['maxlength' => 32]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
