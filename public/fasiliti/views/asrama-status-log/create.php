<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AsramaStatusLog $model */

$this->title = 'Create Asrama Status Log';
$this->params['breadcrumbs'][] = ['label' => 'Asrama Status Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asrama-status-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
