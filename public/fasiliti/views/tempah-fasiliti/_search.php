<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TempahFasilitiSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tempah-fasiliti-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'fasiliti_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'no_kp_pemohon') ?>

    <?= $form->field($model, 'agensi_pemohon') ?>

    <?php // echo $form->field($model, 'tujuan') ?>

    <?php // echo $form->field($model, 'tarikh_masuk') ?>

    <?php // echo $form->field($model, 'tarikh_keluar') ?>

    <?php // echo $form->field($model, 'no_tel') ?>

    <?php // echo $form->field($model, 'alamat') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'tempoh') ?>

    <?php // echo $form->field($model, 'jangkaan_hadirin') ?>

    <?php // echo $form->field($model, 'peralatan') ?>

    <?php // echo $form->field($model, 'lain_peralatan') ?>

    <?php // echo $form->field($model, 'surat_sokongan') ?>

    <?php // echo $form->field($model, 'disahkan_oleh') ?>
		
    <?php // echo $form->field($model, 'status_tempahan_adminKemudahan') ?>

    <?php // echo $form->field($model, 'status_pembayaran') ?>
    
    <?php // echo $form->field($model, 'status_tempahan_pelulus') ?> 

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
