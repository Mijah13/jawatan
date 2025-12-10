<?php

use app\models\Asrama;
use app\models\JenisAsrama;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\PenginapKategori;

/** @var yii\web\View $this */
/** @var app\models\AsramaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'MyFasiliti';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
?>
<div class="asrama-index">

    <!-- Add User Button -->
    <div class="text-right mb-3">
        <?= Html::a('<i class="bi bi-plus-circle"></i> <span class="fw-normal">Tambah Bilik Asrama</span>', ['create'], ['class' => 'btn btn-success shadow-sm text-white']) ?>
    </div>

    <div class="col-lg-12 bg-light-gray"> 
        <div class="card bg-light-gray">
        <div class="card-header bg-light-gray" style="font-size:large; font-weight:bold;">
            <h1>Senarai Bilik Asrama</h1>
        </div>
        <div class="card-body">
        <br>
        <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-hover table-bordered'],
            'filterModel' => $searchModel,
            'pager' => [
                'class' => 'yii\bootstrap5\LinkPager',
                'firstPageLabel' => '«',  // First page
                'lastPageLabel' => '»',   // Last page
                'prevPageLabel' => false,  // buang prev
                'nextPageLabel' => false,  // buang next
                'maxButtonCount' => 3, 
                'options' => ['class' => 'mt-3'] 
            ],
            'columns' => [
               ['class' => 'yii\grid\SerialColumn', 'header' => 'No.'],

                [
                    'attribute' => 'id',
                    'headerOptions' => ['style' => 'width: 70px; text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: center;'],
                ],
                [
                    'label' => 'Blok',
                    'attribute' => 'blok',
                    'filterInputOptions' => [
                        'class' => 'form-select', 
                    ],
                    'filter' => ArrayHelper::map(Asrama::find()->select(['blok'])
                    ->distinct()
                    ->all(), 'blok', 'blok'),
                ],
                [
                    'label' => 'Aras',
                    'attribute' => 'aras',
                    'filterInputOptions' => [
                        'class' => 'form-select', 
                    ],
                    'filter' => ArrayHelper::map(Asrama::find()->select(['aras'])
                    ->distinct()
                    ->all(), 'aras', 'aras'),
                ],
                [
                    'attribute' => 'no_asrama',
                    'label' => 'No Bilik',
                    'contentOptions' => ['style' => 'width: 100px; white-space: nowrap; text-align: center;'],
                    'headerOptions' => ['style' => 'width: 100px; text-align: center;'],
                ],
                [
                    'label' => 'Status Asrama',
                    'attribute' => 'status_asrama',
                    'filterInputOptions' => [
                        'class' => 'form-select', 
                    ],
                    //0 = kosong, 1 = sedang dibersihkan, 2 = simpanan, 3 = rosak, 4 = risiko, 5 = sedang dibaiki, 6 = diisi
                    'value' => function($model) {
                        $status = ['Kosong', 'Sedang dibersihkan', 'Simpanan', 'Rosak', 'Risiko', 'Sedang dibaiki', 'Diisi', 'Separa diisi'];
                        return $status[$model->status_asrama];
                    },
                    'filter' => ['Kosong', 'Sedang dibersihkan', 'Simpanan', 'Rosak', 'Risiko', 'Sedang dibaiki', 'Diisi', 'Separa diisi'],
                ],
                [
                    'label' => 'Kategori Bilik',
                    'attribute' => 'jenis_asrama_id',
                    'filterInputOptions' => [
                        'class' => 'form-select', 
                    ],
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
                    'filter' => \yii\helpers\ArrayHelper::map(
                        \app\models\JenisAsrama::find()->all(),
                        'id',
                        'jenis_bilik'
                    ),
                ],
                [
                    'label' => 'Bilik',
                    'attribute' => 'kelamin',
                    'filterInputOptions' => [
                        'class' => 'form-select', 
                    ],
                    'value' => function($model) {
                        $kelamin = ['Lelaki', 'Perempuan', 'Lelaki/Perempuan'];
                        return $kelamin[$model->kelamin];
                    },
                    'filter' => ['Lelaki', 'Perempuan', 'Lelaki/Perempuan'], 
                ],      
                [
                    'attribute' => 'penginap_kategori_id',
                    'label' => 'Kategori Penginap',
                    'value' => function ($model) {
                        return $model->penginapKategori->jenis_penginap ?? '-';
                    },
                    'filter' => ArrayHelper::map(PenginapKategori::find()->where(['id' => [1, 2]])->all(), 'id', 'jenis_penginap'),
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
                                'data-url' => $url,
                                'title' => 'Padam Fasiliti',
                            ]);
                        },
                    ],
                ],
            ],
        ]); ?>

</div>
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

</div>
</div>
</div>

<?php
$this->registerCss('

 h1 {
        font-size: 1.5rem; /* Adjust size */
        font-weight: bold; /* Make it bold */
        color:rgb(32, 42, 111); /* Text color */
    }

    .table-responsive {
        // border-radius: 10px; /* Rounded corners */
        overflow: hidden; /* Prevent overflow */
        // box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
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

    .bg-light-gray {
        background-color: #ffffff;
    }


    .table tbody tr:nth-child(even) {
        background-color: #ffffff; /* White background */
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


JS;
$this->registerJs($js);

