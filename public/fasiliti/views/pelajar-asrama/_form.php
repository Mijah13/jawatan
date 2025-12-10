<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use app\models\Asrama;
use app\models\JenisAsrama;
use app\models\PenginapKategori;
use yii\web\UploadedFile;

/** @var yii\web\View $this */
/** @var app\models\PelajarAsrama $model */
/** @var yii\widgets\ActiveForm $form */
// Register external CSS file
$this->registerCssFile('@web/css/tempah.css');
// $this->registerJsFile('https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.js', ['depends' => 'yii\web\JqueryAsset']);


$status = ['Bujang', 'Berkahwin'];
$jantina = ['Lelaki', 'Perempuan'];
$statusAsrama = ['Kosong', 'Sedang dibersihkan', 'Simpanan', 'Rosak', 'Risiko', 'Sedang dibaiki'];

?>
<?php
$user = \app\models\User::findOne(Yii::$app->user->id);
?>

<div class="tempah-asrama-page">

    
    <div class="tempah-asrama-form">
        <div class="booking-form">
            <?php $form = ActiveForm::begin(
                [
                    'options' => ['enctype' => 'multipart/form-data'], // Required for file uploads
                    'fieldConfig' => [
                    'errorOptions' => ['class' => 'text-danger'],
                ],
                ]
            ); ?>

                <div class="form-group">
                    <?php if ($model->user): ?>
                    <div class="form-group">
                        <label for="user-nama">Nama</label>
                        <input type="text" id="user-nama" class="form-control custom-input" 
                            value="<?= Html::encode($model->user->nama) ?>">
                    </div>
                <?php endif; ?>

                </div>
                <?= $form->field($model, 'no_kp')->textInput(['class' => 'custom-input']) ?>
                <?= $form->field($model, 'no_tel')->textInput(['class' => 'custom-input']) ?>
                <?= $form->field($model, 'jantina')->dropDownList(
                    $jantina, ['class' => 'custom-input', 'prompt' => 'Pilih Jantina', 'class' => 'custom-input']) 
                ?>
                <?= $form->field($model, 'alamat')->textarea(['class' => 'custom-input', 'rows' => 2]) ?>
        
                <?= $form->field($model, 'kod_kursus')->dropDownList([
                    'Tvet i' => 'Tvet i',
                    'Tvet i Khas' => 'Tvet i Khas',
                    'DLPV' => 'DLPV',
                    'DPV' => 'DPV',
                ], ['class' => 'custom-input', 'prompt' => 'Pilih Kod Kursus']) ?>

                <?= $form->field($model, 'sesi_batch')->dropDownList([
                    'Sesi 1' => 'Sesi 1',
                    'Sesi 2' => 'Sesi 2',
                    'Sesi 3' => 'Sesi 3',
                ], ['class' => 'custom-input', 'prompt' => 'Pilih Sesi Batch']) ?>

                <?= $form->field($model, 'status')->dropDownList(
                    $status, ['class' => 'custom-input', 'prompt' => 'Pilih Status', 'class' => 'custom-input', 'disabled' => Yii::$app->user->identity->role == 3]) 
                ?>
		
		        <?= $form->field($model, 'no_waris')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'hubungan')->textInput(['maxlength' => true]) ?>

                <?php if (!$model->isNewRecord && in_array(Yii::$app->user->identity->role, [0, 1, 6])): ?>
                    <?= $form->field($model, 'status_penginapan')->dropDownList([
                            0 => 'Menginap di Asrama',
                            1 => 'Tinggal di Luar (Diberi Kelulusan)',
                        ], [
                            'prompt' => '-- Pilih Status Penginapan --',
                            'class' => 'custom-input',
                            'id' => 'statusPenginapan'
                        ]) ?>
                 <?php endif; ?>

                <?php if (!$model->isNewRecord && in_array(Yii::$app->user->identity->role, [0, 1, 6])): ?>
                    <?= $form->field($model, 'id_asrama')
                        ->label('Bilik Asrama') // <-- ini tukar label
                        ->dropDownList(
                            ArrayHelper::map(
                                Asrama::find()->all(),
                                'id',
                                function ($model) {
                                    return "{$model->blok}{$model->aras}{$model->no_asrama}";
                                }
                            ),
                            [
                                'prompt' => 'Pilih No Bilik',
                                'class' => 'custom-input',
                                'id' => 'dropdownAsrama'
                            ]
                        )
                    ?>
                <?php endif; ?>
                
                </div>
            </div>
            </div>
            <div class="form-group mt-3"  style="text-align: center;">
                <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
                <?= Html::resetButton('Reset', [
                    'class' => 'btn btn-secondary',
                    'onclick' => 'startDateSelected = false; endDateSelected = false; return true;'
                ]) ?>
            </div>
            
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    
<!-- Modal -->
<div class="modal fade" id="modalTempahanBerjaya" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tempahan Berjaya</h5>
      </div>
      <div class="modal-body">
        Data tempahan anda telah disimpan.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="confirmRedirect" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

</div>


<?php
$this->registerJs(<<<JS
$('#statusPenginapan').on('change', function () {
    if (parseInt($(this).val()) === 1) {
        $('#dropdownAsrama').val('').prop('disabled', true);
    } else {
        $('#dropdownAsrama').prop('disabled', false);
    }
});
JS);



$this->registerCss("


.modal {
    z-index: 9999 !important; /* Pastikan modal di atas elemen lain */
}

.modal-backdrop {
    z-index: 9998 !important; /* Pastikan backdrop juga ada di bawah modal */
}

.modal-content {
    border-radius: 12px; /* Lebih rounded */
    border: none;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Shadow bagi nampak timbul */
}

.modal-header {
    background: #007bff; /* Warna biru Bootstrap */
    color: white;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.modal-body {
    font-size: 16px;
    text-align: center;
    padding: 20px;
}



.modal-footer {
    justify-content: center;
}

.btn-ok {
    background: #28a745; /* Hijau success */
    color: white;
    border-radius: 8px;
    padding: 8px 20px;
    transition: 0.3s;
}

.btn-ok:hover {
    background: #218838; /* Warna hijau lebih pekat bila hover */
}

#dropdownAsrama:disabled {
    background-color: #e9ecef;
    cursor: not-allowed;
}



");

?>