<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Asrama $model */

$this->title = 'MyFasiliti';
// $this->params['breadcrumbs'][] = ['label' => 'Asramas', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="asrama-view">

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
            'blok',
            'aras',
            'no_asrama',
            [
                'label' => 'Status Asrama',
                'value' => function($model) {
                    $status = ['Kosong', 'Sedang dibersihkan', 'Simpanan', 'Rosak', 'Risiko', 'Sedang dibaiki', 'Diisi', 'Separa diisi'];
                    return $status[$model->status_asrama];
                }
            ],
            [
                'label' => 'Jenis Bilik',
                'value' => function($model) {
                    // Map JenisAsrama data
                    $jenisBilikMapping = \yii\helpers\ArrayHelper::map(
                        \app\models\JenisAsrama::find()->all(),
                        'id', // Primary key in JenisAsrama table
                        'jenis_bilik' // Room type name
                    );
            
                    // Return the room type name for the current model
                    return $jenisBilikMapping[$model->jenis_asrama_id] ?? 'Unknown'; 
                },
            ],
            [
                'label' => 'Jenis Kelamin',
                'value' => function($model) {
                    $kelamin = ['Lelaki', 'Perempuan', 'Lelaki/Perempuan'];
                    return $kelamin[$model->kelamin];
                }
            ],
            // [
            //     'label' => 'Penginap Kategori',
            //     'value' => function($model) {
            //         $penginapKategori = ['Penyewa', 'Pelajar'];
            //         return $penginapKategori[$model->penginap_kategori_id];
            //     }
            // ],
            [
                'label' => 'Penginap Kategori',
                'value' => function($model) {
                    return $model->penginapKategori->jenis_penginap ?? '(Tidak Ditentukan)';
                }
            ],

            'kapasiti',

        ],
        ]) ?>
