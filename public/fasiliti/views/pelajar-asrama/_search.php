<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PelajarAsramaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pelajar-asrama-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'id_asrama') ?>

    <?php // echo $form->field($model, 'tarikh_masuk') ?>

    <?php // echo  $form->field($model, 'tarikh_keluar') ?>

    <?php // echo $form->field($model, 'tarikh_pembersihan') ?>

    <?php // echo $form->field($model, 'no_kp') ?>

    <?php // echo $form->field($model, 'no_tel') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'kod_kursus') ?>

    <?php // echo $form->field($model, 'sesi_batch') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'jantina') ?>

    <?php // echo $form->field($model, 'alamat') ?>

    <?php // echo $form->field($model, 'jenis_bilik') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
