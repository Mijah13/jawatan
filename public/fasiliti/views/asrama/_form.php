<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\JenisAsrama;
use app\models\PenginapKategori;
use app\controllers\StatusAsramaLog;

use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Asrama $model */
/** @var yii\widgets\ActiveForm $form */
$this->registerCssFile('@web/css/tempah.css');

$statusAsrama  = ['Kosong', 'Sedang dibersihkan', 'Simpanan', 'Rosak', 'Risiko', 'Sedang dibaiki', 'Diisi', 'Separa diisi'];
$kelamin = ['Lelaki', 'Perempuan', 'Lelaki/Perempuan'];
// $penginapKategori = ['Penginap persendirian', 'Pelajar'];
// $penginapKategori = ArrayHelper::map(PenginapKategori::find()->all(), 'id', 'jenis_penginap'); // 'nama_kategori' depends on your actual column
$penginapKategori = ArrayHelper::map(
    PenginapKategori::find()->where(['in', 'id', [1, 2]])->all(),
    'id',
    'jenis_penginap'
);
?>

<div class="asrama-index">

    <div class="card shadow p-3 mb-5 bg-white rounded">
        
        <div class="card-body">
        <h1><?= Html::encode($this->title) ?></h1>
            <div class="asrama-form">

            <?php $form = ActiveForm::begin(); ?>
            <div class="form-row">
                <div class="form-column">
                    <?= $form->field($model, 'blok')->textInput(['class' => 'custom-input']) ?>
                    <?= $form->field($model, 'no_asrama')->textInput(['class' => 'custom-input']) ?>
                
                    <?= $form->field($model, 'jenis_asrama_id')->dropDownList(
                        ArrayHelper::map(JenisAsrama::find()->all(), 'id', 'jenis_bilik'),['class' => 'custom-input', 'prompt' => 'Pilih Jenis Bilik']) 
                    ?>
                    <?= $form->field($model, 'penginap_kategori_id')->dropDownList(
                        $penginapKategori,
                        ['class' => 'custom-input', 'prompt' => 'Pilih Kategori Penginap']
                    ) ?>
                </div>

                <div class="form-column">
                    <?= $form->field($model, 'aras')->textInput(['class' => 'custom-input']) ?>
                    <?= $form->field($model, 'status_asrama')->dropDownList(
                        $statusAsrama , ['class' => 'custom-input', 'prompt' => 'Pilih Status']) 
                    ?>

                    <?= $form->field($model, 'kelamin')->dropDownList(
                        $kelamin, ['class' => 'custom-input', 'prompt' => 'Pilih Jenis Kelamin']) 
                    ?>
                    <?= $form->field($model, 'kapasiti')->dropDownList(
                        [1 => 1, 2 => 2],
                        ['class' => 'custom-input', 'prompt' => 'Pilih kapasiti']
                    ) ?>

                </div>
            </div>

                <div class="form-group text-center">
                    <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
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