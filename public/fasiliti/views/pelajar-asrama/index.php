<?php

use app\models\PelajarAsrama;
use app\models\JenisAsrama;
use app\models\Asrama;
use app\models\PenginapKategori;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\bootstrap\Modal;
// use kartik\grid\GridView; // ini yang kita nak

// use yii\jui\DatePicker;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\widgets\Pjax;
use kartik\select2\Select2;
// use kartik\date\DatePicker;
// use kartik\grid\EditableColumn;
// use kartik\editable\Editable;

/** @var yii\web\View $this */
/** @var app\models\PelajarAsramaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'MyFasiliti';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');

// dapatkan list sesi_batch dari model atau static
$listTahun = \app\models\PelajarAsrama::find()
    ->select(['sesi_batch'])
    ->distinct()
    ->orderBy(['sesi_batch' => SORT_DESC])
    ->indexBy('sesi_batch')
    ->column();


$kodKursusList = ArrayHelper::map(
    PelajarAsrama::find()
        ->select(['kod_kursus'])
        ->distinct()
        ->orderBy(['kod_kursus' => SORT_ASC])
        ->asArray()
        ->all(),
    'kod_kursus',
    'kod_kursus'
);

?>

<div class="pelajar-asrama-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="card1 shadow rounded border-0 p-2 mb-4">
        <div class="card-body d-flex justify-content-center">
            <?php
            echo Html::beginForm(['pelajar-asrama/index'], 'get', ['class' => 'form-inline', 'style' => 'margin-bottom:20px']);

            echo '<div class="d-flex align-items-center">';
            echo '<label for="sesi_batch" class="fw-bold me-2" style="white-space: nowrap;">Pilih Sesi Batch:</label>';
            echo Html::dropDownList('sesi_batch', $sesiBatch, $listTahun, [
                'prompt' => 'Belum Diproses',
                'class' => 'form-select form-select-sm',
                'style' => 'border-radius: 25px; padding: 0.4rem 1.55rem; font-weight: 500;',

                'onchange' => 'this.form.submit()',
            ]);
            echo '</div>';
            echo Html::endForm();
            ?>
            <?php if ($sesiBatch): ?>
                <div class="mt-0">
                    <button class="btn btn-sm px-4 py-2" id="btnTetapkanTarikh" style="
                        background: linear-gradient(135deg, #0069d9, #0056b3);
                        color: white;
                        font-weight: 500;
                        border: none;
                        border-radius: 30px;
                        box-shadow: 0 4px 10px rgba(0, 105, 217, 0.2);
                        transition: all 0.3s ease;
                    " onmouseover="this.style.background='linear-gradient(135deg,#0056b3,#004494)'" 
                    onmouseout="this.style.background='linear-gradient(135deg,#0069d9,#0056b3)'">
                        <i class="fas fa-calendar-check me-2"></i> 
                        <span class="d-none d-md-inline">Tetapkan Tarikh Keluar</span>
                    </button>
                </div>
            <?php endif; ?>

        </div>
    </div>

    

    <div class="col-lg-12 bg-light-gray"> 
    <div class="card bg-light-gray">
        <div class="card-header bg-light-gray" style="font-size:large; font-weight:bold;">
        <h1>Senarai Tempahan Pelajar</h1>
    </div>
    <div class="overflow-auto max-h-[500px] scrollbar-thin scrollbar-thumb-gray-500 scrollbar-track-gray-300">
    <div class="scroll-shadow">
    </div>
    <div id="inner-container" class="inner-container">
    <div class="card-body">
    <br>
    <div class="table-responsive">
    <?php if (empty($sesiBatch)): ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider1,
        'filterModel' => $searchModel1,
        'pager' => [
                    'class' => 'yii\bootstrap5\LinkPager',
                    'maxButtonCount' => 3, 
                    'options' => ['class' => 'mt-3'] 
                ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' => 'No.'],
            'id',
            // [
            //     'attribute' => 'id_asrama',
            //     'label' => 'Bilik Asrama',
            //     'format' => 'raw',
            //     'value' => function ($model) use ($asramaList) {
            //         $selected = $model->id_asrama;
            //         return Select2::widget([
            //             'name' => 'id_asrama_' . $model->id, // Make unique per row
            //             'value' => $selected,
            //             'data' => $asramaList,
            //             'options' => [
            //                 'class' => 'form-control bilik-pilihan', // Kelas bilik-pilihan
            //                 'placeholder' => 'Pilih Bilik...',
            //                 'id' => 'select2-asrama-' . $model->id,
            //                 'data-tempahan-id' => $model->id, // Pastikan ID tempahan ada di sini
            //                 'data-set' => !empty($model->id_asrama) ? '1' : '0' // Untuk warnakan dropdown
            //             ],
            //             // 'pluginOptions' => ['allowClear' => true],
            //             'bsVersion' => '5.x',
            //         ]);
            //     },
            //     'filter' => $asramaList,
            // ],
             [
                'attribute' => 'id_asrama',
                'label' => 'Bilik Asrama',
                'format' => 'raw',
               'value' => function ($model) use ($asramaList) {
                    return Select2::widget([
                        'name' => 'id_asrama_' . $model->id,
                        'value' => $model->id_asrama, // value ni penting — direct dari DB
                        'data' => $asramaList,
                        'options' => [
                            'placeholder' => 'Pilih Bilik...',
                            'id' => 'select2-asrama-' . $model->id,
                            'data-tempahan-id' => $model->id,
                            'class' => 'form-control bilik-pilihan',
                            'data-set' => $model->id_asrama ? '1' : '0',
                        ],
                        'bsVersion' => '5.x',
                    ]);
                },

                'filter' => $asramaList,
            ],
            
            [
                'attribute' => 'tarikh_masuk',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::input('date', 'tarikh_masuk_' . $model->id, $model->tarikh_masuk, [
                        'class' => 'form-control',
                        'data-id' => $model->id,
                        'data-field' => 'tarikh_masuk',
                        'style' => 'min-width: 160px;',
                    ]);
                },
            ],
            // [
            //     'attribute' => 'tarikh_keluar',
            //     'format' => 'raw',
            //     'value' => function($model) {
            //         return Html::input('date', 'tarikh_keluar_' . $model->id, $model->tarikh_keluar, [
            //             'class' => 'form-control',
            //             'data-id' => $model->id,
            //             'data-field' => 'tarikh_keluar',
            //             'style' => 'min-width: 160px;',
            //         ]);
            //     },
            // ],

            [
                'attribute' => 'nama',
                'value' => 'user.nama',
            ],

            'no_kp',
            'no_tel',
            'email:email',
            'kod_kursus',
            'sesi_batch',
            //  [
            //     'attribute' => 'status_penginapan',
            //     'label' => 'Status Penginapan',
            //     'format' => 'raw',
            //     'value' => function ($model) {
            //         $statusPenginapan = [
            //             0 => 'Dalam',
            //             1 => 'Luar',
            //         ];
            //         return $statusPenginapan[$model->status_penginapan] ?? 'Tidak Diketahui';
            //     },
            //     'filter' => [
            //         0 => 'Dalam',
            //         1 => 'Luar',
            //     ],
            // ],
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
                        return Html::a('<i class="bi bi-trash"></i>', 'javascript:void(0)', [
                            'class' => 'btn btn-sm btn-danger btn-delete-item',
                            'title' => 'Padam Rekod',
                             'data-url' => $url,
                        ]);
                    },
                ],
            ],
            //'alamat',
            //'jenis_bilik',
            //'created_at',
            //'updated_at',
        ],
    ]); ?>
    <?php endif; ?>

   <?php if (!empty($sesiBatch) && $dataProvider2): ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider2,
        'filterModel' => $searchModel2,
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
            'id',
            [
                'attribute' => 'bilik_asrama',
                'label' => 'Bilik Asrama',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->id_asrama) {
                        // Jika sudah assign bilik, ambil data blok, aras, dan no_bilik
                        $asrama = $model->asrama; // Asuming ada relasi ke model Asrama
                        return $asrama ? $asrama->blok . $asrama->aras . $asrama->no_asrama : 'N/A';
                    }
                    return 'N/A'; // Untuk yang belum ada bilik
                },
                'filter' => true, // Tak perlu filter untuk column ni
            ],
            [
                'attribute' => 'nama',
                'value' => 'user.nama',
            ],
            'no_kp',
            'no_tel',
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
                'format' => 'raw',
                'value' => function($model) {
                    return $model->tarikh_keluar 
                        ? Yii::$app->formatter->asDate($model->tarikh_keluar, 'php:d-m-Y') 
                        : Html::tag('span', 'Belum Ditetapkan', ['class' => 'text-danger fw-bold']);
                },
            ],

            'email:email',
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
            'no_waris', 
            'hubungan', 
            [
                'attribute' => 'status_penginapan',
                'label' => 'Status Penginapan',
                'format' => 'raw',
                'value' => function ($model) {
                    $statusPenginapan = [
                        0 => 'Dalam',
                        1 => 'Luar',
                    ];
                    return $statusPenginapan[$model->status_penginapan] ?? 'Tidak Diketahui';
                },
                'filter' => [
                    0 => 'Dalam',
                    1 => 'Luar',
                ],
            ],
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
                        return Html::a('<i class="bi bi-trash"></i>', 'javascript:void(0)', [
                            'class' => 'btn btn-sm btn-danger btn-delete-item',
                            'title' => 'Padam rekod',
                             'data-url' => $url,
                        ]);
                    },
                ],
            ],
        ],
    ]) ?>
    <?php elseif (!empty($sesiBatch) && !$dataProvider2): ?>
    <div class="alert alert-warning">Tiada pelajar untuk sesi ini.</div>
<?php endif; ?>
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
    <br>
    <!-- <?= Html::button('<i class="bi bi-printer"></i> Cetak Borang', [
        'class' => 'btn btn-success',
        'id' => 'cetak-borang-btn',
    ]) ?> -->
<?= Html::a('<i class="bi bi-file-earmark-excel"></i> Export Excel', 
    array_merge(['pelajar-asrama/export-excel'], Yii::$app->request->queryParams),
    ['class' => 'btn btn-success']
) ?>


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
    <p class="modal-text" id="modalMessage">Status berjaya dikemaskini.</p>
    <button class="modal-close">Tutup</button>
  </div>
</div>

<div id="tarikhKeluarModal" class="modal">
  <div class="modal-content">
    <h5 class="mb-3">Tetapkan Tarikh Keluar</h5>
    <label for="kodKursus">Kod Kursus:</label>
    <?php
    echo Html::dropDownList(
        'kod_kursus', // name
        null,         // selected value
        $kodKursusList, // data from DB
        [
            'prompt' => '-- Pilih Kod Kursus --',
            'id' => 'kodKursus',
            'class' => 'form-select form-select-sm mb-2',
        ]
    );
    ?>
    <label for="tarikhKeluar">Tarikh Keluar:</label>
    <input type="date" id="tarikhKeluar" class="form-control form-control-sm mb-3">
    <input type="hidden" id="sesiBatchField" value="<?= $sesiBatch ?>">

    <div class="d-flex justify-content-between">
    <button id="btnSubmitTarikhKeluar" type="button" class="modal-yes-btn">Hantar</button>

      <button class="modal-no-btn">Batal</button>
    </div>
  </div>
</div>


<?php

$js = <<<JS

$(document).on('change', 'input[type="date"]', function() {
    let id = $(this).data('id');
    let field = $(this).data('field');
    let value = $(this).val();

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

    // Hantar ke backend melalui AJAX kalau nak update terus
    $.post('/pelajar-asrama/update-tarikh', {
        id: id,
        field: field,
        value: value
    }, function(res) {
        // alert('Tarikh dikemaskini!');
        showCustomModal("Tarikh dikemaskini!");
        location.reload();
    });
});

$(document).ready(function() {
    // **1. Apply warna hijau bila page load**
    $(".bilik-pilihan").each(function() {
        if ($(this).data("set") == "1") {
            $(this).addClass('bilik-pilihan-set');  // Tambah kelas selepas kemaskini
        }
    });

    // **2. Bila dropdown change, update bilik**
    $(".bilik-pilihan").on("change.select2", function() { // guna event select2
        var dropdown = $(this);
        var idTempahan = dropdown.data("tempahan-id");
        var idBilik = dropdown.val(); // dapatkan nilai yang dipilih

        if (idBilik === "") return; // Jika tidak pilih bilik, keluar

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

        showConfirmModal("Set bilik untuk tempahan ini?", function () {
        // if (confirm("Set bilik untuk tempahan ini?")) {
            $.post('set-bilik?id=' + idTempahan, {
                'id_asrama': idBilik,
                '_csrf': yii.getCsrfToken()
            }, function(data) {
                if (data.success) {
                    showCustomModal("Bilik berjaya dikemaskini.");
                    // alert('Bilik berjaya dikemaskini.');

                    // Pastikan dropdown kekal dengan bilik yang dipilih
                    dropdown.val(idBilik);  // Pastikan `select2` dikemas kini
                    dropdown.addClass('bilik-pilihan-set');  // Tambah kelas selepas kemaskini
                } else {
                    console.log(data.errors); // << tambah ni
                    // alert('Ralat: ' + data.message);
                    showCustomModal("Ralat: " + data.message, false);
                }
            }).fail(function(xhr, status, error) {
                // alert('Ralat semasa mengemaskini bilik: ' + error);
                showCustomModal("Ralat semasa mengemaskini status: " + error, false);
            });
        });
        dropdown.trigger('blur');
    });
});

JS;

$this->registerJs($js);
$js = <<<JS

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

$(document).ready(function () {

    // 1. Buka modal bila tekan button "Tetapkan Tarikh Keluar"
    $('#btnTetapkanTarikh').on('click', function (e) {
        e.preventDefault();
        $('#tarikhKeluarModal').fadeIn();
    });

    // 2. Halang click dalam modal-content trigger auto-close
    $('.modal-content').on('click', function (e) {
        e.stopPropagation();
    });

    // 3. Halang bubbling dari input dalam modal (especially dropdown & date)
    $('#tarikhKeluar, #kodKursus').on('click keydown change', function (e) {
        e.stopPropagation();
    });

    // 4. Optional: Halang Enter key daripada trigger auto submit
    $('#tarikhKeluarModal input').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            return false;
        }
    });

    // 5. Hantar data bila tekan butang Hantar
    $('#btnSubmitTarikhKeluar').on('click', function (e) {
        e.preventDefault();
        $("#loading-overlay").show();

        const kursus = $('#kodKursus').val();
        const tarikhKeluar = $('#tarikhKeluar').val();
        const sesiBatch = $('#sesiBatchField').val();

        if (!kursus || !tarikhKeluar) {
            showCustomModal("Sila isi semua maklumat.", false);
            return;
        }

        $.post('/pelajar-asrama/set-tarikh-keluar', {
            kod_kursus: kursus,
            tarikh_keluar: tarikhKeluar,
            sesi_batch: sesiBatch,
            _csrf: yii.getCsrfToken()
        })
        .done(function (data) {
            if (data.success) {
                $("#loading-overlay").hide();
                $('#tarikhKeluarModal').fadeOut();
                showCustomModal("Tarikh keluar berjaya diset.", true);
                setTimeout(() => location.reload(), 1500);
            } else {
                $("#loading-overlay").hide();
                showCustomModal(data.message || "Gagal set tarikh keluar.", false);
            }
        })
        .fail(function () {
            $("#loading-overlay").hide();
            showCustomModal("Ralat sistem. Sila cuba lagi.", false);
        });
    });

    // 6. Butang tutup modal
    $('.modal-close').on('click', function () {
        $('#customModal').fadeOut();
        $('#tarikhKeluarModal').fadeOut();
    });

    // 7. (Optional) Auto tutup modal bila klik luar modal-content
    $('#tarikhKeluarModal').on('click', function (e) {
        if (!$(e.target).closest('.modal-content').length) {
            $(this).fadeOut();
        }
    });

    // 8. Fungsi popup modal success / error
    function showCustomModal(message, success = true) {
        $('#modalMessage').text(message);
        $('#modalIcon').html(success ? "&#10003;" : "&#10060;");
        $('#modalIcon').css('color', success ? '#28a745' : '#dc3545');
        $('#customModal').fadeIn();
    }

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
        let url = "' . \yii\helpers\Url::to(["asrama-pelajar/print-senarai-pelajar"], true) . '";
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

    .bilik-pilihan-set {
    background-color: #d4edda !important;
    }

    @media (max-width: 768px) {
        #btnTetapkanTarikh {
            padding-left: 0.75rem !important;
            padding-right: 0.35rem !important;
        }
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
     .card1 {
    //    background-color:rgb(177, 199, 222);
        border: 1px solidrgb(13, 19, 14); /* Warna border */
        height: 50px;
        background-color: #0D3B66;
        color: white;
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
