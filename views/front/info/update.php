<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model thinker_g\UserAuth\models\ars\UserInfo */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'User Info',
]) . ' ' . $model->user_id;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', $this->context->module->name),
    'url' => ['/' . $this->context->module->uniqueId]
];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Infos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_id, 'url' => ['view', 'id' => $model->user_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-info-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($model->user): ?>
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Owner user:</strong></div><!-- $.panel-heading -->
            <?= DetailView::widget([
                'model' => $model->user,
                'attributes' => [
                    'id',
                    'username',
                    'primary_email',
                    'password_hash',
                    [
                        'attribute' => 'status',
                        'value' => isset($model->user->availableStatus()[$model->user->status])
                            ? $model->user->availableStatus()[$model->user->status]
                            : $model->user->status,
                    ],
                    'created_at',
                    'updated_at',
                    'last_login_at'
                ]
            ]) ?>
        </div><!-- $.pandel.panel-default -->
    <?php endif; ?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
