<?php

use app\models\TempahFasiliti;
use app\models\Fasiliti;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\TempahFasilitiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'MyFasiliti';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
?>
<?php
 // Assuming $models contains all rows
 $totalJumlah = 0;
 foreach ($dataProvider->models as $model) {
     if ($model->status_tempahan_adminKemudahan == 2 && $model->status_pembayaran == 2) { // Include only tempahan with status 2
         $totalJumlah += $model->calculateJumlah();
     }
 }
 
 ?>
 
<div class="tempah-fasiliti-pelulus">
<div class="col-lg-12 bg-light-gray"> 
    <div class="card bg-light-gray">
        <div class="card-header bg-light-gray" style="font-size:large; font-weight:bold;">
            <h1>Senarai Tempahan Pemohon -Fasiliti</h1>
        </div>
        <div className="overflow-auto max-h-[500px] scrollbar-thin scrollbar-thumb-gray-500 scrollbar-track-gray-300">
        <div class="scroll-shadow">
        </div>
        <div id="inner-container" class="inner-container">
            <div class="card-body">
            <br>
            <div class="table-responsive">
            <?php Pjax::begin(); ?>
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
                                        ['tempah-fasiliti/print-single', 'id' => $model->id], 
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
                        'label' => 'Sahkan Bayaran',
                        'format' => 'raw',
                        'visible' => in_array(Yii::$app->user->identity->role, [0, 1, 6]),
                        'value' => function ($model) {
                            $isChecked = $model->status_pembayaran === 'Bayaran Selesai' ? 'checked' : '';
                            return Html::beginForm(['tukar-status-bayaran', 'id' => $model->id], 'post') .
                                Html::checkbox('status_toggle', $isChecked, [
                                    'class' => 'status-toggle',
                                    'data-id' => $model->id,
                                    'onchange' => 'this.form.submit()'
                                ]) .
                                Html::endForm();
                        }
                    ],
                    
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
                    // 'id_fasiliti',
                    // 'user.nama',
                    
                    [
                        'label' => 'Jenis Fasiliti',
                        'attribute' => 'fasiliti_id',
                        'value' => function($model) {
                            return $model->fasiliti->nama_fasiliti ?? 'Unknown';
                        },
                        'filter' => \yii\helpers\ArrayHelper::map(
                            \app\models\Fasiliti::find()->all(),
                            'id',
                            'nama_fasiliti'
                        ),
                        'filterInputOptions' => [
                            'class' => 'form-select',
                        ],
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
                    // [
                    //     'label' => 'Kadar X Tempoh',
                    //     'value' => function ($model) {
                    //         // Check if related fasiliti exists
                    //         if (!$model->fasiliti) {
                    //             return 'Fasiliti tidak tersedia';
                    //         }
                    
                    //         // Mapping session durations (in hours)
                    //         $durations = [
                    //             'sesiPagi' => 3,
                    //             'sesiPetang' => 3,
                    //             'sesiMalam' => 3,
                    //             'sesiPagiPetang' => 6,
                    //             // 'satuHari' => 8,
                    //         ];
                    
                    //         // Mapping tempoh labels
                    //         $labels = [
                    //             'sesiPagi' => 'Sesi pagi',
                    //             'sesiPetang' => 'Sesi petang',
                    //             'sesiMalam' => 'Sesi malam',
                    //             'sesiPagiPetang' => 'Pagi - Petang',
                    //             'satuHari' => 'Satu Hari',
                    //         ];
                    
                    //         $rate = 0;
                    //         switch ($model->tempoh) {
                    //             case 'sesiPagi':
                    //             case 'sesiPetang':
                    //             case 'sesiPagiPetang':
                    //                 // Check for siang or general hourly rate
                    //                 $rate = $model->fasiliti->kadar_sewa_perJamSiang ?? $model->fasiliti->kadar_sewa_perJam ?? 0;
                    //                 break;
                    //             case 'sesiMalam':
                    //                 // Use night rate
                    //                 $rate = $model->fasiliti->kadar_sewa_perJamMalam ?? 0;
                    //                 break;
                    //             case 'satuHari':
                    //                 // Use full-day rate
                    //                 $rate = $model->fasiliti->kadar_sewa_perHari ?? 0;
                    //                 break;
                    //         }
                    
                    //         // Get the hourly/daily rate based on the selected session
                    //         $hourlyOrDailyRate = $rateTypeMapping[$model->tempoh] ?? 0;

                    //         if ($model->tempoh === 'satuHari') {
                    //             // Calculate the number of days for 'satuHari'
                    //             $date1 = new \DateTime($model->tarikh_masuk);
                    //             $date2 = new \DateTime($model->tarikh_keluar);
                    //             $days = $date1->diff($date2)->days;
                    //             $days = $days > 0 ? $days : 1; // At least 1 day

                    //             return "Satu Hari = RM{$rate} x {$days} Hari";
                    //         }

                    //         // For other sessions
                    //         $selectedTempoh = $labels[$model->tempoh] ?? 'Tidak dipilih';
                    //         $selectedDuration = $durations[$model->tempoh] ?? 0;

                    //         return "{$selectedTempoh} = RM{$rate} x {$selectedDuration} jam";
                    //     },
                    //     'footer' => "Jumlah: ",
                    //     'footerOptions' => ['class' => 'text-end fw-bold'],
                    // ],
                    // [
                    //     'label' => 'Jumlah (RM)',
                    //     'contentOptions' => ['class' => 'text-end'],
                    //     'value' => function ($model) {
                    //         // Calculate the amount for each row
                    //         $jumlah = $model->calculateJumlah(); // Ensure calculateJumlah() is defined in your model
                    //         return $jumlah !== null ? number_format($jumlah, 2) : '0.00';
                    //     },
                    //     'footer' => number_format($totalJumlah, 2), // Footer to display total of all rows
                    //     'footerOptions' => ['class' => 'text-end fw-bold'],
                    // ],
                    // 'no_kp_pemohon',
                  
                    // 'no_tel',
                    // 'alamat',
                    // 'email:email',
                   
                   
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
