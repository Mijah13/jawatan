<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\JenisAsrama $model */

$this->title = 'MyFasiliti';
// $this->params['breadcrumbs'][] = ['label' => 'Jenis Asrama', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="jenis-asrama-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
