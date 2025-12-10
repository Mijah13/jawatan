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
use yii\widgets\ListView;
use yii\bootstrap5\LinkPager;


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

    <div class="tempah-asrama-pelulus">
    <div class="desktop-table">
    <div class="col-lg-12 bg-light-gray"> 
        <div class="card bg-light-gray">
            <div class="card-header bg-light-gray" style="font-size:large; font-weight:bold;">
                <h1>Permohonan Tempahan -Asrama</h1>
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

                        
                        [
                            'header' => 'Tindakan',
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{status}', // Single placeholder for status button
                            'buttons' => [
                                'status' => function ($url, $model) {
                                    if ($model->status_tempahan_pelulus == 2) {
                                        // Approved status (disable button)
                                        return Html::button(
                                            'Diluluskan',
                                            ['class' => 'btn btn-success btn-sm', 'disabled' => true]
                                        );
                                    } elseif ($model->status_tempahan_pelulus == 3) {
                                        // Rejected status (disable button)
                                        return Html::button(
                                            'Dibatalkan',
                                            ['class' => 'btn btn-danger btn-sm', 'disabled' => true]
                                        );
                                    } else {
                                       
                                        return Html::button('Lulus', [
                                            'class' => 'btn btn-success btn-sm change-status',
                                            'data-id' => $model->id,
                                            'data-status' => 2,
                                            'confirm' => 'Adakah anda pasti untuk meluluskan tempahan ini?',
                                        ]) . ' ' . Html::button('Tidak Lulus', [
                                            'class' => 'btn btn-danger btn-sm cancel-booking',
                                            'data-id' => $model->id,
                                            'data-status' => 3,
                                            'data-bs-toggle' => 'modal',
                                            'data-bs-target' => '#batalModal', // Trigger modal
                                            // 'confirm' => 'Adakah anda pasti untuk tidak meluluskan tempahan ini?',
                                        ]);
                                        
                                    }
                                },
                            ],
                        ],       
                        
                        [
                            'label' => 'Disokong Oleh',
                            'value' => function ($model) {
                                return $model->disokongOleh ? $model->disokongOleh->nama : 'Belum Disokong';
                            
                            },
                            'format' => 'raw', // To allow HTML for fallback text
                            'contentOptions' => ['class' => 'text-center'], // Optional: Center align
                        ],            
                        [
                            'attribute' => 'status_pembayaran',
                            'label' => 'Status Pembayaran',
                            'format' => 'raw',
                            'value' => function ($model) {
                                $statuses = [
                                    0 => ['label' => 'Belum Disemak', 'class' => 'bg-warning text-dark'], 
                                    1 => ['label' => 'Tidak Berbayar', 'class' => 'bg-blue text-white'], 
                                    2 => ['label' => 'Berbayar', 'class' => 'bg-blue text-white'],
                                    3 => ['label' => 'Bayaran Selesai', 'class' => 'bg-success text-white']
                                ];
                        
                                $status = $statuses[$model->status_pembayaran] ?? ['label' => 'Unknown', 'class' => 'bg-secondary text-white'];
                        
                                return Html::tag(
                                    'span',
                                    $status['label'],
                                    ['class' => "badge {$status['class']} px-3 py-2"]
                                );
                            },
                        ],
                        'id',
                        'user.nama',
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
                        // 'id_asrama',
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
                        'agensi_pemohon',
                        'tujuan',
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
                        // 'id_asrama',
                      
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
             </div>

             <?php
                /* ======================
                  MOBILE  – guna ListView + partial
                  ====================== */
                ?>
                
                <div class="mobile-cards">
                  <div class="card bg-light-gray">
                    <div class="card-header bg-dark" style="font-size:large; font-weight:bold;">
                        <h7>Permohonan Asrama</h7>
                    </div>
                </div>
                    <?= ListView::widget([
                        'dataProvider'  => $dataProvider,
                        'layout'        => "{items}\n{pager}",
                        'pager'         => [
                            'class'          => LinkPager::class,
                            'maxButtonCount' => 3,
                            'options'        => ['class' => 'mt-3'],
                        ],
                        'itemView'      => 'cardPelulus',   // partial view di bawah
                    ]); ?>
                </div>

    <!-- Modal -->
<div class="modal fade" id="batalModal" tabindex="-1" aria-labelledby="batalModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <form id="batal-form">
          <div class="mb-3">
            <label for="alasanBatal" class="form-label mb-bold"><strong>Alasan Pembatalan</strong></label>
            <textarea class="form-control" id="alasanBatal" name="alasan_batal" required></textarea>
          </div>
          <input type="hidden" id="tempahanId">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="modal-no-btn" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="modal-yes-btn" id="confirmBatal">Batalkan</button> 
        
      </div>
    </div>
  </div>
</div>


<!-- Loading Spinner -->
<div id="loading-overlay">
  <div class="spinner"></div>
</div>

<!-- Success Modal -->
<div id="customModal" class="modal">
  <div class="modal-content">
    <div class="modal-icon">&#10003;</div>
    <p class="modal-text" id="modalMessage">Status berjaya dikemaskini.</p>
    <!-- <button class="modal-close" onclick="closeModal()">Tutup</button> -->
    <button class="modal-close">Tutup</button>

  </div>
</div>

<!-- Confirm Modal -->
<div id="confirmModal" class="modal">
  <div class="modal-content">
    <p class="modal-text" id="confirmMessage">Anda pasti?</p>
    <div class="modal-buttons">
      <button class="modal-yes-btn" id="modalYes">Ya</button>
      <button class="modal-no-btn" id="modalNo">Tidak</button>
    </div>
  </div>
</div>

<?php
// $changeStatusPelulus = Url::to(['tempah-asrama/change-status-pelulus']);
$changeStatusPelulus = Url::to(['tempah-asrama/change-status-pelulus']);
$this->registerJsVar('changeStatusPelulus', $changeStatusPelulus);

$js = <<<JS

$(document).on("click", ".cancel-booking", function (e) {
    e.preventDefault();
    let tempahanId = $(this).data("id");

    console.log("Button batal ditekan, ID:", tempahanId); // Debug

    // Set ID dalam modal supaya butang confirmBatal boleh baca
    $("#batalModal").data("tempahan-id", tempahanId);

    $("#batalModal").modal("show");
});

// Bila admin tekan "Batalkan Tempahan"
$("#batalModal").on("click", "#confirmBatal", function () {
    let tempahanId = $("#batalModal").data("tempahan-id"); // Ambil ID dari modal
    $("#loading-overlay").show();

    console.log("Button confirmBatal ditekan!");
    console.log("Tempahan ID:", tempahanId); // Debug

    function showCustomModal(message, success = true) {
        $("#modalMessage").text(message);
        $("#modalIcon").html(success ? "&#10003;" : "&#10060;"); // Tik or X
        $("#modalIcon").css("color", success ? "#28a745" : "#dc3545"); // Hijau / Merah
        $("#customModal").fadeIn();
    }

    $(".modal-close").on("click", function () {
        $("#customModal").fadeOut();
    });

    if (!tempahanId) {
        showCustomModal("Ralat: Tiada tempahan dipilih.");
        return;
    }

    let alasan = $("#alasanBatal").val().trim();

    if (!alasan) {
        showCustomModal("Sila masukkan alasan pembatalan.");
        return;
    }

    $.post("{$changeStatusPelulus}?id=" + tempahanId, {
      status_tempahan_pelulus: 3,
      alasan_batal: alasan,
      _csrf: yii.getCsrfToken()
  })

    .done(function (data) {
        console.log("Respon berjaya:", data);
        if (data.success) {
            $("#loading-overlay").hide();
            showCustomModal("Tempahan berjaya dibatalkan.");
            $("#batalModal").modal("hide");
            // location.reload();
        } else {
            showCustomModal("Ralat: " + data.message);
        }
    })
    .fail(function (xhr, status, error) {
        console.log("XHR Response:", xhr.responseText);
        console.log("Status:", status);
        console.log("Error:", error);
        $("#loading-overlay").hide();
        showCustomModal("Ralat semasa membatalkan tempahan: " + (xhr.responseText || error));
    });

});

$(document).ready(function() {
    $(".change-status").click(function() {
        var id = $(this).data("id");
        var status = $(this).data("status");

         showConfirmModal('Adakah anda pasti ingin ubah status tempahan ini?', function () {
            $("#loading-overlay").show();

            
            $.ajax({
                url: '/tempah-asrama/change-status-pelulus?id=' + id,
                type: 'POST',
                data: {
                    id: id,
                    status_tempahan_pelulus: status,
                    _csrf: yii.getCsrfToken()
                },
                success: function(response) {
                    $("#loading-overlay").hide();

                    if (response.success) {
                        showModal("Status berjaya dikemaskini.");
                    } else {
                        showModal("Ralat: " + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    $("#loading-overlay").hide();
                    showModal("Ralat semasa mengemaskini status: " + error);
                }
            });
        });
    });
});

// Modal Functions
function showModal(message) {
    $("#modalMessage").text(message);
    $("#customModal").fadeIn();
}

function showConfirmModal(message, onConfirm) {
    $("#confirmMessage").text(message);
    $("#confirmModal").fadeIn();

    $("#modalYes").off().on("click", function () {
        $("#confirmModal").fadeOut();
        onConfirm(); // Call the function if user confirms
    });

    $("#modalNo").off().on("click", function () {
        $("#confirmModal").fadeOut();
    });
}


$(document).on('click', '.modal-close', function () {
    $("#confirmModal").fadeOut(function () {
        location.reload();
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

    .scroll-content {
      scroll-behavior: smooth;
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

    /* Loading Overlay */
#loading-overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.4);
  z-index: 9998;
  display: none;
}

.spinner {
  border: 6px solid #f3f3f3;
  border-top: 6px solid #3498db;
  border-radius: 50%;
  width: 60px;
  height: 60px;
  animation: spin 1s linear infinite;
  position: absolute;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
}

@keyframes spin {
  0% { transform: translate(-50%, -50%) rotate(0deg); }
  100% { transform: translate(-50%, -50%) rotate(360deg); }
}

/* Modal */
.modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0; top: 0;
  width: 100%; height: 100%;
  background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
  background-color: white;
  margin: 10% auto;
  padding: 30px;
  border-radius: 10px;
  width: 300px;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center; /* Ni yang penting untuk center icon */
  justify-content: center;
}


.modal-icon {
  font-size: 50px;
  color: #4CAF50;
  border: 2px solid #dff0d8;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  display: flex; /* tukar inline-flex → flex */
  align-items: center;
  justify-content: center;
  margin-bottom: 20px;
}

.modal-text {
  font-size: 14px;
  font-weight: 500;
  margin-bottom: 25px;
}

.modal-close {
  background-color: #5bc0de;
  border: none;
  color: white;
  padding: 10px 50px;
  border-radius: 8px;
  font-size: 14px;
  cursor: pointer;
}

.modal-close:hover {
  background-color: #31b0d5;
}


.modal-confirm-text {
  font-size: 14px; /* or 13px ikut suka */
  color: #111827;
  margin-bottom: 20px;
  text-align: center;
}


.modal-yes-btn {
  background-color: #3B82F6;
  border: none;
  color: white;
  padding: 10px 25px;
  border-radius: 8px;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.modal-yes-btn:hover {
  background-color: #2563EB; /* darker blue on hover */
}

.modal-no-btn {
  background-color: #E5E7EB;
  border: none;
  color: #111827;
  padding: 10px 25px;
  border-radius: 8px;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.modal-no-btn:hover {
  background-color: #D1D5DB; /* slightly darker grey on hover */
}


');

$this->registerCss('

     h1 {
        font-size: 1.5rem; /* Adjust size */
        font-weight: bold; /* Make it bold */
        color:rgb(32, 42, 111); /* Text color */
      }

      h7 {
        font-size: 17px; /* Adjust size */
        font-weight: bold; /* Make it bold */
        color:rgb(252, 252, 252); /* Text color */
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

     .modal {
    z-index: 9999 !important;
    }
    .modal-backdrop {
        z-index: 9998 !important;
    }

    /* === default desktop === */
    .mobile-cards { display: none; }

    /* Card styling (both mobile & desktop preview if needed) */
    .card-permohonan {
        background: #fff;
        border: 1px solid #e2e2e2;
        // border-radius: 12px;
    }

    /* === bila screen ≤ 768px === */
    @media (max-width: 768px) {
        .desktop-table { display: none !important; }
        .mobile-cards { display: block; }
    }

');
?>
