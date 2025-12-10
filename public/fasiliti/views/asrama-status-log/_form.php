<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AsramaStatusLog $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="asrama-status-log-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_asrama')->textInput() ?>

    <?= $form->field($model, 'tarikh_mula')->textInput() ?>

    <?= $form->field($model, 'tarikh_tamat')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
