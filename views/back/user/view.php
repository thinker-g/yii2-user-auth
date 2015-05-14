<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model thinker_g\UserAuth\models\User */

$this->title = "User [{$model->id}]: $model->primary_email";
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', $this->context->module->name),
    'url' => ['/' . $this->context->module->uniqueId]
];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

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
            'username',
            'primary_email:email',
            'password_hash',
            'status',
            'auth_key',
            'password_reset_token',
            'created_at',
            'last_login_at',
        ],
    ]) ?>

    <?php if ($model->userInfo): ?>
        <h2>Additional Info:
            <span class="btn-group">
                <?= Html::a(Yii::t('app', 'Update'), [
                    '/' . $this->context->module->uniqueId . '/user-info/update',
                    'id' => $model->userInfo->primaryKey
                ], [
                    'class' => 'btn btn-primary'
                ]) ?>
                <?= Html::a(Yii::t('app', 'Delete'), [
                    '/' . $this->context->module->uniqueId . '/user-info/delete',
                    'id' => $model->userInfo->primaryKey
                ], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </span>
        </h2>
        <?= DetailView::widget([
            'model' => $model->userInfo,
            'attributes' => [
                'user_id',
                'is_male',
                'dob',
                'board_type:ntext',
                'ski_age',
            ],
        ]) ?>
    <?php else: ?>
        <h2>Additional Info: 
            <span class="btn-group">
                <?= Html::a(Yii::t('app', 'Create Additional Info'), [
                    '/' . $this->context->module->uniqueId . '/user-info/create',
                    'UserInfo[user_id]' => $model->primaryKey
                ], [
                    'class' => 'btn btn-success'
                ]) ?>
            </span>
        </h2>
    <?php endif; ?>

    <h2>External Account:
        <span class="btn-group">
            <?= Html::a(Yii::t('app', 'Create New'), [
                '/' . $this->context->module->uniqueId . '/user-ext-account/create',
                'UserExtAccount[user_id]' => $model->primaryKey
            ], [
                'class' => 'btn btn-success'
            ]) ?>
            <?php if (!$model->superAgentAcct): ?>
                <?= Html::a(Yii::t('app', 'Grant Super Agent account'), [
                    '/' . $this->context->module->uniqueId . '/super-agent/create',
                    'SuperAgentAccount[user_id]' => $model->primaryKey
                ], [
                    'class' => 'btn btn-info'
                ]) ?>
            <?php endif; ?>
        </span>
    </h2>

    <?= GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $model->userExtAccounts,
            'key' => 'user_id'
        ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'from_source',
            'ext_user_id',
            'email:email',
            'created_at',
            'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function($action, $model, $key, $index) {
                    return Url::toRoute([
                        '/' . $this->context->module->uniqueId . '/user-ext-account/' . $action,
                        'id' => $model->id
                    ]);
                }
            ],
        ]
    ]) ?>
</div>
