<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\Pagination;

/* @var $this yii\web\View */
/* @var $searchModel thinker_g\UserAuth\models\ars\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', $this->context->module->name),
    'url' => ['/' . $this->context->module->uniqueId]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="btn-group">
        <?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'primary_email:email',
            // 'password_hash',
            [
                'attribute' => 'status',
                'value' => function($model, $key, $index, $column) {
                    return isset($model->availableStatus()[$model->status])
                        ? $model::availableStatus()[$model->status] . ' [' . $model->status . ']'
                        : $model->status;
                }
            ],
            // 'auth_key',
            // 'password_reset_token',
            'created_at',
            // 'updated_at',
            'last_login_at',

            ['class' => 'yii\grid\ActionColumn', 'header' => Yii::t('app', 'Actions')],
        ],
    ]); ?>

</div>
