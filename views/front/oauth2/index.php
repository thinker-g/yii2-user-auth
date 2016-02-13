<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = '3rd Party Accounts';
$this->params['breadcrumbs'][] = $this->title;
is_array($adaptors) || $adaptors = [$adaptors];
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <table class="table table-striped table-condensed">
        <thead>
            <tr>
                <th>Account</th>
                <th>Operation</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($adaptors)): ?>
            <?php   foreach ($adaptors as $adaptor): ?>
            <tr>
                <td>
                    <?= Html::encode($adaptor->id)?>
                </td>
                <td>
                    <?= Html::a(
                        Yii::$app->user->isGuest ? 'Login' : 'Bind',
                        $adaptor->getAuthUrl(Yii::$app->request->getCsrfToken()), ['class' => 'btn btn-primary']
                    ) ?>
                </td>
            </tr>
            <?php   endforeach;?>
            <?php endif;?>
        </tbody>
    </table>
</div>
