<?php
use yii\helpers\Html;
$this->title = Yii::t('app', 'User Manager');
?>
<div class="user-auth-default-index">
    <h1><?= $this->title ?> <small>Route: [<?= $this->context->action->uniqueId ?>]</small></h1>
    <p><span class="label label-success">Frontend console</span></p>
    <p>
        This is the splash page of your system's <span class="label label-info"><?= $this->context->module->name ?></span> module.
    </p>
</div>
