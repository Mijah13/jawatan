<?php

use app\models\Fasiliti;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;


/** @var yii\web\View $this */
/** @var app\models\FasilitiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'MyFasiliti';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
?>
<div class="fasiliti-index">

    <!-- Add User Button -->
    <div class="text-right mb-3">
        <?= Html::a('<i class="bi bi-plus-circle"></i> <span class="fw-normal">Tambah Fasiliti</span>', ['create'], ['class' => 'btn btn-success shadow-sm text-white']) ?>
    </div>

    <div class="col-lg-12 bg-light-gray"> 
        <div class="card bg-light-gray">
        <div class="card-header bg-light-gray" style="font-size:large; font-weight:bold;">
            <h1>Senarai Fasiliti</h1>
        </div>
        <div class="scroll-shadow">
            <div class="scroll-shadow-content"></div>
        </div>
        <div class="inner-container">
        <div class="card-body">
        <br>
        <div class="table-responsive">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' => 'No.'],
            'nama_fasiliti',
            [
                'attribute' => 'deskripsi',
                'contentOptions' => ['style' => 'max-width: 250px; white-space: normal; text-align: left;'],
            ],
            'id',
            
            'kadar_sewa_perJam',
            'kadar_sewa_perHari',
            'kadar_sewa_perJamSiang',
            'kadar_sewa_perJamMalam',
            [
                'label' => 'Status Fasiliti',
                'attribute' => 'fasiliti_status',
                'filterInputOptions' => [
                    'class' => 'form-select', 
                ],
                'value' => function($model) {
                    $status = ['Kosong', 'disimpan', 'Rosak', 'Sedang dibaiki', 'Diisi'];
                    return $status[$model->fasiliti_status];
                },
                'filter' => ['Kosong', 'disimpan', 'Rosak', 'Sedang dibaiki', 'Diisi'],
            ],
            [
                'attribute' => 'akses_pengguna',
                'value' => function ($model) {
                    return $model->akses_pengguna == 0 ? 'Semua Pengguna' : 'Pengguna Dalaman';
                },
                'filter' => [
                    0 => 'Semua Pengguna',
                    1 => 'Pengguna Dalaman',
                ],
            ],
            [
                'format' => 'raw',
                'attribute' => 'gambar',
                'value' => function($model) {
                    return Html::a(
                        Html::img("/images/".$model->gambar, ['style' => 'max-width: 80px; max-height: 80px; object-fit: cover; border-radius: 5px;']),
                        "/images/".$model->gambar,
                        ['target' => '_blank']
                    );
                }
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
    <div class="scroll-shadow">
        <div class="scroll-shadow-content"></div>
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
$this->registerJs("
  const topScrollBar = document.querySelectorAll('.scroll-shadow')[0];
  const bottomScrollBar = document.querySelectorAll('.scroll-shadow')[1];
  const contentScrollBar = document.querySelector('.inner-container');

  const shadowContents = document.querySelectorAll('.scroll-shadow-content');
  shadowContents.forEach(shadow => {
    shadow.style.width = contentScrollBar.scrollWidth + 'px';
  });

  const syncScroll = (source, targets) => {
    source.addEventListener('scroll', () => {
      targets.forEach(target => {
        target.scrollLeft = source.scrollLeft;
      });
    });
  };

  syncScroll(topScrollBar, [contentScrollBar, bottomScrollBar]);
  syncScroll(bottomScrollBar, [contentScrollBar, topScrollBar]);
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
      height: 5px; /* Height of the scrollbar */
      overflow-x: auto; /* Enable horizontal scrolling */
      margin-bottom: 5px; /* Space between top scroll and content */
      display: none;
    }

    /* Dummy content for the top scrollbar */
    .scroll-shadow-content {
      width: max-content; /* Match the scrollable content width */
      height: 5px;
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
      height: 5px;
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