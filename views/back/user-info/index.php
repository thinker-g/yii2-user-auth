<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel thinker_g\UserAuth\models\UserInfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'User Infos');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', $this->context->module->name),
    'url' => ['/' . $this->context->module->uniqueId]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-info-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="btn-group">
        <?= Html::a(Yii::t('app', 'Create User Info'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'user_id',
                'label' => 'User ID',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->user) {
                        return Html::a($model->user_id, ['user/view', 'id' => $model->user_id]);
                    } else {
                        return;
                    }
                }
            ],
            'is_male',
            'dob',
            'board_type:ntext',
            'ski_age',

            ['class' => 'yii\grid\ActionColumn', 'header'  => Yii::t('app', 'Actions')],
        ],
    ]); ?>

</div>
