<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\TempahFasiliti $model */

// $this->title = $model->id;
// $this->params['breadcrumbs'][] = ['label' => 'Tempah Fasilitis', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tempah-fasiliti-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <title><?= Html::encode($this->title ?? 'MyFasiliti') ?></title>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'created_at',
            'updated_at',
            'fasiliti_id', 
            [
                'attribute' => 'jenis_fasiliti',
                'value' => $model->fasiliti ? $model->fasiliti->nama_fasiliti : 'Not set', // Adjust 'name' based on your column
            ],
            'user.nama', 
            'no_kp_pemohon',
            'agensi_pemohon',
            'tujuan',
            [
                'attribute' => 'tarikh_masuk',
                'label' => 'Tarikh Masuk',
                'value' => function($model) {
                    return Yii::$app->formatter->asDate($model->tarikh_masuk, 'php:d-m-Y');
                },
            ],
            [
                'attribute' => 'tarikh_keluar',
                'label' => 'Tarikh Keluar',
                'value' => function($model) {
                    return Yii::$app->formatter->asDate($model->tarikh_keluar, 'php:d-m-Y');
                },
            ],
            'no_tel',
            'alamat',
            'email:email',
            [
                'label' => 'Tempoh',
                'attribute' => 'tempoh',
                'value' => function ($model) {
                    return $model->tempohLabel;
                },
            ],

            'jangkaan_hadirin',
           
            [
                'attribute' => 'surat_sokongan',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->surat_sokongan) {
                        return Html::a(
                            'Buka Fail',
                            Yii::getAlias('@web/uploads/' . $model->surat_sokongan),
                            ['target' => '_blank', 'class' => 'btn btn-primary btn-sm']
                        );
                    }
                    return '<span class="text-danger">Tiada fail</span>';
                },
            ], 
            // [
            //     'attribute' => 'status_tempahan',
            //     'label' => 'Status Tempahan',
            //     'format' => 'raw',
            //     'value' => function ($model) {
            //         $statuses = [
            //             1 => 'Sedang Diproses',
            //             2 => 'Disahkan',
            //             3 => 'Dibatalkan',
            //         ];
                   
            //         return Html::dropDownList(
            //             "status_tempahan[{$model->id}]",
            //             $model->status_tempahan,
            //             $statuses,
            //             [
            //                 'class' => 'form-control status-tempahan',
            //                 'id' => 's-'.$model->id,
            //             ]
            //         );
            //     },
            //     'filter' => [
            //         1 => 'Sedang Diproses',
            //         2 => 'Disahkan',
            //         3 => 'Dibatalkan',
            //     ],
            // ],
            // [
            //     'attribute' => 'status_pembayaran',
            //     'label' => 'Status Pembayaran',
            //     'format' => 'raw',
            //     'value' => function ($model) {
            //         $statuses = [0 => 'Belum Disemak', 1 => 'Tidak Diperlukan', 2 => 'Diperlukan'];
            //         return Html::dropDownList(
            //             "status_pembayaran[{$model->id}]",
            //             $model->status_pembayaran,
            //             $statuses,
            //             [
            //                 'class' => 'form-control status-pembayaran',
            //                 'id' => 's-'.$model->id,
            //             ]
            //         );
            //     },
            // ],
        ],
    ]) ?>
 
 </div>
  