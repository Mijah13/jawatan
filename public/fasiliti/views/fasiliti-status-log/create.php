<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\FasilitiStatusLog $model */

$this->title = 'Create Fasiliti Status Log';
$this->params['breadcrumbs'][] = ['label' => 'Fasiliti Status Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fasiliti-status-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
