<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\base\Object;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model thinker_g\UserAuth\models\User An empty model for invoking static methods. */
/* @var $stat array An 2D array, in which each "line" is an array indexed by status code. */

$this->title = Yii::t('app', $this->context->module->name);
$this->params['breadcrumbs'][] = $this->title;

rsort($stats);
foreach ($stats as &$entry) {
    if (isset($model->availableStatus()[$entry['status']])) {
        $entry['status'] = lcfirst($model->availableStatus()[$entry['status']]);
    }
}
?>
<div class="user-auth-default-index">
    <h1><?= $this->title ?> <small>Route: [<?= $this->context->action->uniqueId ?>]</small></h1>
    <p><span class="label label-danger">Backend console</span></p>
    <p>
        This is the console of your system's <span class="label label-info"><?= $this->context->module->name ?></span> module.
    </p>
    <div class="panel panel-default">
        <div class="panel-heading">User statistics</div>
        <?php if(!is_null($stats)): ?>
            <?= GridView::widget([
                'dataProvider' => new ArrayDataProvider(['allModels' => $stats]),
                'summary' => '',
                'tableOptions' => ['class' => 'table table-striped tabel-condensed', 'style' => 'margin: 0;']
            ]) ?>
        <?php else: ?>
            <p class="panel-body text-danger"><?= Yii::t('app', 'Statistic method unavailable. Method [[getStatsByStatus()]] not found in "User" model.') ?></p>
        <?php endif; ?>
    </div>
    <p class="btn-group">
        <?= Html::a('Manage Users', ['/' . $this->context->module->uniqueId . '/user'], ['class' => 'btn btn-sm btn-primary']) ?>
    </p>
</div>
