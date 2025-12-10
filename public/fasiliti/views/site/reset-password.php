<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Set Semula Kata Laluan';
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 50vh;">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <h2 class="text-center mb-3"><?= Html::encode($this->title) ?></h2>
                <p class="text-center mb-4">Sila masukkan kata laluan baru anda:</p>

                <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true, 'placeholder' => 'Kata laluan baru']) ?>

                <div class="d-grid">
                    <?= Html::submitButton('Simpan', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
