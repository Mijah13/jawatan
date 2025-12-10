<?php

use app\models\TempahFasiliti;
use app\models\Fasiliti;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\ActionColumn;
use yii\grid\GridView;
// use yii\widgets\Pjax;


/** @var yii\web\View $this */
/** @var app\models\TempahFasilitiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'MyFasiliti';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
?>
<div class="tempah-fasiliti-index">

    <!-- Add Button -->
    <div class="text-right mb-3">
        <?= Html::a('<i class="bi bi-plus-circle"></i> <span class="fw-normal">Tambah Tempahan</span>', ['fasiliti/senarai-fasiliti'], ['class' => 'btn btn-success shadow-sm text-white']) ?>
    </div>

    <div class="col-lg-12 bg-light-gray"> 
        <div class="card bg-light-gray">
        <div class="card-header bg-light-gray" style="font-size:large; font-weight:bold;">
            <h1>Senarai Permohonan Tempahan -Fasiliti</h1>
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
                    'maxButtonCount' => 3, // letak sini untuk efek direct
                    'firstPageLabel' => '«',  // First page
                    'lastPageLabel' => '»',   // Last page
                    'prevPageLabel' => false,  // buang prev
                    'nextPageLabel' => false,  // buang next
                    'options' => ['class' => 'mt-3'] // Bootstrap 5 margin-top 3 (setara 20px)
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn', 'header' => 'No.'],

                    'id',
                    'user.nama',
                    // 'fasiliti_id',
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
                    // 'no_kp_pemohon',
                    // 'no_tel',
                    // 'alamat',
                    // 'email:email',
                    // 'tempoh',
                    // 'jangkaan_hadirin',
                    [
                        'attribute' => 'status_pembayaran',
                        'label' => 'Status Pembayaran',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $statuses = [0 => 'Belum Ditetapkan', 1 => 'Tidak Berbayar', 2 => 'Berbayar'];
                            return Html::dropDownList(
                                "status_pembayaran[{$model->id}]",
                                $model->status_pembayaran,
                                $statuses,
                                [
                                    'class' => 'form-control status-pembayaran',
                                    'id' => 's-'.$model->id,
                                ]
                            );
                        },
                    ],
                    [
                        'attribute' => 'status_tempahan_adminKemudahan',
                        'label' => 'Status Tempahan',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $statuses = [
                                1 => 'Sedang Diproses',
                                2 => 'Menunggu bayaran',
                                3 => 'Disokong',
                              
                            ];
                        
                            return Html::dropDownList(
                                "status_tempahan_adminKemudahan[{$model->id}]",
                                $model->status_tempahan_adminKemudahan,
                                $statuses,
                                [
                                    'class' => 'form-control status-tempahan-adminKemudahan',
                                    'id' => 's-'.$model->id,
                                    'data-previous' => $model->status_tempahan_adminKemudahan // Simpan nilai asal
                                ]
                            );
                        },
                    ],
                    
                    [
                        'attribute' => 'diluluskan_oleh',
                        'label' => 'Pelulus Pilihan',
                        'format' => 'raw',
                        'value' => function ($model) {
                            // Ambil senarai user yang role = 2 (Pelulus)
                            $pelulusList = ArrayHelper::map(
                                \app\models\User::find()->where(['role' => 2])->all(),
                                'id',
                                'nama' // Pastikan dalam table user ada field 'nama'
                            );
                    
                            return Html::dropDownList(
                                "diluluskan_oleh[{$model->id}]",
                                $model->diluluskan_oleh, // Ini untuk set selected value
                                ['' => 'Pilih Pelulus'] + $pelulusList,
                                [
                                    'class' => 'form-control pelulus-pilihan',
                                    'data-tempahan-id' => $model->id
                                ]
                            );
                        },
                        'filter' => ArrayHelper::map(
                            \app\models\User::find()->where(['role' => 2])->all(),
                            'id',
                            'nama'
                        ),
                    ],
                    [
                        'header' => 'Tindakan',
                        'class' => ActionColumn::className(),
                        'template' => '{view} {cancel}',
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
                                return Html::a('<i class="bi bi-trash"></i>', 'javascript:void(0)', [
                                    'class' => 'btn btn-sm btn-danger btn-delete-item',
                                    'title' => 'Padam Rekod',
                                    'data-url' => $url,
                                    // 'data-confirm' => 'Adakah anda pasti untuk memadam pengguna ini?',
                                    // 'data-method' => 'post',
                                ]);
                            },
                            'cancel' => function ($url, $model) {
                                return Html::a('<i class="bi bi-x-circle"></i>', 'javascript:void(0);', [
                                    'class' => 'btn btn-sm btn-secondary cancel-booking',
                                    'title' => 'Batal Tempahan',
                                    'data-id' => $model->id,
                                    // 'data-confirm' => 'Adakah anda pasti untuk membatalkan tempahan ini?',
                                    'data-bs-toggle' => 'modal',
                                    'data-bs-target' => '#batalModal', // Trigger modal
                                ]);
                            },
                        ],
                    ],
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

    <!-- Modal -->
<div class="modal fade" id="batalModal" tabindex="-1" aria-labelledby="batalModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- <div class="modal-header">
        <h5 class="modal-title" id="batalModalLabel">Sahkan Pembatalan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div> -->
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

  <!-- Confirm Modal -->
<div id="confirmModal" class="modal">
  <div class="modal-content">
    <p class="modal-text" id="confirmMessage">Adakah anda pasti?</p>
    <div class="modal-buttons">
      <button class="modal-yes-btn" id="modalYes">Ya</button>
      <button class="modal-no-btn" id="modalNo">Tidak</button>
    </div>
  </div>
</div>

<!-- Custom Modal (Success + Error) -->
<div id="customModal" class="modal">
  <div class="modal-content">
    <div class="modal-icon" id="modalIcon"></div>
    <p class="modal-text" id="modalMessage"></p>
    <button class="modal-close">Tutup</button>
  </div>
</div>


<?php
$changeStatus = Url::to(['tempah-fasiliti/change-status']);

$js = <<<JS


$(document).ready(function () {
    $(".status-tempahan-adminKemudahan").prop("disabled", true); // Disable dropdown awal-awal
    $(".status-tempahan-adminKemudahan").css({
        "pointer-events": "none",
        "cursor": "default"
    });

    $(".status-tempahan-adminKemudahan").on("mousedown", function (e) {
        // Dapatkan ID baris dropdown ini
        let id = $(this).attr("id")?.replace(/^s-/, "");

        // Check status_pembayaran yang berkait
        let pembayaran = $("#s-" + id + ".status-pembayaran").val();

        // Kalau user belum pilih status pembayaran (kosong/null/0)
        if (!pembayaran) {
            showCustomModal("Anda perlu pilih status pembayaran terlebih dahulu.");
            e.preventDefault(); // Halang interaction walaupun pointer-events: auto
        }
    });

    $(".status-pembayaran").change(function () {
        var id = $(this).attr("id").substr(2);
        var statusPembayaran = $("#s-" + id + ".status-pembayaran").val();
        var statusTempahanDropdown = $("#s-" + id + ".status-tempahan-adminKemudahan");
        var statusTempahan = statusTempahanDropdown.val(); // Ambil status tempahan sebelum ubah

        // Simpan pilihan semasa untuk reset kalau perlu
        var defaultOptions = {
            1: "Sedang Diproses",
            2: "Menunggu bayaran",
            3: "Disokong",
        };

        // Logic perubahan dropdown ikut status pembayaran
        if (statusPembayaran == 1) { 
            statusTempahanDropdown.find("option[value='2']").remove(); // Hide "Menunggu Bayaran"
            statusTempahanDropdown.val(3); // Auto set ke "Dibatalkan"
            statusTempahanDropdown.css("background-color", "#f8d7da"); // Merah lembut
        } else if (statusPembayaran == 2) { 
            statusTempahanDropdown.find("option[value='3']").remove(); // Hide "Diluluskan"
            statusTempahanDropdown.val(2); // Auto set ke "Menunggu Bayaran"
            statusTempahanDropdown.css("background-color", "#5a90d1"); // Biru
        } else {
            // Reset balik semua pilihan
            statusTempahanDropdown.empty();
            $.each(defaultOptions, function (key, value) {
                statusTempahanDropdown.append(new Option(value, key));
            });
            statusTempahanDropdown.css("background-color", "#ffffff"); // Reset warna
        }

        // **Fix utama: Ambil status terbaru lepas auto-update**
        var statusTempahanFinal = statusTempahanDropdown.val();

        
        function showConfirmModal(message, onYes, onNo) {
            $("#confirmMessage").text(message);
            $("#confirmModal").fadeIn();

            $("#modalYes").off("click").on("click", function () {
                $("#confirmModal").fadeOut();
                if (onYes) onYes();
            });

            $("#modalNo").off("click").on("click", function () {
                $("#confirmModal").fadeOut();
                if (onNo) onNo();
            });
        }

        function showCustomModal(message, success = true) {
            $("#modalMessage").text(message);
            $("#modalIcon").html(success ? "&#10003;" : "&#10060;"); // Tik or X
            $("#modalIcon").css("color", success ? "#28a745" : "#dc3545"); // Hijau / Merah
            $("#customModal").fadeIn();
        }

        $(".modal-close").on("click", function () {
            $("#customModal").fadeOut();
        });

        // Confirm sebelum ubah status
        showConfirmModal("Ubah status tempahan/pembayaran?", function () {
            $.post('$changeStatus' + '?id=' + id, {
                'status_tempahan_adminKemudahan': statusTempahanFinal,
                'status_pembayaran': statusPembayaran,
                '_csrf': yii.getCsrfToken()
            }, function (data) {
                if (data.success) {
                    showCustomModal("Status berjaya dikemaskini.");
                } else {
                    showCustomModal("Ralat: " + data.message, false);
                }
            }).fail(function (xhr, status, error) {
                showCustomModal("Ralat semasa mengemaskini status: " + error, false);
            });
        }, function () {
            // Bila user tekan "Tidak"
            $("#s-" + id + ".status-pembayaran").val($("#s-" + id + ".status-pembayaran").data("previous"));
        });

        // Simpan pilihan terbaru untuk revert kalau perlu
        $(this).data("previous", $(this).val());
    });
});

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

    if (!tempahanId) {
        showCustomModal("Ralat: Tiada tempahan dipilih.");
        return;
    }

    let alasan = $("#alasanBatal").val().trim();

    if (!alasan) {
        showCustomModal("Sila masukkan alasan pembatalan.");
        return;
    }

    function showConfirmModal(message, onYes, onNo) {
        $("#confirmMessage").text(message);
        $("#confirmModal").fadeIn();

        $("#modalYes").off("click").on("click", function () {
            $("#confirmModal").fadeOut();
            if (onYes) onYes();
        });

        $("#modalNo").off("click").on("click", function () {
            $("#confirmModal").fadeOut();
            if (onNo) onNo();
        });
    }

    function showCustomModal(message, success = true) {
        $("#modalMessage").text(message);
        $("#modalIcon").html(success ? "&#10003;" : "&#10060;"); // Tik or X
        $("#modalIcon").css("color", success ? "#28a745" : "#dc3545"); // Hijau / Merah
        $("#customModal").fadeIn();
    }

    $(".modal-close").on("click", function () {
        $("#customModal").fadeOut();
    });

    $.post("$changeStatus?id=" + tempahanId, {
        status_tempahan_adminKemudahan: 4,
        alasan_batal: alasan,
        _csrf: yii.getCsrfToken()
    })
    .done(function (data) {
        console.log("Respon berjaya:", data);
        if (data.success) {
            $("#loading-overlay").hide();
            showCustomModal("Tempahan berjaya dibatalkan.");
            $("#batalModal").modal("hide");
            location.reload();
        } else {
            showCustomModal("Ralat: " + data.message);
        }
    })
    .fail(function (xhr, status, error) {
        console.log("XHR Response:", xhr.responseText);
        console.log("Status:", status);
        console.log("Error:", error);
        $("#loading-overlay").hide();
        showCustomModal("Ralat semasa membatalkan tempahan: " + xhr.responseText);
    });
});

$(document).on('click', '.btn-delete-item', function (e) {
    e.preventDefault();

    const url = $(this).data('url');

    
    function showConfirmModal(message, onYes, onNo) {
        $("#confirmMessage").text(message);
        $("#confirmModal").fadeIn();

        $("#modalYes").off("click").on("click", function () {
            $("#confirmModal").fadeOut();
            if (onYes) onYes();
        });

        $("#modalNo").off("click").on("click", function () {
            $("#confirmModal").fadeOut();
            if (onNo) onNo();
        });
    }

    function showCustomModal(message, success = true) {
        $("#modalMessage").text(message);
        $("#modalIcon").html(success ? "&#10003;" : "&#10060;"); // Tik or X
        $("#modalIcon").css("color", success ? "#28a745" : "#dc3545"); // Hijau / Merah
        $("#customModal").fadeIn();
    }

    $(".modal-close").on("click", function () {
        $("#customModal").fadeOut();
    });

    showConfirmModal("Adakah anda pasti mahu padam item ini?", function () {
        $.post(url, {
            _csrf: yii.getCsrfToken()
        })
        .done(function (data) {
            if (data.success) {
                showCustomModal("Item berjaya dipadam.", true);
                // Optional: reload page or GridView
                location.reload();
            } else {
                showCustomModal("Ralat: " + (data.message || "Gagal padam item."), false);
            }
        })
        .fail(function (xhr, status, error) {
            showCustomModal("Ralat sistem: " + error, false);
        });
    });
});


$(document).on('change', '.pelulus-pilihan', function () {
    var tempahanId = $(this).data('tempahan-id');
    var pelulusId = $(this).val();

    
    function showConfirmModal(message, onYes, onNo) {
        $("#confirmMessage").text(message);
        $("#confirmModal").fadeIn();

        $("#modalYes").off("click").on("click", function () {
            $("#confirmModal").fadeOut();
            if (onYes) onYes();
        });

        $("#modalNo").off("click").on("click", function () {
            $("#confirmModal").fadeOut();
            if (onNo) onNo();
        });
    }

    function showCustomModal(message, success = true) {
        $("#modalMessage").text(message);
        $("#modalIcon").html(success ? "&#10003;" : "&#10060;"); // Tik or X
        $("#modalIcon").css("color", success ? "#28a745" : "#dc3545"); // Hijau / Merah
        $("#customModal").fadeIn();
    }

    $(".modal-close").on("click", function () {
        $("#customModal").fadeOut();
    });

    showConfirmModal('Adakah anda pasti?', function () {
        $("#loading-overlay").show();
        // $('.pelulus-pilihan').prop('disabled', true);

    $.ajax({
        url: '/tempah-fasiliti/update-pelulus',
        type: 'POST',
        data: {
            id: tempahanId, 
            diluluskan_oleh: pelulusId,
            _csrf: yii.getCsrfToken()
        },
        success: function(response) {
            $("#loading-overlay").hide();
            // $('.pelulus-pilihan').prop('disabled', false);

            if (response.success) {
                 showCustomModal("Pelulus berjaya disimpan.");
            } else {
                showCustomModal("Ralat: " + response.message, false);
            }
        },
        error: function (xhr, status, error) {
            $("#loading-overlay").hide();
            //  $('.pelulus-pilihan').prop('disabled', false);
            showCustomModal("Ralat semasa mengemaskini pelulus: " + error, false);
        }
        });
    });
});

// Modal Functions
// function showModal(message) {
//     $("#modalMessage").text(message);
//     $("#customModal").fadeIn();
// }

// function showConfirmModal(message, onConfirm) {
//     $("#confirmMessage").text(message);
//     $("#confirmModal").fadeIn();

//     $("#modalYes").off().on("click", function () {
//         $("#confirmModal").fadeOut();
//         onConfirm(); // Call the function if user confirms
//     });

//     $("#modalNo").off().on("click", function () {
//         $("#confirmModal").fadeOut();
//     });
// }


$(document).on('click', '.modal-close', function () {
    $("#customModal").fadeOut(function () {
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

    .table-responsive .table tbody tr:nth-child(odd) {
        background-color: #f9f9f9 !important;
    }

    .table-responsive .table tbody tr:nth-child(even) {
        background-color: #ffffff !important;
    }


    .bg-light-gray {
        background-color: #ffffff;
    }

    .table tbody tr:hover {
        background-color: #e9ecef; /* Highlight row on hover */
        cursor: pointer;
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
');
?>

