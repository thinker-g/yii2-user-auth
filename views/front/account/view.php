<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model thinker_g\UserAuth\models\ars\User */

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

    <div class="panel panel-default">
        <?= DetailView::widget([
            'options' => [
                'class' => 'table table-striped table-condensed detail-view',
            ],
            'model' => $model,
            'attributes' => [
                'id',
                'username',
                'primary_email:email',
                [
                    'attribute' => 'status',
                    'value' => isset($model::availableStatus()[$model->status]) ? $model::availableStatus()[$model->status] : $model->status,
                ],
                [
                    'attribute' => 'created_at',
                    'label' => 'Registered',
                ],
                'last_login_at',
            ],
        ]) ?>
        <div class="panel-footer">
            <span class="btn-group">
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Logout'), ['auth/logout', 'id' => $model->id], [
                    'class' => 'btn btn-primary',
                    'data' => [
                        'method' => 'post',
                        'confirm' => \Yii::t('app', 'Are you sure you want to logout?'),
                    ]
                ]) ?>
            </span>
        </div><!-- $.panel-footer -->
    </div><!-- $.pandel.panel-default -->

    <?php if ($model->hasProperty('userInfo')): // Display user info if defined. ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Additional Info: </strong>
            <span class="btn-group">
                <?php if ($model->userInfo): ?>
                    <?= Html::a(Yii::t('app', 'Update'), [
                        '/' . $this->context->module->uniqueId . '/user-info/update',
                        'user_id' => $model->userInfo->primaryKey
                    ], [
                        'class' => 'btn btn-primary'
                    ]) ?>
                <?php else: ?>
                    <?= Html::a(Yii::t('app', 'Create Additional Info'), [
                        '/' . $this->context->module->uniqueId . '/user-info/create',
                        'UserInfo[user_id]' => $model->primaryKey
                    ], [
                        'class' => 'btn btn-success'
                    ]) ?>
                <?php endif; ?>
            </span>
        </div><!-- $.panel-heading -->
        <?php $model->userInfo && print(DetailView::widget([
            'model' => $model->userInfo,
            'attributes' => [
                'user_id',
                'is_male',
                'dob',
                'board_type:ntext',
                'ski_age',
            ],
        ])); ?>
    </div><!-- $.pandel.panel-default -->
    <?php endif; // <End> Display user info if defined. ?>

    <?php if ($model->hasProperty('userExtAccounts')): // Display External User Account if defined ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>External Account: </strong>
            <span class="btn-group">
                <?= Html::a(Yii::t('app', 'Create New'), [
                    '/' . $this->context->module->uniqueId . '/user-ext-account/create',
                    'UserExtAccount[user_id]' => $model->primaryKey
                ], [
                    'class' => 'btn btn-success'
                ]) ?>
                <?= Html::a(Yii::t('app', 'Grant Super Agent account'), [
                    '/' . $this->context->module->uniqueId . '/super-agent/create',
                    'SuperAgentAccount[user_id]' => $model->primaryKey
                ], [
                    'class' => 'btn btn-info'
                ]) ?>
            </span>
        </div><!-- $.panel-heading -->
        <?= GridView::widget([
            'layout' => "{pager}\n{items}\n{summary}",
            'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'margin-bottom: 0;'],
            'summaryOptions' => ['class' => 'panel-footer'],
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $model->userExtAccounts,
                'key' => 'user_id'
            ]),
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'from_source:raw:Acct. Type',
                'ext_user_id',
                'email:email',
                'created_at',
                'updated_at',
                [
                    'class' => 'yii\grid\ActionColumn', 'header'  => Yii::t('app', 'Actions'),
                    'urlCreator' => function($action, $model, $key, $index) {
                        return Url::toRoute([
                            '/' . $this->context->module->uniqueId . '/user-ext-account/' . $action,
                            'id' => $model->id
                        ]);
                    }
                ],
            ]
        ]) ?>
    </div><!-- $.pandel.panel-default -->
    <?php endif; // <End> Display External User Account if defined ?>

</div>
