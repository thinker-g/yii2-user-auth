<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model thinker_g\UserAuth\models\UserExtAccount */

$this->title = "User external account [{$model->id}]";
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', $this->context->module->name),
    'url' => ['/' . $this->context->module->uniqueId]
];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Ext Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-ext-account-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="btn-group">
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            'from_source',
            'access_token',
            'ext_user_id',
            'email:email',
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <h1>Belongs to user:</h1>
    <?php if ($model->user): ?>
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
    <?php else: ?>
        <h3>None</h3>
    <?php endif; ?>

</div>
