<?php

use app\models\TempahAsrama;
use app\models\JenisAsrama;
use app\models\Asrama;
use app\models\PenginapKategori;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\bootstrap5\Tabs;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\TempahAsramaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'MyFasiliti';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
?>
<div class="tempah-asrama-index">

<div class="col-lg-12 bg-light-gray"> 
    <div class="card bg-light-gray">
        <div class="card-header bg-light-gray" style="font-size:large; font-weight:bold;">
            <h1>Senarai Tempahan Gagal -Asrama</h1>
        </div>
    <div className="overflow-auto max-h-[500px] scrollbar-thin scrollbar-thumb-gray-500 scrollbar-track-gray-300">
    <div class="scroll-shadow">
    </div>
    <div id="inner-container" class="inner-container">
        <div class="card-body">
        <br>
        <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-hover table-bordered'],
            'filterModel' => $searchModel,
            'pager' => [
                'class' => 'yii\bootstrap5\LinkPager',
                'maxButtonCount' => 3, 
                'firstPageLabel' => '«',  // First page
                'lastPageLabel' => '»',   // Last page
                'prevPageLabel' => false,  // buang prev
                'nextPageLabel' => false,  // buang next
                'options' => ['class' => 'mt-3'] 
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn', 'header' => 'No.'],
                // ['class' => 'yii\grid\CheckboxColumn', 'checkboxOptions' => ['class' => 'bulk-select']],

                // [
                //     'header' => 'Tindakan',
                //     'class' => ActionColumn::className(),
                //     'template' => '{delete} {view}',
                //     'buttons' => [
                //         'view' => function ($url, $model) {
                //             return Html::a('<i class="bi bi-eye"></i>', $url, [
                //                 'class' => 'btn btn-sm btn-primary',
                //                 'title' => 'Lihat Maklumat',
                //             ]);
                //         },
                //         'update' => function ($url, $model) {
                //             return Html::a('<i class="bi bi-pencil"></i>', $url, [
                //                 'class' => 'btn btn-sm btn-warning',
                //                 'title' => 'Kemaskini Maklumat',
                //             ]);
                //         },
                //         'delete' => function ($url, $model) {
                //             return Html::a('<i class="bi bi-trash"></i>', $url, [
                //                 'class' => 'btn btn-sm btn-danger',
                //                 'title' => 'Padam Pengguna',
                //                 'data-confirm' => 'Adakah anda pasti untuk memadam tempahan ini?',
                //                 'data-method' => 'post',
                //             ]);
                //         },
                //     ],
                // ],
                // [
                //     'attribute' => 'status_tempahan_adminKemudahan',
                //     'label' => 'Status Tempahan',
                //     'format' => 'raw',
                //     'value' => function ($model) {
                //         $statuses = [
                //             1 => 'Sedang Diproses',
                //             2 => 'Menunggu bayaran',
                //             3 => 'Diluluskan',
                //             4 => 'Dibatalkan',
                //         ];
                    
                //         return Html::dropDownList(
                //             "status_tempahan_adminKemudahan[{$model->id}]",
                //             $model->status_tempahan_adminKemudahan,
                //             $statuses,
                //             [
                //                 'class' => 'form-control status-tempahan-adminKemudahan',
                //                 'id' => 's-'.$model->id,
                //             ]
                //         );
                //     },
                //     'filter' => [
                //         1 => 'Sedang Diproses',
                //         2 => 'Menunggu bayaran',
                //         3 => 'Diluluskan',
                //         4 => 'Dibatalkan',
                //     ],
                // ],
                // [
               
                // [
                //     'attribute' => 'status_pembayaran',
                //     'label' => 'Status Pembayaran',
                //     'format' => 'raw',
                //     'value' => function ($model) {
                //         $statuses = [
                //             0 => ['label' => 'Belum Disemak', 'class' => 'bg-warning text-dark'], 
                //             1 => ['label' => 'Tidak Berbayar', 'class' => 'bg-blue text-white'], 
                //             2 => ['label' => 'Berbayar', 'class' => 'bg-blue text-white']
                //         ];
                
                //         $status = $statuses[$model->status_pembayaran] ?? ['label' => 'Unknown', 'class' => 'bg-secondary text-white'];
                
                //         return Html::tag(
                //             'span',
                //             $status['label'],
                //             ['class' => "badge {$status['class']} px-3 py-2"]
                //         );
                //     },
                // ],
                 [
                    'attribute' => 'dibatalkan_oleh_nama',
                    'label' => 'Dibatalkan Oleh',
                    'value' => function ($model) {
                        if ($model->diluluskanOleh) {
                            return $model->diluluskanOleh->nama;
                        } elseif ($model->disokongOleh) {
                            return $model->disokongOleh->nama; // Admin kemudahan yang batalkan
                        } else {
                            return 'Sistem: Pembayaran tidak dibuat dalam tempoh 5hari';
                        }
                    },
                    'format' => 'raw', // To allow HTML for fallback text
                    'contentOptions' => ['class' => 'text-center'], // Optional: Center align
                ], 
                'alasan_batal',
                'id',
                // 'id_asrama',
                [
                    'attribute' => 'nama',
                    'value' => 'user.nama',
                ],
                [
                    'attribute' => 'jenis_penginap',
                    'value' => function($model) {
                        $jenis_penginapan = ArrayHelper::map(PenginapKategori::find()->all(), 'id', 'jenis_penginap');
                        return $jenis_penginapan[$model->jenis_penginap];
                    }
                ],
                [
                    'label' => 'Jenis Bilik',
                    'attribute' => 'jenis_bilik',
                    'filterInputOptions' => [
                        'class' => 'form-select', 
                    ],
                    'value' => function($model) {
                        $jenisBilikMapping = ArrayHelper::map(
                            \app\models\JenisAsrama::find()->all(),
                            'id', // Primary key in JenisAsrama table
                            'jenis_bilik' // Room type name
                        );

                        return $jenisBilikMapping[$model->jenis_bilik] ?? 'Unknown';
                    },
                    'filter' => ArrayHelper::map(
                        \app\models\JenisAsrama::find()->all(),
                        'id',
                        'jenis_bilik'
                    ),
                ],
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
                
                'agensi_pemohon',
                'tujuan',
                'no_kp_pemohon',
            
                'no_tel',
                'alamat',
                'email:email',
                
                // 'no_matrik_pemohon',
                // 'kod_kursus',
                // 'sesi_batch',
                // 'status',
                // 'masalah_kesihatan',
                // 'jantina',
                // 'nama_penginap_1',
                // 'email_penginap_1',
                // 'no_tel_penginap_1',
                // 'alamat_penginap_1',
                // 'nama_penginap_2',
                // 'email_penginap_2',
                // 'no_tel_penginap_2',
                // 'alamat_penginap_2',
            ],
            ]); ?>
        </div>
        </div>
        
        <!-- Bottom scrollbar -->
        <div class="scroll-shadow">
            <div class="scroll-shadow-content"></div>
        </div>
    </div>
    </div>

    </div>
    </div>

<?php
$this->registerJs("
// Get references to the top, content, and bottom scrollbars
  const topScrollBar = document.querySelectorAll('.scroll-shadow')[0];
  const bottomScrollBar = document.querySelectorAll('.scroll-shadow')[1];
  const contentScrollBar = document.querySelector('.inner-container');

  // Set the width of the dummy content to match the scrollable content
  const shadowContents = document.querySelectorAll('.scroll-shadow-content');
  shadowContents.forEach(shadow => {
    shadow.style.width = contentScrollBar.scrollWidth + 'px';
  });

  // Synchronize scroll positions
  const syncScroll = (source, targets) => {
    source.addEventListener('scroll', () => {
      targets.forEach(target => {
        target.scrollLeft = source.scrollLeft;
      });
    });
  };

  // Sync top scrollbar with content and bottom scrollbar
  syncScroll(topScrollBar, [contentScrollBar, bottomScrollBar]);

  // Sync bottom scrollbar with content and top scrollbar
  syncScroll(bottomScrollBar, [contentScrollBar, topScrollBar]);

  // Sync content scrollbar with top and bottom scrollbars
  syncScroll(contentScrollBar, [topScrollBar, bottomScrollBar]);
");


$this->registerCss('
    .bg-blue {
        background-color:rgb(0, 105, 217) !important;
        color: #ffffff !important; /* White text */
    }
    .outer-container {
      width: 100%;
      max-width: 800px;
      margin: 0 auto;
      border: 2px solid #ccc;
      padding: 10px;
      position: relative;
    }

    /* Top scrollbar container */
    .scroll-shadow {
      height: 16px; /* Height of the scrollbar */
      overflow-x: auto; /* Enable horizontal scrolling */
      margin-bottom: 5px; /* Space between top scroll and content */
      display: none;
    }

    /* Dummy content for the top scrollbar */
    .scroll-shadow-content {
      width: max-content; /* Match the scrollable content width */
    }

    .inner-container {
      display: flex;
      overflow-x: auto; /* Enable horizontal scrolling */
      white-space: nowrap; /* Prevent wrapping of child elements */
      border: 1px solid #ddd;
    }

    .scroll-item {
      flex: 0 0 auto; /* Prevent items from stretching */
      min-width: 150px; /* Fixed item width */
      margin-right: 10px;
      padding: 10px;
      background-color: #007bff;
      color: white;
      border-radius: 5px;
      text-align: center;
    }

    .scroll-shadow::-webkit-scrollbar,
    .inner-container::-webkit-scrollbar {
      height: 8px;
    }

    .scroll-shadow::-webkit-scrollbar-thumb,
    .inner-container::-webkit-scrollbar-thumb {
      background: #888; /* Scrollbar color */
      border-radius: 4px;
    }

    .scroll-shadow::-webkit-scrollbar-thumb:hover,
    .inner-container::-webkit-scrollbar-thumb:hover {
      background: #555; /* Scrollbar hover color */
    }

    .scroll-content {
      margin-top: 10px; /* Space between bottom scroll and other content */
    }

    /* Untuk Webkit (Chrome, Edge, Safari) */
::-webkit-scrollbar {
  width: 5px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: #555;
}
');

$this->registerCss('
 h1 {
        font-size: 1.5rem; /* Adjust size */
        font-weight: bold; /* Make it bold */
        color:rgb(32, 42, 111); /* Text color */
    }

    .table-responsive {
        overflow: hidden; /* Prevent overflow */
        margin-bottom: 20px;
    }

    table {
        border-collapse: collapse; /* Change to collapse to remove extra spacing */
        width: 100%;
    }

    .table thead th {
        background-color:rgb(82, 139, 185); /* Replace with your desired color */
        color: white; /* Text color */
        text-decoration: none; /* Remove underline */
        text-align: center;
    }

    .table thead th a {
        color: white; /* Make header links white */
        text-decoration: none; /* Remove underline */
    }

    .table thead th a:hover {
        color:rgb(0, 0, 0); /* Optional: Add hover effect */
    }

    .table tbody tr:nth-child(odd) {
        background-color: #f9f9f9; /* Light background for striped rows */
    }

    .table tbody tr:nth-child(even) {
        background-color: #ffffff; /* White background */
    }

    .table tbody tr:hover {
        background-color: #e9ecef; /* Highlight row on hover */
        cursor: pointer;
    }
    .bg-light-gray {
     background-color: #ffffff;
    }

    .table tbody td {
        padding: 10px;
        text-align: center;
        vertical-align: middle;
        border-bottom: 1px solid #dee2e6; /* Light border between rows */
    }

    /* Remove any unintended margin or padding */
    .table, .table-container {
        margin: 0;
        padding: 0;
    }
');
?>
