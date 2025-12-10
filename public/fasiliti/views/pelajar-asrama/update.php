<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PelajarAsrama $model */

$this->title = 'Update Pelajar Asrama: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pelajar Asramas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pelajar-asrama-update">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
