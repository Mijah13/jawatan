<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\TempahAsrama $model */

$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');

\yii\web\YiiAsset::register($this);

// Define semua attributes (boleh guna 'attribute:format' style)
$allAttributes = [
    'id',
    'id_asrama',
    'user_id',
    'jenis_penginap',
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
    'alamat:ntext',
    'email:email',
    [
        'label' => 'Jantina',
        'value' => function($model) {
            $jantina = ['Lelaki', 'Perempuan'];
            return $jantina[$model->jantina] ?? null;
        }
    ],
    [
        'label' => 'Jenis Bilik',
        'attribute' => 'jenis_bilik',
        'value' => function($model) {
            $jenisBilikMapping = ArrayHelper::map(
                \app\models\JenisAsrama::find()->all(),
                'id',
                'jenis_bilik'
            );
            return $jenisBilikMapping[$model->jenis_bilik] ?? null;
        },
    ],

                // 03.10.2025 Betulkan error view - raihan
                [
        'attribute' => 'surat_sokongan',
        'format' => 'raw',
        'value' => function($model) {
            return $model->surat_sokongan
                ? Html::a('Download File', Yii::getAlias('@web/uploads/' . $model->surat_sokongan), [
                    'target' => '_blank',
                    'class' => 'btn btn-success'
                ])
                : null;
        },
    ],
                // [
                //     'attribute' => 'surat_sokongan',
                //     'format' => 'raw',
                //     'value' => function($model) {
                //         return $model->surat_sokongan
                //             ? Html::a('Download File', Yii::getAlias('/uploads/' . $model->surat_sokongan, ['target' => '_blank', 'class' => 'btn btn-success']): null;
                //     },
                // ],


    'nama_penginap_1',
    'email_penginap_1:email',
    'no_tel_penginap_1',
    'alamat_penginap_1',
    'nama_penginap_2',
    'email_penginap_2:email',
    'no_tel_penginap_2',
    'alamat_penginap_2',
    'disokong_oleh',
    'diluluskan_oleh',
    'status_tempahan_pelulus',
    [
        'attribute' => 'created_at',
        'label' => 'Created At',
        'value' => function ($model) {
            return date('d-m-Y H:i:s', strtotime($model->created_at));
        }
    ],
    [
        'attribute' => 'updated_at',
        'label' => 'Updated At',
        'value' => function ($model) {
            return date('d-m-Y H:i:s', strtotime($model->updated_at));
        }
    ],
    
    
    // [
    //     'attribute' => 'status_tempahan_adminKemudahan',
    //     'label' => 'Status Tempahan',
    //     'format' => 'raw',
    //     'value' => function ($model) {
    //         $statuses = [1 => 'Sedang Diproses', 2 => 'Disahkan', 3 => 'Dibatalkan'];
    //         return Html::dropDownList(
    //             "status_tempahan_adminKemudahan[{$model->id}]",
    //             $model->status_tempahan_adminKemudahan,
    //             $statuses,
    //             ['class' => 'form-control status-tempahan-adminKemudahan', 'id' => 's-'.$model->id]
    //         );
    //     },
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
    //             ['class' => 'form-control status-pembayaran', 'id' => 's-'.$model->id]
    //         );
    //     },
    // ],
];

// Filter only attributes yang ada value
$filteredAttributes = [];
foreach ($allAttributes as $attr) {
    if (is_string($attr)) {
        // Handle 'attribute:format' style
        if (strpos($attr, ':') !== false) {
            [$attributeName, $format] = explode(':', $attr);
            $value = $model->$attributeName;
            if ($value !== null && $value !== '') {
                $filteredAttributes[] = [
                    'attribute' => $attributeName,
                    'format' => $format,
                ];
            }
        } else {
            $value = $model->$attr;
            if ($value !== null && $value !== '') {
                $filteredAttributes[] = $attr;
            }
        }
    } elseif (is_array($attr)) {
        // Custom value handler
        $valFn = $attr['value'] ?? null;
        $attributeKey = $attr['attribute'] ?? null;

        $value = is_callable($valFn)
            ? $valFn($model)
            : ($attributeKey ? $model->$attributeKey : null);

        if ($value !== null && $value !== '') {
            $filteredAttributes[] = $attr;
        }
    }
}
?>

<div class="tempah-asrama-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <title><?= Html::encode($this->title ?? 'MyFasiliti') ?></title>

    
    <div class="card p-3 mb-5 bg-white rounded">
       
        <div class="card-body">

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => $filteredAttributes,
        ]) ?>

        </div>
    </div>

</div>

<?php
$this->registerCss("
    .detail-view th {
        width: 25%;
        white-space: nowrap;
        vertical-align: top;
        
    }
    .detail-view td {
        width: 75%;
    }
");
?>
