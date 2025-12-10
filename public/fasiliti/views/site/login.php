<?php

// / @var yii\web\View $this 
// / @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'MyFasiliti';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css');

// Register the external CSS file
$this->registerCssFile('/css/login.css');
// Register Google Fonts (Poppins)
$this->registerLinkTag([
    'rel' => 'stylesheet',
    'href' => 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap'
]);

?>
<div class="container-bg">
<?php
// Display success flash message
if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= Yii::$app->session->getFlash('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php
// Display error flash message
if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= Yii::$app->session->getFlash('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-center align-items-center">
    <div class="container rounded-4 overflow-hidden" style="max-width: 900px;">
        <div class="row g-0" style="border-radius: 50px;">
            <!-- Login Form Section -->
            <div class="col-md-6 text-white d-flex flex-column justify-content-center p-4"  style="background-color: #0D3B66;">
                <h3 class="fw-bold mb-3">Log Masuk</h3>
                <p class="mb-4">Sila masukkan maklumat anda</p>

                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                    <div class="mb-3">
                        <?= $form->field($model, 'email')->textInput([
                            'class' => 'form-control rounded-pill',
                            'placeholder' => 'Email',
                        ])->label(false) ?>
                        <?php if ($model->hasErrors('email')): ?>
                    <div class="alert alert-danger">
                        <?= $model->getFirstError('email') ?>
                    </div>
                <?php endif; ?>
                    </div>
                    <div class="mb-3 position-relative">
                        <?= $form->field($model, 'password')->passwordInput([
                            'class' => 'form-control rounded-pill pe-5',
                            'placeholder' => 'Kata Laluan',
                            'id' => 'passwordInput',
                        ])->label(false) ?>
                        
                        <!-- Icon toggle -->
                       <button type="button"
                            class="btn position-absolute end-0 me-3"
                            onclick="togglePassword()"
                            style="top: 0.10rem; background: transparent; border: none;">
                            <i class="fas fa-eye-slash text-dark" id="toggleIcon"></i>
                        </button>


                    </div>

                  <!-- code lupa kata laluan -->
                  <div class="d-flex justify-content-between align-items-center mb-3">
                       <a href="<?= \yii\helpers\Url::to(['site/lupa-kata-laluan']) ?>" class="text-light small">Lupa Kata Laluan?</a>
                   </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">Log Masuk</button>
                <?php ActiveForm::end(); ?>

                <div class="text-center mt-4">
                <?= Html::a('Daftar Akaun Baru', ['site/daftar', 'flash' => 'show'], ['class' => 'text-decoration-underline text-light small']) ?>
                </div>
            </div>

            <!-- Decorative Right Section -->
            <div class="col-md-6 bg-light d-none d-md-flex flex-column justify-content-center position-relative">

                <div class="text-center p-4">
                    <h2 class="fw-bold text-dark">Selamat Datang</h2>
                    <p class="mt-2 text-dark">Sistem Pengurusan Fasiliti</p>
                </div><!-- Facility Illustrations using SVG -->
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
                    </div><div class="text-center">
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
</div>

<script>
    document.getElementById("login-form").addEventListener("submit", function() {
        const input = document.getElementById("passwordInput");
        input.type = "password"; // reset
    });

    function togglePassword() {
        const input = document.getElementById("passwordInput");
        const icon = document.getElementById("toggleIcon");

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    }
</script>
