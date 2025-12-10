<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use app\models\Asrama;
use app\models\JenisAsrama;
use app\models\PenginapKategori;
use app\models\Info;
use yii\web\UploadedFile;


/** @var yii\web\View $this */
/** @var app\models\TempahAsrama $model */
/** @var yii\widgets\ActiveForm $form */

// Register external CSS file
$this->registerCssFile('@web/css/tempah.css');
// $this->registerJsFile('https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.js', ['depends' => 'yii\web\JqueryAsset']);


$status = ['Bujang', 'Berkahwin'];
$jantina = ['Lelaki', 'Perempuan'];
$statusAsrama = ['Kosong', 'Sedang dibersihkan', 'Simpanan', 'Rosak', 'Risiko', 'Sedang dibaiki'];
if(Yii::$app->user->identity->role == 5) { //login as admin sistem/kemudahan
    $model->tujuan = "Pelajar CIAST";
    $model->jenis_penginap = 1;
    $model->agensi_pemohon = "-";
}
$role = Yii::$app->user->identity->role;

?>

<?php
// Ambil tarikh dari GET jika ada, kalau tak ada guna dari model
$tarikhMasuk = Yii::$app->request->get('tarikh_masuk', $model->tarikh_masuk);
$tarikhKeluar = Yii::$app->request->get('tarikh_keluar', $model->tarikh_keluar);


// Format tarikh kalau ada nilai
$tarikhMasukFormatted = $tarikhMasuk ? Yii::$app->formatter->asDate($tarikhMasuk, 'php:d-m-Y') : '';
$tarikhKeluarFormatted = $tarikhKeluar ? Yii::$app->formatter->asDate($tarikhKeluar, 'php:d-m-Y') : '';
?>


<div class="tempah-asrama-page">


<?php
// Retrieve the passed values from URL
$roomTypeId = Yii::$app->request->get('jenis_bilik'); // Guna 'jenis_bilik' dari URL
$roomType = JenisAsrama::findOne($roomTypeId); // Ambil data jenis bilik ikut ID

// Ambil semua info yang aktif
$infoAktif = Info::find()->where(['aktif' => 1])->all();
// Pastikan data wujud sebelum guna
$jenisBilik = $roomType ? $roomType->jenis_bilik : "Tidak Diketahui"; 
?>
        <!-- Display Room Details Inline and Centered -->
    <div class="room-highlight"> 
        <p><strong>Jenis Bilik:</strong> <?= Html::encode($jenisBilik) ?></p>
    </div>


<div class="info-section mt-4 mx-auto" style="max-width: 700px;">
    <?php foreach ($infoAktif as $info): ?>
        <div class="alert alert-warning border-left border-4 border-danger shadow-sm mb-3 p-3">
            <h5 class="fw-bold" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-align: center;">
                <?= Html::encode($info->tajuk) ?>
            </h5>
            <ul style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 15px;">
                <?php foreach (explode("\n", $info->keterangan) as $point): ?>
                    <?php if (trim($point) !== ''): ?>
                        <li><?= Html::encode($point) ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</div>

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
            
            <?= $form->field($model, 'tarikh_masuk')->textInput([
                'readonly' => true, 
                'value' => $tarikhMasukFormatted
            ]) ?>

            <?= $form->field($model, 'tarikh_keluar')->textInput([
                'readonly' => true, 
                'value' => $tarikhKeluarFormatted
            ]) ?>
            <?= $form->field($model, 'agensi_pemohon')->textInput(['class' => 'custom-input']) ?>
            <?= $form->field($model, 'tujuan')->textInput(['class' => 'custom-input']) ?>
            <?= $form->field($model, 'jantina')->dropDownList(
                    $jantina, ['class' => 'custom-input', 'prompt' => 'Pilih Jantina', 'class' => 'custom-input']) 
            ?>
                    
            <?php
            // Fetch all PenginapKategori records
            $penginapKategori = PenginapKategori::find()->all();

            // Filter out 'Pelajar' (id = 1) if the role is 3
            if ($role == 3 || $role == 4) {
                $penginapKategori = array_filter($penginapKategori, function($item) {
                    return $item->id != 1; // Exclude 'Pelajar'
                });
            }

            // Map the filtered records for dropdown
            $penginapOptions = ArrayHelper::map($penginapKategori, 'id', 'jenis_penginap');
            ?>

            <?= $form->field($model, 'jenis_penginap')->dropDownList(
                $penginapOptions,
                [
                    'prompt' => 'Pilih Jenis Penginap',
                    'class' => 'custom-input',
                ]
            ) ?>
            <div id="general-info" style="display: none;">
                <?= $form->field($model, 'no_kp_pemohon')->textInput(['class' => 'custom-input']) ?>
                <?= $form->field($model, 'no_tel')->textInput(['class' => 'custom-input']) ?>
                <?= $form->field($model, 'alamat')->textarea(['class' => 'custom-input', 'rows' => 2]) ?>
            </div>

            <div id="penginap-berkumpulan" style="display: none;">
                <h3>Penginap 1</h3>
                    <?= $form->field($model, 'nama_penginap_1')->textInput(['class' => 'custom-input']) ?>
                    <?= $form->field($model, 'no_kp_penginap_1')->textInput(['class' => 'custom-input']) ?>
                    <?= $form->field($model, 'email_penginap_1')->textInput(['class' => 'custom-input']) ?>
                    <?= $form->field($model, 'no_tel_penginap_1')->textInput(['class' => 'custom-input']) ?>
                    <?= $form->field($model, 'alamat_penginap_1')->textarea(['class' => 'custom-input', 'rows' => 2]) ?>

                    <h3>Penginap 2</h3>
                    <?= $form->field($model, 'nama_penginap_2')->textInput(['class' => 'custom-input']) ?>
                    <?= $form->field($model, 'no_kp_penginap_2')
                        ->textInput(['class' => 'custom-input'])
                        ->label('No. Kad Pengenalan Penginap 2 <span class="text-muted">*Bayaran bagi tempahan merujuk kepada No. IC Penginap 1</span>', ['encode' => false]) ?>

                    <?= $form->field($model, 'email_penginap_2')->textInput(['class' => 'custom-input']) ?>
                    <?= $form->field($model, 'no_tel_penginap_2')->textInput(['class' => 'custom-input']) ?>
                    <?= $form->field($model, 'alamat_penginap_2')->textarea(['class' => 'custom-input', 'rows' => 2]) ?>
            </div>
        
           <!-- Hidden file input field -->
           <div class="col-sm-10">
           <?= $form->field($model, 'surat_sokongan')->fileInput([
                'id' => 'document_doc_file',
                'class' => 'form-control',
                'accept' => '.png, .jpeg, .jpg, .doc, .docx, .pdf',
                // 'multiple' => true, // Allow multiple file uploads
                ]) ->label('Surat Sokongan <span class="text-muted">(*eg Surat Tawaran Kursus/ Surat Rasmi)</span>', ['escape' => false])?>
            </div>
            <div id="document_doc_file_help" class="form-text mb-0 help-text">
                Max 8MB per file (PNG, JPEG, JPG, PDF). 
            </div>
            
            <?php if (in_array(Yii::$app->user->identity->role, [0, 1, 6])): ?>
            <?= $form->field($model, 'is_simpanan')->checkbox([
                'value' => 1,
                'label' => 'Simpan Tempahan (Hanya dibuat oleh Admin)',
                'uncheck' => 0,
                'checked' => $model->is_simpanan ? true : false,
            ]) ?>
            <?php endif; ?>
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
</div>


<?php
$this->registerCss("

/* Style for calendar text */
#calendar {
   font-family: Arial, sans-serif; /* Optional: Change the font */
}

#calendar .fc-toolbar-title {
   color: #333333; /* Change calendar header title color */
   font-size: 20px;
   font-weight: bold;
}

#calendar .fc-day-header {
   color: #0056b3; /* Change the color of the weekday headers (e.g., Sun, Mon) */
   font-size: 14px;
   font-weight: bold;
   text-decoration: none; /* Remove underline */
}

#calendar .fc-daygrid-day-number {
   color:rgb(70, 72, 74); /* Change the color of the day numbers */
   font-weight: bold;
   text-decoration: none; /* Remove underline */
}

#calendar .fc-daygrid-day:hover {
   background-color: #f5f5f5; /* Add a hover effect for better interactivity */
   cursor: pointer;
}

#calendar a {
   color: inherit; /* Ensure links in the calendar inherit the text color */
   text-decoration: none; /* Remove underline for links */
}

#calendar a:hover {
   color: #0056b3; /* Change color on hover */
   text-decoration: none; /* Keep no underline on hover */
}

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


");

$this->registerJs("
  $(document).ready(function() {
      const role = " . Yii::$app->user->identity->role . ";

      // Check if there is a flash success message
      let flashSuccess = " . (Yii::$app->session->hasFlash('success') ? 'true' : 'false') . ";

      // Monitor changes to the 'Jenis Penginap' dropdown
      $('#tempahasrama-jenis_penginap').change(function() {
          var jenisPenginap = $(this).val();


          var jenisBilik = " . Json::encode($jenisBilik) . ";

          // Convert to string if it's an array
          if (Array.isArray(jenisBilik)) {
              jenisBilik = jenisBilik.join(',');
          }

          // Show extra fields only for 'penginap berkumpulan' and specific room types (id1 sebab katil single)
          if (jenisPenginap == 3 && jenisBilik !== 'ID_1') {
              $('#penginap-berkumpulan').slideDown();
              $('#general-info').slideUp();
              $('#tempahasrama-nama_penginap_1').attr('required', true).closest('.form-group').addClass('required');
              $('#tempahasrama-no_kp_penginap_1').attr('required', true).closest('.form-group').addClass('required');
              $('#tempahasrama-email_penginap_1').attr('required', true).closest('.form-group').addClass('required');
              $('#tempahasrama-no_tel_penginap_1').attr('required', true).closest('.form-group').addClass('required');
              $('#tempahasrama-alamat_penginap_1').attr('required', true).closest('.form-group').addClass('required');
              $('#tempahasrama-nama_penginap_2').attr('required', true).closest('.form-group').addClass('required');
              $('#tempahasrama-no_kp_penginap_2').attr('required', true).closest('.form-group').addClass('required');
              $('#tempahasrama-email_penginap_2').attr('required', true).closest('.form-group').addClass('required');
              $('#tempahasrama-no_tel_penginap_2').attr('required', true).closest('.form-group').addClass('required');
              $('#tempahasrama-alamat_penginap_2').attr('required', true).closest('.form-group').addClass('required');
          } else {
              $('#penginap-berkumpulan').slideUp();
              $('#general-info').slideDown();
              // Remove required
              $('#tempahasrama-nama_penginap_1').prop('required', false);
              $('#tempahasrama-no_kp_penginap_1').prop('required', false);
              $('#tempahasrama-email_penginap_1').prop('required', false);
              $('#tempahasrama-no_tel_penginap_1').prop('required', false);
              $('#tempahasrama-alamat_penginap_1').prop('required', false);
              $('#tempahasrama-nama_penginap_2').prop('required', false);
              $('#tempahasrama-no_kp_penginap_2').prop('required', false);
              $('#tempahasrama-email_penginap_2').prop('required', false);
              $('#tempahasrama-no_tel_penginap_2').prop('required', false);
              $('#tempahasrama-alamat_penginap_2').prop('required', false);
          }

          // Show field only if jenisPenginap == 1 and role condition applies
          if (jenisPenginap == 2) {
              $('.conditional-field').slideDown();
              $('#tempahasrama-no_kp_pemohon').attr('required', true).closest('.form-group').addClass('required');
              $('#tempahasrama-no_tel').attr('required', true).closest('.form-group').addClass('required');
              $('#tempahasrama-alamat').attr('required', true).closest('.form-group').addClass('required');
          } else {
              $('.conditional-field').slideUp();
              $('#tempahasrama-no_kp_pemohon').attr('required', false);
              $('#tempahasrama-no_tel').attr('required', false);
              $('#tempahasrama-alamat').attr('required', false);
          }
      });

      // Trigger change on page load in case default values match the condition
      $('#tempahasrama-jenis_penginap').trigger('change');
  });
", \yii\web\View::POS_READY);


?>