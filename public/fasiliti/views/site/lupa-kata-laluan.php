<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Lupa Kata Laluan';
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 50vh;">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <h2 class="text-center mb-3"><?= Html::encode($this->title) ?></h2>
                <p class="text-center mb-4">Masukkan e-mel anda untuk menerima pautan set semula kata laluan.</p>

                <?php $form = ActiveForm::begin(['id' => 'password-reset-request-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => 'Contoh: nama@domain.com']) ?>

                <div class="d-grid">
                    <?= Html::submitButton('Hantar', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
