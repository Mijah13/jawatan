<?php

use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'MyFasiliti';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
?>
<div class="user-index">

    <!-- Add User Button -->
    <div class="text-right mb-3">
        <?= Html::a('<i class="bi bi-plus-circle"></i> <span class="fw-normal">Tambah Pengguna</span>', ['create'], ['class' => 'btn btn-success shadow-sm text-white']) ?>
    </div>

    <!-- GridView Table -->
    <div class="col-lg-12 bg-light-gray"> 
        <div class="card bg-light-gray">
        <div class="card-header bg-light-gray" style="font-size:large; font-weight:bold;">
            <h1>Senarai Pengguna</h1>
        </div>
        <div class="card-body">
        <br>
        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
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
                'options' => ['class' => 'table-responsive'],
                'tableOptions' => ['class' => 'table table-hover table-bordered'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn', 'header' => 'No.'],

                    'nama',
                    'email:email',
                    [
                        'label' => 'Peranan',
                        'attribute' => 'role',
                        'filterInputOptions' => [
                            'class' => 'form-select', 
                        ],
                        'value' => function ($model) {
                            $roles = ['Admin Sistem', 'Admin Kemudahan', 'Pelulus', 'Pengguna', 'Pengguna Dalaman', 'Pelajar', 'Ketua Admin', 'admin kewangan', 'admin PEM'];
                            return $roles[$model->role];
                        },
                        'filter' => [
                            0 => 'Admin Sistem',
                            1 => 'Admin Kemudahan',
                            2 => 'Pelulus',
                            3 => 'Pengguna',
                            4 => 'Pengguna Dalaman',
                            5 => 'Pelajar',
                            6 => 'Ketua Admin',
                            7 => 'admin kewangan',
                            8 => 'admin PEM',
                        ],
                    ],
                    [
                        'attribute' => 'status',
                        'filterInputOptions' => [
                            'class' => 'form-select', 
                        ],
                        'value' => function ($model) {
                            return $model->status == 1 ? 'Aktif' : 'Tidak Aktif';
                        },
                        'filter' => [
                            1 => 'Aktif',
                            0 => 'Tidak Aktif',
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
                                    'title' => 'Padam Pengguna',
                                    'data-url' => $url,
                                    // 'data-confirm' => 'Adakah anda pasti untuk memadam pengguna ini?',
                                    // 'data-method' => 'post',
                                ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
            </div>
        </div>
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

<?php
$this->registerCss('
    h1 {
        font-size: 1.5rem; /* Adjust size */
        font-weight: bold; /* Make it bold */
        color:rgb(32, 42, 111); /* Text color */
            
        }

        .table-responsive {
            overflow: hidden; /* Prevent overflow */
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

        .pagination .page-item.active .page-link {
            background-color:rgb(10, 55, 91) !important;  /* Tukar ni ikut warna pilihan kau */
            border-color:rgb(82, 139, 185) !important;
            color: #fff !important;
        }

        .pagination .page-link {
            color:rgb(47, 86, 132);  /* warna link page biasa */
        }

        .pagination .page-link:hover {
            background-color:rgb(228, 247, 253);  /* background masa hover */
            color:rgb(13, 77, 149);
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
?>