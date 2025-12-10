<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\FasilitiSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="fasiliti-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nama_fasiliti') ?>

    <?= $form->field($model, 'deskripsi') ?>

    <?= $form->field($model, 'kadar_sewa_perJam') ?>

    <?= $form->field($model, 'kadar_sewa_perHari') ?>

    <?php // echo $form->field($model, 'kadar_sewa_perJamSiang') ?>

    <?php // echo $form->field($model, 'kadar_sewa_perJamMalam') ?>

    <?php // echo $form->field($model, 'fasiliti_status') ?>

    <?php // echo $form->field($model, 'gambar') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
