<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\UploadedFile;


$this->title = 'Import Pelajar Asrama';
?>

<h3><?= Html::encode($this->title) ?></h3>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= $form->field($modelUpload, 'excelFile')->fileInput() ?>

<div class="form-group">
    <?= Html::submitButton('Import', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
