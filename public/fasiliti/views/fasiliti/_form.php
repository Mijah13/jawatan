<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Fasiliti $model */
/** @var yii\widgets\ActiveForm $form */
$this->registerCssFile('@web/css/tempah.css');
$statusFasiliti = ['Kosong', 'disimpan', 'rosak', 'sedang dibaiki', 'Diisi'];
?>

<div class="fasiliti-form">
    <div class="card shadow p-3 mb-5 bg-white rounded">
        <div class="card-body">
        <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
     <!-- Row start -->
     <div class="row">
        <!-- First column -->
        <div class="col-md-6">
            <?= $form->field($model, 'nama_fasiliti')->textInput(['class' => 'custom-input']) ?>
            <?= $form->field($model, 'kadar_sewa_perJam')->textInput(['class' => 'custom-input']) ?>
            <?= $form->field($model, 'kadar_sewa_perJamSiang')->textInput(['class' => 'custom-input']) ?>
            <?= $form->field($model, 'fasiliti_status')->dropDownList(
                $statusFasiliti, ['class' => 'custom-input', 'prompt' => 'Pilih Status', 'class' => 'custom-input']) 
            ?>
            <?= $form->field($model, 'akses_pengguna')->dropDownList(
                [0 => 'Semua Pengguna', 1 => 'Pengguna Dalaman Sahaja'],
                ['class' => 'custom-input', 'prompt' => 'Pilih Akses Pengguna']
            ) ?>

        </div>
         <!-- Second column -->
         <div class="col-md-6">
            <?= $form->field($model, 'deskripsi')->textarea(['class' => 'custom-input', 'rows' => 3]) ?>
            <?= $form->field($model, 'kadar_sewa_perHari')->textInput(['class' => 'custom-input']) ?>
            <?= $form->field($model, 'kadar_sewa_perJamMalam')->textInput(['class' => 'custom-input']) ?>
            
            <div class="col-sm-10">
            <?= $form->field($model, 'imej')->fileInput([
                'id' => 'document_doc_file',
                'class' => 'form-control',
                'accept' => '.png, .jpeg, .jpg, .doc, .docx, .pdf',
            ]) ?>

            <div id="document_doc_file_help" class="form-text mb-0 help-text">
                Max 8MB per file (PNG, JPEG, JPG). Multiple files allowed.
            </div>

        </div>
    </div>

    <div class="form-group text-center">
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

