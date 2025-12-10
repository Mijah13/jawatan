<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\DaftarForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;


$this->title = 'MyFasiliti';
$this->params['breadcrumbs'][] = $this->title;

// Register the external CSS file
$this->registerCssFile('@web/css/daftar.css');
?>


<?php
// Set flash di luar HTML tag
if (Yii::$app->request->get('flash') === 'show') {
    Yii::$app->session->setFlash('info', 'Bagi pengguna dalaman, anda hendaklah menggunakan email domain @ciast/@mohr bagi mendapatkan akses penuh.');
}

$showIpaymentModal = Yii::$app->session->hasFlash('info') && Yii::$app->request->isGet;
?>

<?php if (Yii::$app->session->hasFlash('info')): ?>
    <!-- Flash Message -->
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= Yii::$app->session->getFlash('info') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <?php if ($showIpaymentModal): ?>
    <!-- Modal HTML -->
    <div class="modal fade" id="ipaymentModal" tabindex="-1" aria-labelledby="ipaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-warning">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="ipaymentModalLabel">Peringatan</h5>
                </div>
                <div class="modal-body">
                    <p>
                        Sila pastikan anda telah mendaftar iPayment di Portal atau Aplikasi
                        <a href="https://ipayment.anm.gov.my/" target="_blank">IPayment</a>
                    </p>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" id="confirmCheckbox">
                        <label class="form-check-label" for="confirmCheckbox">
                            Saya telah mendaftar iPayment
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="confirmButton" class="btn btn-primary" disabled>Teruskan</button>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>
<?php endif; ?>

<div class="d-flex justify-content-center align-items-center">
    <div class="container rounded-4 overflow-hidden" style="max-width: 900px;">
        <div class="row g-0">
            <!-- Login Form Section -->
            <div class="col-md-6 text-white d-flex flex-column justify-content-center p-4"   style="background-color: #0D3B66;">
                <h3 class="fw-bold mb-3">Daftar akaun</h3>
                <p class="mb-4">Sila masukkan maklumat anda.</p>

                <?php $form = ActiveForm::begin(['id' => 'daftar-form']); ?>
                    <div class="mb-3">
                        <?= $form->field($model, 'nama')->textInput([
                            'class' => 'form-control rounded-pill',
                            'placeholder' => 'Nama',
                        ])->label(false) ?>
                    </div>
                    <div class="mb-3">
                        <?= $form->field($model, 'email')->textInput([
                            'class' => 'form-control rounded-pill',
                            'placeholder' => 'Email',
                        ])->label(false) ?>
                    </div>
                    <div class="mb-3">
                        <?= $form->field($model, 'kata_laluan')->passwordInput([
                            'class' => 'form-control rounded-pill',
                            'placeholder' => 'Kata Laluan',
                        ])->label(false) ?>
                    </div>
                    <div class="mb-3">
                        <?= $form->field($model, 'sah_kata_laluan')->passwordInput([
                            'class' => 'form-control rounded-pill',
                            'placeholder' => 'Sah Kata Laluan',
                        ])->label(false) ?>
                    </div>
                    <div class="mb-3">
                        <?= $form->field($model, 'captcha', [
                            'labelOptions' => ['label' => 'Sila masukkan kod seperti di bawah.'],
                        ])->widget(Captcha::class, [
                            'template' => '<div class="captcha-image-container">{image}</div>{input}',
                            'options' => [
                                'class' => 'custom-input',
                                'style' => 'border: 1px solid #ddd; padding: 8px; border-radius: 4px;', // Inline styles for input
                            ],
                        ]) ?>
                    </div>

                    <div id="role-label"></div>

                    <div class="mb-3">
                        <div id="role-radio-group"> <!-- Tambah wrapper ni -->
                        <label class="form-label">Peranan</label>
                            <?= $form->field($model, 'role')->inline()->radioList([
                                3 => 'Pengguna',
                                5 => 'Pelajar',
                            ], [
                                'item' => function($index, $label, $name, $checked, $value) {
                                    $checkedAttr = $checked ? 'checked' : '';
                                    return "<div class='form-check form-check-inline'>
                                                <input type='radio' class='form-check-input' name='DaftarForm[role]' value='{$value}' {$checkedAttr}>
                                                <label class='form-check-label'>{$label}</label>
                                            </div>";
                                }

                            ])->label(false) ?>
                        </div>
                    </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">Daftar</button>
                <?php ActiveForm::end(); ?>

                <div class="text-center mt-4">
                    <?= Html::a('log masuk?', ['site/login'], ['class' => 'text-decoration-underline text-light small']) ?>
                </div>
            </div>

            <!-- Decorative Right Section -->
             <div class="col-md-6 bg-light d-none d-md-flex flex-column justify-content-center position-relative">
                <div class="text-center p-4">
                    <h2 class="fw-bold text-dark">Selamat Datang</h2>
                    <p class="mt-2 text-dark">Sistem Pengurusan Fasiliti</p>
                </div>

                <!-- Facility Illustrations using SVG -->
                <div class="d-flex justify-content-around align-items-center">
                    <div class="text-center">
                        <!-- Dewan Illustration -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 64 64">
                            <rect x="8" y="12" width="48" height="40" fill="none" stroke="#007BFF" stroke-width="2"/>
                            <line x1="8" y1="32" x2="56" y2="32" stroke="#007BFF" stroke-width="2"/>
                            <line x1="8" y1="52" x2="56" y2="52" stroke="#007BFF" stroke-width="2"/>
                            <circle cx="20" cy="16" r="3" fill="rgba(0, 123, 255, 0.2)"/>
                            <circle cx="44" cy="16" r="3" fill="rgba(0, 123, 255, 0.2)"/>
                        </svg>
                        <p class="mt-2 text-dark">Dewan</p>
                    </div>

                    <div class="text-center">
                        <!-- Hostel Illustration -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 64 64">
                            <rect x="8" y="20" width="48" height="40" fill="none" stroke="#28a745" stroke-width="2"/>
                            <line x1="8" y1="40" x2="56" y2="40" stroke="#28a745" stroke-width="2"/>
                            <rect x="10" y="30" width="12" height="10" fill="#28a745"/>
                            <rect x="42" y="30" width="12" height="10" fill="#28a745"/>
                        </svg>
                        <p class="mt-2 text-dark">Asrama</p>
                    </div>

                    <div class="text-center">
                        <!-- Court Illustration -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 64 64">
                            <circle cx="32" cy="32" r="28" fill="none" stroke="#FFC107" stroke-width="2"/>
                            <line x1="16" y1="32" x2="48" y2="32" stroke="#FFC107" stroke-width="2"/>
                            <line x1="32" y1="16" x2="32" y2="48" stroke="#FFC107" stroke-width="2"/>
                        </svg>
                        <p class="mt-2 text-dark">Gelanggang</p>
                    </div>
                </div>

                <!-- Add new facility illustrations (Konkos, Bilik Meeting, Bilik Kuliah) -->
                <div class="d-flex justify-content-around align-items-center mt-4">
                    <div class="text-center">
                        <!-- Konkos (Canteen) Illustration -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 64 64">
                            <rect x="10" y="18" width="44" height="32" fill="none" stroke="#6c757d" stroke-width="2"/>
                            <line x1="10" y1="30" x2="54" y2="30" stroke="#6c757d" stroke-width="2"/>
                            <rect x="15" y="20" width="8" height="8" fill="#6c757d"/>
                            <rect x="25" y="20" width="8" height="8" fill="#6c757d"/>
                            <rect x="35" y="20" width="8" height="8" fill="#6c757d"/>
                            <rect x="45" y="20" width="8" height="8" fill="#6c757d"/>
                        </svg>
                        <p class="mt-2 text-dark">Konkos</p>
                    </div>

                    <div class="text-center">
                        <!-- Bilik Meeting (Meeting Room) Illustration -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 64 64">
                            <rect x="8" y="8" width="48" height="48" fill="none" stroke="#17a2b8" stroke-width="2"/>
                            <line x1="8" y1="24" x2="56" y2="24" stroke="#17a2b8" stroke-width="2"/>
                            <line x1="8" y1="40" x2="56" y2="40" stroke="#17a2b8" stroke-width="2"/>
                            <circle cx="20" cy="16" r="3" fill="rgba(23, 162, 184, 0.2)"/>
                            <circle cx="44" cy="16" r="3" fill="rgba(23, 162, 184, 0.2)"/>
                        </svg>
                        <p class="mt-2 text-dark">Bilik Mesyuarat</p>
                    </div>

                    <div class="text-center">
                        <!-- Bilik Kuliah (Classroom) Illustration -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 64 64">
                            <rect x="8" y="8" width="48" height="48" fill="none" stroke="#007bff" stroke-width="2"/>
                            <line x1="8" y1="24" x2="56" y2="24" stroke="#007bff" stroke-width="2"/>
                            <line x1="8" y1="40" x2="56" y2="40" stroke="#007bff" stroke-width="2"/>
                            <circle cx="20" cy="16" r="3" fill="rgba(0, 123, 255, 0.2)"/>
                            <circle cx="44" cy="16" r="3" fill="rgba(0, 123, 255, 0.2)"/>
                        </svg>
                        <p class="mt-2 text-dark">Bilik Kuliah</p>
                    </div>
                </div>

                <!-- Optional Decorative Elements -->
                <div class="position-absolute w-100 h-100" style="top: 0; left: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500" class="w-100 h-100">
                        <circle cx="120" cy="120" r="100" fill="rgba(0, 123, 255, 0.1)" />
                        <circle cx="380" cy="350" r="120" fill="rgba(0, 123, 255, 0.1)" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
$this->registerCss('
    .captcha-image-container {
   
    padding: 10px;
    border: 1px solid #ccc; /* Light border around the image */
    border-radius: 8px; /* Rounded corners */
    background-color: #ffff; /* Light gray background */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
    margin-bottom: 10px; /* Add some space below the image */
    text-align: center; /* Center the image */
}

');

$this->registerJs(<<<JS
    
// Show modal dengan options untuk lock dari dismissed by backdrop/keyboard
var myModal = new bootstrap.Modal(document.getElementById('ipaymentModal'), {
    backdrop: 'static',
    keyboard: false
});
myModal.show();

// Enable/Disable button based on checkbox
$('#confirmCheckbox').on('change', function () {
    $('#confirmButton').prop('disabled', !this.checked);
});

// Hide modal bila tekan butang teruskan
$('#confirmButton').on('click', function () {
    myModal.hide();
});
   
$(document).ready(function () {
    const \$emailInput = $("input[name='DaftarForm[email]']");
    const \$roleGroup = $("#role-radio-group");
    const \$roleLabel = $("#role-label");

    function checkEmailDomain() {
        const email = \$emailInput.val();
        const isKerajaan = /@(ciast\\.gov\\.my|mohr\\.gov\\.my)\$/i.test(email);

        if (isKerajaan) {
            $("#role-radio-group").hide();
            $("#role-label").html('<p><strong>Peranan: <u>Pengguna Dalaman</u>.</strong></p>');
            
            // Inject hidden input
            if ($("#hidden-role").length === 0) {
                $("<input>").attr({
                    type: "hidden",
                    id: "hidden-role",
                    name: "DaftarForm[role]",
                    value: 4
                }).appendTo("form");
            } else {
                $("#hidden-role").val(4);
            }

        } else {
            $("#role-radio-group").show();
            $("#role-label").html('');
            $("#hidden-role").remove(); // Remove hidden input if not kerajaan
        }

    }

    \$emailInput.on("input", checkEmailDomain);
    checkEmailDomain(); // Initial trigger
});


JS);


?>

