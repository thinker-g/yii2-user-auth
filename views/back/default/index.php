<?php
use yii\helpers\Html;
$this->title = Yii::t('app', $this->context->module->name);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-auth-default-index">
    <h1><?= $this->title ?> <small>Route: [<?= $this->context->action->uniqueId ?>]</small></h1>
    <p><span class="label label-danger">Backend console</span></p>
    <p>
        This is the console of your system's <span class="label label-info"><?= $this->context->module->name ?></span> module.
    </p>
    <p class="btn-group">
        <?= Html::a('Manage Users', ['/' . $this->context->module->uniqueId . '/user'], ['class' => 'btn btn-sm btn-primary']) ?>
    </p>
</div>
