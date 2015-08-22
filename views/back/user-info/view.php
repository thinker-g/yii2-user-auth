<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model thinker_g\UserAuth\models\ars\UserInfo */

$this->title = 'User info: [' . $model->user_id . ']';
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', $this->context->module->name),
    'url' => ['/' . $this->context->module->uniqueId]
];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Infos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-info-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="btn-group">
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'user_id' => $model->user_id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'user_id' => $model->user_id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </span>
        </div><!-- $.panel-heading -->
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'user_id',
                'is_male',
                'dob',
                'board_type:ntext',
                'ski_age',
            ],
        ]) ?>
        <div class="panel-heading"><strong>Belongs to user: </strong></div>
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

</div>
