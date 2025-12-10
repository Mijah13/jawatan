<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */
$this->title = 'Tambah Pengguna';
$this->params['breadcrumbs'][] = $this->title;
$role = ['Admin Sistem', 'Admin Kemudahan', 'Pelulus', 'Pengguna', 'Pengguna Dalaman', 'Pelajar', 'Ketua Admin', 'admin kewangan', 'admin PEM'];
$this->registerCssFile('@web/css/tempah.css');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
?>

<div class="user-index">

    <div class="card p-3 mb-5 bg-white rounded">
       
        <div class="card-body">
            <div class="user-form">

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'role')->dropDownList($role, ['prompt' => '- Pilih Peranan -']) ?>

                <?= $form->field($model, 'kata_laluan')->passwordInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'sah_kata_laluan')->passwordInput(['maxlength' => true]) ?>

                <div class="form-group text-right">
                    <?= Html::submitButton(
                        $model->isNewRecord ? 'Daftar' : 'Kemaskini',
                        ['class' => 'btn btn-success', 'data-confirm' => 'Anda pasti?']
                    ) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>

<?php
$this->registerCss('

    h1 {
        font-size: 2.5rem; /* Adjust size */
        font-weight: bold; /* Make it bold */
        color: #ffffff; /* Text color */
        background: linear-gradient(90deg, #4CAF50, #2196F3); /* Gradient background */
        -webkit-background-clip: text; /* Clip background to text */
        -webkit-text-fill-color: transparent; /* Make the rest transparent */
        text-align: center; /* Center align the title */
        margin-bottom: 20px; /* Add space below */
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3); /* Add shadow for a 3D effect */
        letter-spacing: 1px; /* Slightly increase letter spacing */
    }
    ')
?>
