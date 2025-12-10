<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\FasilitiStatusLog $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="fasiliti-status-log-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fasiliti_id')->textInput() ?>

    <?= $form->field($model, 'fasiliti_status')->textInput() ?>

    <?= $form->field($model, 'tarikh_mula')->textInput() ?>

    <?= $form->field($model, 'tarikh_tamat')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
