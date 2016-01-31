<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'Try Authentication';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::a('Authenticate via ' . $adaptor->id, $adaptor->getAuthUrl(Yii::$app->request->getCsrfToken()), ['class' => 'btn btn-primary']) ?></p>

</div>
