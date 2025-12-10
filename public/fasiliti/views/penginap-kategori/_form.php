<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PenginapKategori $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="penginap-kategori-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_penginap')->textInput() ?>

    <?= $form->field($model, 'jenis_penginap')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
