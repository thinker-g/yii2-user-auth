<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel thinker_g\UserAuth\models\UserExtAccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Super Agents');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', $this->context->module->name),
    'url' => ['/' . $this->context->module->uniqueId]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-ext-account-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="btn-group">
        <?= Html::a(Yii::t('app', 'Add Super Agent'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'username',
                'label' => 'Username',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->user) {
                        return Html::a($model->user->username, ['user/view', 'id' => $model->user_id]);
                    } else {
                        return;
                    }
                }
            ],
            [
                'attribute' => 'primary_email',
                'format' => 'email',
                'label' => 'Primary email',
                'value' => 'user.primary_email'
            ],
            // 'email:email:Account Email',
            'created_at',
            'updated_at',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
