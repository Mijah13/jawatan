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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\TempahAsramaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'MyFasiliti';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
?>
<?php
$hasApprovedBooking = false;

    // Check if there is at least one approved booking
    foreach ($dataProvider->models as $model) {
        if ($model->status_tempahan_adminKemudahan == 2 || $model->status_tempahan_adminKemudahan == 3) {
            $hasApprovedBooking = true;
            break;
        }
    }
?>
<div class="tempah-asrama-index">

    <!-- Add Button -->
    <div class="text-right mb-3">
        <?= Html::a('<i class="bi bi-plus-circle"></i> <span class="fw-normal">Tambah Tempahan</span>', ['asrama/bilik'], ['class' => 'btn btn-success shadow-sm text-white']) ?>
    </div>

    <div class="col-lg-12 bg-light-gray"> 
    <div class="card bg-light-gray">
        <div class="card-header bg-light-gray" style="font-size:large; font-weight:bold;">
            <h1>Senarai Tempahan Pelajar</h1>
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
            'class' => 'yii\bootstrap5\LinkPager'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // ['class' => 'yii\grid\CheckboxColumn', 'checkboxOptions' => ['class' => 'bulk-select']],
            
            [
                'header' => 'Tindakan',
                'class' => ActionColumn::className(),
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="bi bi-eye"></i>', $url, [
                            'class' => 'btn btn-sm btn-primary',
                            'title' => 'Lihat Maklumat',
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<i class="bi bi-pencil"></i>', $url, [
                            'class' => 'btn btn-sm btn-warning',
                            'title' => 'Kemaskini Maklumat',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="bi bi-trash"></i>', $url, [
                            'class' => 'btn btn-sm btn-danger',
                            'title' => 'Padam Pengguna',
                            'data-confirm' => 'Adakah anda pasti untuk memadam tempahan ini?',
                            'data-method' => 'post',
                        ]);
                    },
                ],
            ],
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

            'id',
            // 'id_asrama',
            [
                'attribute' => 'id_asrama',
                'label' => 'Bilik Asrama',
                'format' => 'raw',
                'value' => function ($model) {
                    // Ambil jenis_bilik yang user tempah
                    $jenisBilikUserTempah = TempahAsrama::find()
                        ->select('jenis_bilik')
                        ->where(['id' => $model->id])
                        ->scalar(); // Dapatkan satu nilai je
            
                    // Filter bilik asrama ikut jenis_bilik & pastikan bilik available (status_asrama == 0)
                    $asramaList = Asrama::find()
                        ->joinWith('jenisAsrama') // Pastikan ada relation dalam model
                        ->where([
                            'asrama.jenis_asrama_id' => $jenisBilikUserTempah,
                            'asrama.status_asrama' => 0, // Pastikan bilik boleh ditempah
                        ])
                        ->all();
            
                    // Buat array untuk dropdown
                    $asramaDropdown = ArrayHelper::map($asramaList, 'id', function ($data) {
                        return "Blok {$data->blok} - Aras {$data->aras} - No Bilik {$data->no_asrama} - {$data->jenisAsrama->jenis_bilik}";
                    });
            
                    $selectedBilik = $model->id_asrama ?? null; // Ambil ID bilik kalau dah ada, kalau tak NULL

                    return Html::dropDownList("id_asrama[{$model->id}]", $selectedBilik,  
                        ['' => 'Sila Pilih Bilik'] + $asramaDropdown,  
                        [
                            'class' => 'form-control bilik-pilihan', 
                            'data-tempahan-id' => $model->id,
                            'data-set' => !empty($model->id_asrama) ? '1' : '0' // Kalau dah set bilik, data-set = 1
                        ]  
                    );

                },
                'filter' => function ($model) {
                    // Sama konsep, tapi untuk filter pada GridView search
                    $jenisBilikUserTempah = TempahAsrama::find()
                        ->select('jenis_bilik')
                        ->where(['id' => $model->id])
                        ->scalar();
            
                    return ArrayHelper::map(
                        Asrama::find()->joinWith('jenisAsrama')
                            ->where([
                                'asrama.jenis_asrama_id' => $jenisBilikUserTempah,
                                'asrama.status_asrama' => 0, // Pastikan bilik boleh ditempah
                            ])
                            ->all(),
                        'id',
                        function ($data) {
                            return "Blok {$data->blok} - Aras {$data->aras} - No Bilik {$data->no_asrama} - {$data->jenisAsrama->jenis_bilik}";
                        }
                    );
                },
            ],
            'user.nama',
            'no_matrik_pemohon',
            'no_kp_pemohon',
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
                'label' => 'Jenis Bilik', //incase kalau duduk blok R
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
            'masalah_kesihatan',
        ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
        </div>
        
        <!-- Bottom scrollbar -->
        <div class="scroll-shadow">
            <div class="scroll-shadow-content"></div>
        </div>
    </div>
    </div>

    </div>
    </div>
    <br>
    <?php if ($dataProvider->getCount() > 0 && $hasApprovedBooking): ?>
        <?= Html::button('<i class="bi bi-printer"></i> Cetak Borang', [
            'class' => 'btn btn-success',
            'id' => 'cetak-borang-btn',
        ]) ?>
        <?= Html::a('<i class="bi bi-file-earmark-excel"></i> Export Excel', 
        ['tempah-asrama/export-excel'], 
        ['class' => 'btn btn-success']) ?>

    <?php endif; ?>

<?php
// $saveUrl = Url::to(['tempah-asrama/save-statuses']);
$changeStatus = Url::to(['tempah-asrama/change-status']);

$js = <<<JS
$(document).ready(function(){

    $(document).ready(function(){
    // **1. Apply warna hijau bila page load**
    $(".bilik-pilihan").each(function(){
        if ($(this).data("set") == "1") {
            $(this).css("background-color", "#d4edda"); // Hijau kalau dah set
        }
    });

    // **2. Bila dropdown change, update bilik**
    $(".bilik-pilihan").change(function(){
        var dropdown = $(this);
        var idTempahan = dropdown.data("tempahan-id");
        var idBilik = dropdown.val();

        if (idBilik === "") return;

        if (confirm("Set bilik untuk tempahan ini?")) {
            $.post('set-bilik?id=' + idTempahan, {
                'id_asrama': idBilik, 
                '_csrf': yii.getCsrfToken() 
            }, function(data) {
                if (data.success) {
                    alert('Bilik berjaya dikemaskini.');
                    
                    // Pastikan dropdown kekal dengan bilik yang dipilih
                    dropdown.val(idBilik);

                    // **Update color & set data-set supaya kekal lepas reload**
                    dropdown.css("background-color", "#d4edda").data("set", "1");
                } else {
                    alert('Ralat: ' + data.message);
                }
            }).fail(function(xhr, status, error) {
                alert('Ralat semasa mengemaskini bilik: ' + error);
            });
        }

        dropdown.trigger('blur');
    });
});

});

JS;

$this->registerJs($js);
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
    console.log("DOM Ready, trying to bind jQuery event...");
    $("#cetak-borang-btn").on("click", function() {
        let url = "' . \yii\helpers\Url::to(["tempah-asrama/print-senarai-pelajar", "id" => $model->id], true) . '";
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

    .table tfoot {
        font-weight: bold;
        background-color: #f1f3f5; /* Footer background */
        color: #333; /* Footer text color */
    }
    
    .table tfoot td {
    padding: 10px; /* Add padding directly to footer cells */
    text-align: right; /* Align footer content (e.g., total) to the right */
    border-top: 1px solid #dee2e6; /* Add a border to separate footer from body */
    }

    /* Remove any unintended margin or padding */
    .table, .table-container {
        margin: 0;
        padding: 0;
    }
');
?>
