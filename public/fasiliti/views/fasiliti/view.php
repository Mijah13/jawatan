<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Fasiliti $model */
/** @var array $jenisAsrama */

$this->title = 'MyFasiliti';
// $this->params['breadcrumbs'][] = ['label' => 'Fasiliti', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fasiliti-view">

    <h1><?= Html::encode($this->title) ?></h1>
    

    <p>
        <?= Html::a('Kemaskini', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Padam', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Adakah anda pasti mahu memadamkan item ini?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            [
                'format' => 'raw',
                'attribute' => 'gambar',
                'value' => function($model) {
                    return Html::img(Yii::getAlias('@web') . "/images/" . $model->gambar, [
                        'style' => 'max-width: 150px; max-height: 100px; object-fit: cover;'
                    ]);
                }
            ],
            
            'nama_fasiliti',
            'deskripsi:ntext',
            'kadar_sewa_perJam',
            'kadar_sewa_perHari',
            'kadar_sewa_perJamSiang',
            'kadar_sewa_perJamMalam',
            [
                'label' => 'Status Fasiliti',
                'attribute' => 'fasiliti_status',
                'value' => function($model) {
                    $statusFasiliti =  ['Kosong', 'disimpan', 'rosak', 'sedang dibaiki', 'Diisi'];
                    return $statusFasiliti[$model->fasiliti_status];
                }
            ],
            [
                'attribute' => 'akses_pengguna',
                'value' => function ($model) {
                    return $model->akses_pengguna == 0 ? 'Semua Pengguna' : 'Pengguna Dalaman';
                },
                'filter' => [
                    0 => 'Semua Pengguna',
                    1 => 'Pengguna Dalaman',
                ],
            ],
           
        ],
    ]) ?>


</div>
