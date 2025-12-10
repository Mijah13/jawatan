<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\PelajarAsrama $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pelajar Asramas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pelajar-asrama-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user.nama',
            'user_id',
            'id_asrama',
            'no_kp',
            'no_tel',
            'email:email',
            'kod_kursus',
            'sesi_batch',
             [
                'attribute' => 'status',
                'label' => 'Status',
                'format' => 'raw',
                'value' => function ($model) {
                    $status = [
                        0 => 'Bujang',
                        1 => 'Berkahwin',
                    ];
                    return $status[$model->status] ?? 'Tidak Diketahui';
                },
                'filter' => [
                    0 => 'Bujang',
                    1 => 'Berkahwin',
                ],  
            ],
            [
                'attribute' => 'jantina',
                'label' => 'Jantina',
                'format' => 'raw',
                'value' => function ($model) {
                    $jantina = [
                        0 => 'Lelaki',
                        1 => 'Perempuan',
                    ];
                    return $jantina[$model->jantina] ?? 'Tidak Diketahui';
                },
                'filter' => [
                    0 => 'Lelaki',
                    1 => 'Perempuan',
                ],
            ],
            'alamat',
            'no_waris', 
            'hubungan', 
            // 'jenis_bilik',
            [
                'attribute' => 'status_penginapan',
                'label' => 'Status Penginapan',
                'format' => 'raw',
                'value' => function ($model) {
                    $statusPenginapan = [
                        0 => 'Dalam',
                        1 => 'Luar',
                    ];
                    return $statusPenginapan[$model->status_penginapan] ?? 'Tidak Diketahui';
                },
                'filter' => [
                    0 => 'Dalam',
                    1 => 'Luar',
                ],
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
