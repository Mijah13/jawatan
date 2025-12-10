<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TempahAsramaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tempah-asrama-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_asrama') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'jenis_penginap') ?>

    <?= $form->field($model, 'no_kp_pemohon') ?>

    <?php // echo $form->field($model, 'agensi_pemohon') ?>

    <?php // echo $form->field($model, 'tujuan') ?>

    <?php // echo $form->field($model, 'tarikh_masuk') ?>

    <?php // echo $form->field($model, 'tarikh_keluar') ?>

    <?php // echo $form->field($model, 'tarikh_pembersihan') ?>

    <?php // echo $form->field($model, 'no_tel') ?>

    <?php // echo $form->field($model, 'alamat') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'jenis_bilik') ?>

    <?php // echo $form->field($model, 'surat_sokongan') ?>

    <?php // echo $form->field($model, 'no_matrik_pemohon') ?>

    <?php // echo $form->field($model, 'kod_kursus') ?>

    <?php // echo $form->field($model, 'sesi_batch') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'masalah_kesihatan') ?>

    <?php // echo $form->field($model, 'jantina') ?>

    <?php // echo $form->field($model, 'nama_penginap_1') ?>

    <?php // echo $form->field($model, 'email_penginap_1') ?>

    <?php // echo $form->field($model, 'no_tel_penginap_1') ?>

    <?php // echo $form->field($model, 'alamat_penginap_1') ?>

    <?php // echo $form->field($model, 'nama_penginap_2') ?>

    <?php // echo $form->field($model, 'email_penginap_2') ?>

    <?php // echo $form->field($model, 'no_tel_penginap_2') ?>

    <?php // echo $form->field($model, 'alamat_penginap_2') ?>

    <?php // echo $form->field($model, 'disokong_oleh') ?>

    <?php // echo $form->field($model, 'status_tempahan_adminKemudahan') ?>

    <?php // echo $form->field($model, 'alasan_batal') ?>

    <?php // echo $form->field($model, 'status_pembayaran') ?>

    <?php // echo $form->field($model, 'diluluskan_oleh') ?>

    <?php // echo $form->field($model, 'status_tempahan_pelulus') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'is_simpanan') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
