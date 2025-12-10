<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\JenisAsrama $model */
/** @var yii\widgets\ActiveForm $form */
?>
<div class="card shadow p-3 mb-5 bg-white rounded">
    <div class="card-body">
        <h1><?= Html::encode($this->title) ?></h1>

        <div class="jenis-asrama-form">
            <?php $form = ActiveForm::begin(); ?>

            <!-- Row start -->
            <div class="row">
                <!-- First column -->
                <div class="col-md-6">
                    <?= $form->field($model, 'jenis_bilik')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'kadar_sewa')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'akses_pengguna')->dropDownList(
                        [0 => 'Semua Pengguna', 1 => 'Pengguna Dalaman Sahaja'],
                        ['prompt' => 'Pilih Akses Pengguna']
                    ) ?>

                </div>

                <!-- Second column -->
                <div class="col-md-6">
                    <?= $form->field($model, 'deskripsi')->textarea(['rows' => 1]) ?> 

                    <div class="col-sm-12">
                        <?= $form->field($model, 'imej[]')->fileInput([
                            'id' => 'document_doc_file',
                            'class' => 'form-control',
                            'accept' => '.png, .jpeg, .jpg, .doc, .docx, .pdf',
                            'multiple' => true, // Allow multiple file uploads
                        ]) ?>

                        <div id="document_doc_file_help" class="form-text mb-0 help-text">
                            Max 8MB per file (PNG, JPEG, JPG). Multiple files allowed.
                        </div>

                        <!-- Container to show selected file names -->
                        <ul id="file-list" class="mt-2"></ul>
                    </div>
                </div>
            </div> <!-- Row end -->

            <!-- Display existing images -->
            <?php if (!empty($model->gambar)): ?>
                <div class="form-group mt-3 text-center">
                    <label class="d-block mb-2"><strong>Gambar Sedia Ada</strong></label>
                    <?php $existingImages = json_decode($model->gambar, true); ?>
                    <div class="row">
                        <?php foreach ($existingImages as $image): ?>
                            <div class="col-md-3 text-center">
                            <img src="<?= Yii::getAlias('@web') . "/images/" . $image ?>" 
                                alt="Existing Image" 
                                class="img-thumbnail" 
                                style="width: 250px; height: auto;">
                                <div>
                                    <label>
                                        <input type="checkbox" name="remove_images[]" value="<?= $image ?>"> Padam
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="form-group text-center mt-4">
                <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


<?php
$this->registerJs('
document.getElementById("document_doc_file").addEventListener("change", function(event) {
    let fileList = document.getElementById("file-list");
    fileList.innerHTML = ""; // Clear previous list

    for (let file of event.target.files) {
        let listItem = document.createElement("li");
        listItem.textContent = file.name;
        fileList.appendChild(listItem);
    }
});

');
$this->registerCss('

     h1 {
            font-size: 1.5rem; /* Adjust size */
            font-weight: bold; /* Make it bold */
            color:rgb(32, 42, 111); /* Text color */
            margin-bottom: 25px;
           
        }
    ');

$this->registerCss("

.file-upload-area {
    border: 2px dashed #d3d3d3;
    border-radius: 5px;
    text-align: center;
    padding: 40px 80px;
    background-color: #f9f9f9;
    color: #6c757d;
    cursor: pointer;
    transition: border-color 0.3s ease;
}
.file-upload-area:hover {
    border-color: #007bff;
}
.file-upload-area i {
    font-size: 48px;
    color: #007bff;
    margin-bottom: 10px;
}
.file-upload-area .title {
    font-weight: 600;
    font-size: 18px;
    margin-bottom: 5px;
}
.file-upload-area .subtitle {
    font-size: 14px;
    color: #6c757d;
}
.file-details {
    margin-top: 10px;
    font-size: 14px;
    color: #245, 5, 5;
}


");

?>