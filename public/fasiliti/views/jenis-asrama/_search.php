<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\JenisAsramaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="jenis-asrama-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'jenis_bilik') ?>

    
    <?= $form->field($model, 'deskripsi') ?> 

    <?= $form->field($model, 'kadar_sewa') ?>

    <?= $form->field($model, 'asrama_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
