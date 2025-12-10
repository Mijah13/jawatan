<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\JenisAsrama $model */

$this->title = 'MyFasiliti';
// $this->params['breadcrumbs'][] = ['label' => 'Jenis Asramas', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="jenis-asrama-view">

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
            'id',
            [
                'format' => 'raw',
                'attribute' => 'gambar',
                'value' => function($model) {
                    $gambarArray = json_decode($model->gambar, true); // Decode JSON ke array
                    if (empty($gambarArray)) {
                        return "No image available";
                    }
                    
                    $html = "";
                    foreach ($gambarArray as $gambar) {
                        $html .= Html::img(Yii::getAlias('@web') . "/images/" . $gambar, [
                            'style' => 'max-width: 150px; max-height: 100px; object-fit: cover; margin-right: 5px;'
                        ]);
                    }
                    return $html;
                }
            ],
            // [
            //     'format' => 'raw',
            //     'attribute' => 'gambar',
            //     'value' => function($model) {
            //         return Html::a(
            //             Html::img("/images/".$model->gambar, ['style' => 'max-width: 80px; max-height: 80px; object-fit: cover; border-radius: 5px;']),
            //             "/images/".$model->gambar,
            //             ['target' => '_blank']
            //         );
            //     }
            // ],
            'jenis_bilik',
            'deskripsi:ntext',
            'kadar_sewa',
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
            // 'asrama_id',
        ],
    ]) ?>

</div>
