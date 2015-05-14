<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model thinker_g\UserAuth\models\UserExtAccount */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'User Ext Account',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', $this->context->module->name),
    'url' => ['/' . $this->context->module->uniqueId]
];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Ext Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-ext-account-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if ($model->user): ?>
        <h3>Owner user info:</h3>
        <?= DetailView::widget([
            'model' => $model->user,
            'attributes' => [
                'id',
                'username',
                'primary_email',
                'password_hash',
                [
                    'attribute' => 'status',
                    'value' => $model->user->availableStatus()[$model->user->status]
                ],
                'created_at',
                'last_login_at'
            ]
        ]) ?>
    <?php endif; ?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
