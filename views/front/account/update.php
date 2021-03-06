<?php

use yii\helpers\Html;

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model thinker_g\UserAuth\models\ars\User */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'User',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', $this->context->module->name),
    'url' => ['/' . $this->context->module->uniqueId]
];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Account'), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Edit');
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="user-form">
    
        <?php $form = ActiveForm::begin(); ?>
    
        <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>
    
        <?= $form->field($model, 'primary_email')->textInput(['maxlength' => 255]) ?>
    
        <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255]) ?>
    
        <?php
            $statusField = $form->field($model, 'status');
            is_null($model->status) && ($model->status = $model::$defaultStatusCode);
            ($model->id == Yii::$app->getUser()->id) && $statusField->hintOptions['class'] = ' text-danger';
        ?>
        <?= $statusField->dropDownList($model->availableStatus()) ?>
    
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>
    
        <?php ActiveForm::end(); ?>
    
    </div><!-- $.user-form -->

</div>
