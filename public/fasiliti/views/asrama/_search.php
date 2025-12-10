<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AsramaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="asrama-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'blok') ?>

    <?= $form->field($model, 'aras') ?>

    <?= $form->field($model, 'no_asrama') ?>

    <?= $form->field($model, 'status_asrama') ?>

    <?php echo $form->field($model, 'jenis_asrama_id') ?>

    <?php echo $form->field($model, 'kelamin') ?>

    <?php // echo $form->field($model, 'jenis_asrama_id') ?>
		
    <?php // echo $form->field($model, 'penginap_kategori_id') ?>

    <?php // echo $form->field($model, 'kapasiti') ?>
  

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
