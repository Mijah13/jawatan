<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AsramaStatusLog $model */

$this->title = 'Update Asrama Status Log: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Asrama Status Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="asrama-status-log-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
