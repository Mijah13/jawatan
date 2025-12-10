<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Info $model */
/** @var yii\widgets\ActiveForm $form */
?>
<div class="card shadow p-3 mb-5 bg-white rounded">
    <div class="card-body">
        <h1><?= Html::encode($this->title) ?></h1>

        <div class="info-form">

    <?php $form = ActiveForm::begin(); ?>

     <!-- Row start -->
        <div class="row">
            <!-- First column -->
            <div class="col-md-6">
                <?= $form->field($model, 'tajuk')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'aktif')->dropDownList([
                    1 => 'Aktif',
                    0 => 'Tidak Aktif'
                ], ['prompt' => 'Pilih Status']) ?>
                
		   
            </div>

            <!-- Second column -->
            <div class="col-md-6">
                <?= $form->field($model, 'keterangan')->textarea(['rows' => 4]) ?>

                <!-- <?= $form->field($model, 'created_at')->textInput() ?>

                <?= $form->field($model, 'updated_at')->textInput() ?> -->
            </div>
        </div> 
        <!-- Row end -->

    <div class="form-group text-center mt-4">
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
<?php
$this->registerCss('

     h1 {
            font-size: 1.5rem; /* Adjust size */
            font-weight: bold; /* Make it bold */
            color:rgb(32, 42, 111); /* Text color */
            margin-bottom: 25px;
           
        }
    ');
    ?>
