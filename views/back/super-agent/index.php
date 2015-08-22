<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel thinker_g\UserAuth\models\ars\UserExtAccountSearch */
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
        'filterModel' => $searchModel, //@todo filter not working correctly
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',

            [
                'attribute' => 'username',
                'label' => 'Owner',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->user) {
                        return Html::a($model->user->username, ['user/view', 'id' => $model->user_id]);
                    } else {
                        return;
                    }
                }
            ],
            'from_source:ntext:Agent Type',
            'email:email:Account Email',
            'created_at',
            ['class' => 'yii\grid\ActionColumn', 'header'  => Yii::t('app', 'Actions')],
        ],
    ]); ?>

</div>
