<?php

use app\models\TempahAsrama;
use app\models\JenisAsrama;
use app\models\PenginapKategori;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\bootstrap5\Tabs;


/** @var yii\web\View $this */
/** @var app\models\TempahAsramaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'MyFasiliti';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
?>

<?php
// Calculate total beforehand
$totalJumlah = 0;
foreach ($dataProvider->models as $model) {
    if ($model->status_tempahan_adminKemudahan == 2 && $model->status_pembayaran == 2) { // Include only tempahan with status 2
        $date1 = new DateTime($model->tarikh_masuk);
        $date2 = new DateTime($model->tarikh_keluar);
        $days = $date1->diff($date2)->days;

        if ($model->jenisBilik) {
            $totalJumlah += $model->jenisBilik->kadar_sewa * $days;
        }
    }
}
?>

<?php
// Assume user->role is available
$allowedRoles = [0, 1, 6];

if (in_array(Yii::$app->user->identity->role, $allowedRoles)) {
    if ($model->status_pembayaran === 'Menunggu Bayaran') {
        echo Html::a(
            'Tandakan Bayaran Selesai',
            ['tukar-status-bayaran', 'id' => $model->id],
            ['class' => 'btn btn-success', 'data-method' => 'post']
        );
    } elseif ($model->status_pembayaran === 'Bayaran Selesai') {
        echo Html::tag('span', 'Bayaran Selesai', ['class' => 'badge badge-success']);
    }
}
?>

    <div class="tempah-asrama-pelulus">

    <div class="col-lg-12 bg-light-gray"> 
        <div class="card bg-light-gray">
            <div class="card-header bg-light-gray" style="font-size:large; font-weight:bold;">
                <h1>Senarai Tempahan Pemohon -Asrama</h1>
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
                    // 'filterModel' => $searchModel,
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
                        // [
                        //     'header' => 'Tindakan',
                        //     'class' => 'yii\grid\ActionColumn',
                        //     'template' => '{status}', // Single placeholder for status button
                        //     'buttons' => [
                        //         'status' => function ($url, $model) {
                        //             if ($model->status_tempahan_pelulus == 2) {
                        //                 // Approved status (disable button)
                        //                 return Html::button(
                        //                     'Diluluskan',
                        //                     ['class' => 'btn btn-success btn-sm', 'disabled' => true]
                        //                 );
                        //             } elseif ($model->status_tempahan_pelulus == 3) {
                        //                 // Rejected status (disable button)
                        //                 return Html::button(
                        //                     'Dibatalkan',
                        //                     ['class' => 'btn btn-danger btn-sm', 'disabled' => true]
                        //                 );
                        //             } else {
                                        
                        //                 return Html::button('Lulus', [
                        //                     'class' => 'btn btn-success btn-sm change-status',
                        //                     'data-id' => $model->id,
                        //                     'data-status' => 2,
                        //                     'confirm' => 'Adakah anda pasti untuk meluluskan tempahan ini?',
                        //                 ]) . ' ' . Html::button('Tidak Lulus', [
                        //                     'class' => 'btn btn-danger btn-sm change-status',
                        //                     'data-id' => $model->id,
                        //                     'data-status' => 3,
                        //                     'confirm' => 'Adakah anda pasti untuk tidak meluluskan tempahan ini?',
                        //                 ]);
                                        
                        //             }
                        //         },
                        //     ],
                        // ],  
                        [
                            'header' => 'Bil',
                            'class' => ActionColumn::className(),
                            'template' => '{print}',
                            'buttons' => [
                                'print' => function ($url, $model) {
                                    $hasApprovedBooking = ($model->status_tempahan_adminKemudahan == 2 && $model->status_pembayaran == 2 && $model->status_tempahan_pelulus == 2) 
                                        || ($model->status_tempahan_adminKemudahan == 5 && $model->status_pembayaran == 3 && $model->status_tempahan_pelulus == 2);
    
                                    if ($hasApprovedBooking) {
                                        return Html::a('<i class="bi bi-printer"></i>', 
                                            ['tempah-asrama/print-single', 'id' => $model->id], 
                                            [
                                                'class' => 'btn btn-primary btn-sm cetak-borang-btn',
                                                'title' => 'Cetak Borang',
                                                'data-pjax' => '0',
                                            ]
                                        );
                                    }
    
                                    return ''; // Kalau syarat tak cukup, return empty string (tak render apa-apa)
                                },
    
                            ],
                        ],     
                        [
                            'header' => 'Resit',
                            'format' => 'raw',
                            'value' => function ($model) {
                                // Semak kalau tempahan dah diluluskan + bayaran disahkan
                                $isConfirmed = (
                                    $model->status_tempahan_adminKemudahan == 5 &&
                                    $model->status_tempahan_pelulus == 2 &&
                                    $model->status_pembayaran == 3 // maybe nak support belum disahkan pun
                                );

                                if ($isConfirmed && $model->slip_pembayaran) {
                                    $slipUrl = Yii::getAlias('@web') . '/uploads/' . $model->slip_pembayaran;
                                    return Html::a('<i class="bi bi-file-earmark-text-fill" style="font-size: 1.2rem;"></i>', $slipUrl, [
                                        'target' => '_blank',
                                        'class' => 'btn btn-outline-danger btn-sm',
                                        'title' => 'Lihat Slip Pembayaran',
                                        'data-pjax' => 0,
                                    ]);
                                }

                                return '<span class="text-muted"><i>Tiada</i></span>';
                            },
                        ],
                        'no_resit',
                        // [
                        //     'label' => 'Sahkan Bayaran',
                        //     'format' => 'raw',
                        //     'visible' => in_array(Yii::$app->user->identity->role, [0, 1, 6]),
                        //     'value' => function ($model) {
                        //         $isChecked = $model->status_pembayaran === 'Bayaran Selesai' ? 'checked' : '';
                        //         return Html::beginForm(['tukar-status-bayaran', 'id' => $model->id], 'post') .
                        //             Html::checkbox('status_toggle', $isChecked, [
                        //                 'class' => 'status-toggle',
                        //                 'data-id' => $model->id,
                        //                 'onchange' => 'this.form.submit()'
                        //             ]) .
                        //             Html::endForm();
                        //     }
                        // ],
                      
                        [
                            'label' => 'Disokong Oleh',
                            'value' => function ($model) {
                                return $model->disokongOleh ? $model->disokongOleh->nama : '-';
                            
                            },
                            'format' => 'raw', // To allow HTML for fallback text
                            'contentOptions' => ['class' => 'text-center'], // Optional: Center align
                        ],    
                        [
                            'label' => 'Diluluskan Oleh',
                            'value' => function ($model) {
                                return $model->diluluskanOleh ? $model->diluluskanOleh->nama : '-';
                            
                            },
                            'format' => 'raw', // To allow HTML for fallback text
                            'contentOptions' => ['class' => 'text-center'], // Optional: Center align
                        ],    
                        [
                            'label' => 'Disahkan Oleh',
                            'value' => function ($model) {
                                return $model->disahkanBayaranOleh ? $model->disahkanBayaranOleh->nama : '-';
                            
                            },
                            'format' => 'raw', // To allow HTML for fallback text
                            'contentOptions' => ['class' => 'text-center'], // Optional: Center align
                        ],        
                        
                        'id',
                        // [
                        //     'attribute' => 'id',
                        //     'value' => function ($model) {
                        //         var_dump($model->id); // Debug
                        //         return $model->id;
                        //     }
                        // ],
                        
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
                            'contentOptions' => ['style' => 'text-align: left;'],  // ⬅️ Align teks dalam cell ke kiri
                            // 'headerOptions' => ['style' => 'text-align: left;'], 
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
                        // 'agensi_pemohon',
                        // 'tujuan',
                        // [
                        //     'attribute' => 'surat_sokongan',
                        //     'format' => 'raw',
                        //     'value' => function ($model) {
                        //         if ($model->surat_sokongan) {
                        //             return Html::a(
                        //                 'Buka Fail',
                        //                 Yii::getAlias('@web/uploads/' . $model->surat_sokongan),
                        //                 ['target' => '_blank', 'class' => 'btn btn-primary btn-sm']
                        //             );
                        //         }
                        //         return '<span class="text-danger">Tiada fail</span>';
                        //     },
                        // ],
                        // 'id_asrama',
                        // 'user.nama',
                        // 'email:email',
                        // [
                        //     'attribute' => 'jenis_penginap',
                        //     'value' => function($model) {
                        //         $jenis_penginapan = ArrayHelper::map(PenginapKategori::find()->all(), 'id', 'jenis_penginap');
                        //         return $jenis_penginapan[$model->jenis_penginap];
                        //     }
                        // ],
                        
                        // [
                        //     'label' => 'Kadar X Malam',
                        //     'value' => function($model) {
                        //         if (!$model) return null; // Prevent error when $model is undefined
                        //         $date1 = new DateTime($model->tarikh_masuk);
                        //         $date2 = new DateTime($model->tarikh_keluar);
                        //         $interval = $date1->diff($date2);
                        //         $days = $interval->days;

                        //         if ($model->jenisBilik) {
                        //             return number_format($model->jenisBilik->kadar_sewa, 2) ." X " .$days;
                        //         }

                        //         return '-';
                        //     },
                        //     'footer' => "Jumlah: ",
                        //     'footerOptions' => ['class' => 'text-end fw-bold'],
                        // ],
                        // [
                        //     'label' => 'Jumlah (RM)',
                        //     'contentOptions' => ['class' => 'text-end'],
                        //     'value' => function($model) {
                        //         if (!$model) return null; // Prevent error when $model is undefined
                        //         $date1 = new DateTime($model->tarikh_masuk);
                        //         $date2 = new DateTime($model->tarikh_keluar);
                        //         $days = $date1->diff($date2)->days;
                        //         $amount = $model->jenisBilik ? $model->jenisBilik->kadar_sewa * $days : 0;

                        //         return number_format($amount, 2);
                        //     },
                        //     'footer' => number_format($totalJumlah, 2),
                        //     'footerOptions' => ['class' => 'text-end fw-bold'],
                        // ],
                        // 'no_kp_pemohon',
                        
                    
                        // 'no_tel',
                        // 'alamat',
                        
                       
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

$this->registerJs('
$(document).ready(function() {
    console.log("DOM Ready, binding print button events...");

    $(document).on("click", ".cetak-borang-btn", function(e) {
        e.preventDefault(); // Halang default behaviour link
        let url = $(this).attr("href"); // Dapatkan URL dari href butang
        console.log("Button clicked! Opening window: " + url);
        openPrintWindow(url);
    });
});

function openPrintWindow(url) {
    let printWindow = window.open(url, "_blank", "width=900,height=700,scrollbars=yes,resizable=yes");
    if (printWindow) {
        printWindow.focus();
    } else {
        alert("Popup blocked! Please allow popups for this website.");
    }
}
');


$this->registerCss('
    /* CSS #1: Button Biru */
    .bg-blue {
        background-color:rgb(0, 105, 217) !important;
        color: #ffffff !important;
    }

    /* CSS #2: Scroll Shadow Container */
    .outer-container {
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
        border: 2px solid #ccc;
        padding: 10px;
        position: relative;
    }

    .scroll-shadow {
        height: 16px;
        overflow-x: auto;
        margin-bottom: 5px;
        display: none;
    }

    .scroll-shadow-content {
        width: max-content;
    }

    .inner-container {
        display: flex;
        overflow-x: auto;
        white-space: nowrap;
        border: 1px solid #ddd;
    }

    .scroll-item {
        flex: 0 0 auto;
        min-width: 150px;
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
        background: #888;
        border-radius: 4px;
    }

    .scroll-shadow::-webkit-scrollbar-thumb:hover,
    .inner-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .scroll-content {
        margin-top: 10px;
        scroll-behavior: smooth;
    }

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

    h1 {
        font-size: 1.5rem;
        font-weight: bold;
        color:rgb(32, 42, 111);
    }

    .table-responsive {
        overflow: hidden;
        margin-bottom: 20px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    .table thead th {
        background-color:rgb(82, 139, 185);
        color: white;
        text-decoration: none;
        text-align: center;
    }

    .table thead th a {
        color: white;
        text-decoration: none;
    }

    .table tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    .table tbody tr:nth-child(even) {
        background-color: #ffffff;
    }

    .table tbody tr:hover {
        background-color: #e9ecef;
        cursor: pointer;
    }

    .bg-light-gray {
        background-color: #ffffff;
    }

    .table tbody td {
        padding: 10px;
        text-align: center;
        vertical-align: middle;
        border-bottom: 1px solid #dee2e6;
    }

    .table, .table-container {
        margin: 0;
        padding: 0;
    }

    /* Toggle Switch */
    .status-toggle {
        width: 40px;
        height: 20px;
        position: relative;
        -webkit-appearance: none;
        background: #c6c6c6;
        outline: none;
        border-radius: 20px;
        transition: 0.3s;
    }

    .status-toggle:checked {
        background: #28a745;
    }

    .status-toggle::before {
        content: "";
        position: absolute;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        top: 1px;
        left: 1px;
        background: white;
        transition: 0.3s;
    }

    .status-toggle:checked::before {
        left: 21px;
    }
');

?>
