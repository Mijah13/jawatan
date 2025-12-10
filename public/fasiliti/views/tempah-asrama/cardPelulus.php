<?php
/** @var $model app\models\TempahAsrama */

use yii\helpers\Html;
use yii\helpers\Url;
?>
 
<div class="card-permohonan mb-3 shadow-sm p-3 rounded-3">

   <div class="d-flex align-items-start mb-2">
        <!-- Status badge dibuang / disable -->

        <!-- Bayaran badge -->
        <?php
            $pay = [
                0 => ['label'=>'Belum Sahkan','class'=>'bg-warning text-dark'],
                1 => ['label'=>'Tidak Berbayar','class'=>'bg-blue text-white'],
                2 => ['label'=>'Berbayar','class'=>'bg-blue text-white'],
                3 => ['label'=>'Bayaran Selesai','class'=>'bg-success']
            ][$model->status_pembayaran];
        ?>
        <span class="badge <?= $pay['class'] ?> ms-auto"><?= $pay['label'] ?></span>
    </div>


    <div class="fw-bold lh-sm mb-1">
        ID <?= $model->id ?> &nbsp; <?= $model->jenisBilik->jenis_bilik ?? '-' ?>
    </div>

    <div class="small text-muted mb-2">
        <?= Yii::$app->formatter->asDate($model->tarikh_masuk,'php:d-m-Y') ?>
        &rarr;
        <?= Yii::$app->formatter->asDate($model->tarikh_keluar,'php:d-m-Y') ?>
    </div>

    <div class="mb-2">
        <i class="bi bi-person-circle"></i> <?= Html::encode($model->user->nama) ?>
    </div>

    <?php if ($model->agensi_pemohon): ?>
        <div class="small mb-2">
            <span class="fw-semibold text-dark">Agensi:</span>
            <span class="text-secondary"><?= Html::encode($model->agensi_pemohon) ?></span>
        </div>
    <?php endif; ?>

    <?php if ($model->tujuan): ?>
        <div class="small mb-2">
            <span class="fw-semibold text-dark">Tujuan:</span>
            <span class="text-secondary"><?= Html::encode($model->tujuan) ?></span>
        </div>
    <?php endif; ?>

   <div class="small mb-2">
        <span class="fw-semibold text-dark">Disokong Oleh:</span>
        <span class="text-secondary">
            <?= $model->disokongOleh && $model->disokongOleh->nama
                ? Html::encode($model->disokongOleh->nama)
                : '<span class="text-muted fst-italic">Belum Disokong</span>' ?>
        </span>
    </div>

        <?php if ($model->surat_sokongan): ?>
        <div class="mb-2">
            <i class="fas fa-paperclip me-1"></i>
            <?= Html::a('Surat Sokongan', Yii::getAlias('@web/uploads/' . $model->surat_sokongan), [
                'target' => '_blank',
                'class' => 'btn-primary small fst-italic'
            ]) ?>
        </div>
    <?php else: ?>
        <div class="mb-2 text-danger small fst-italic">
            <i class="fas fa-exclamation-circle me-1"></i> Tiada surat sokongan
        </div>
    <?php endif; ?>


    <!-- Action buttons -->

        <div class="card-body">
            <?php
           if ($model->status_tempahan_pelulus == 2) {
                echo Html::button('Diluluskan', ['class' => 'btn btn-success btn-sm w-100', 'disabled' => true]);
            } elseif ($model->status_tempahan_pelulus == 3) {
                echo Html::button('Dibatalkan', ['class' => 'btn btn-danger btn-sm w-100', 'disabled' => true]);

            } else {
                echo Html::button('Lulus', [
                    'class' => 'btn btn-success btn-sm flex-fill change-status',
                    'data-id' => $model->id,
                    'data-status' => 2,
                ]);
                echo ' ';
                echo Html::button('Tidak Lulus', [
                    'class' => 'btn btn-danger btn-sm flex-fill cancel-booking',
                    'data-id' => $model->id,
                    'data-status' => 3,
                    'data-bs-toggle' => 'modal',
                    'data-bs-target' => '#batalModal'
                ]);
            }
            ?>
        </div>
    </div>

<?php
$changeStatusPelulus = Url::to(['tempah-asrama/change-status-pelulus']);

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

    if (!tempahanId) {
        showModal("Ralat: Tiada tempahan dipilih.");
        return;
    }

    let alasan = $("#alasanBatal").val().trim();

    if (!alasan) {
        showModal("Sila masukkan alasan pembatalan.");
        return;
    }

    $.post("$changeStatusPelulus?id=" + tempahanId, {
        status_tempahan_pelulus: 3,
        alasan_batal: alasan,
        _csrf: yii.getCsrfToken()
    })
    .done(function (data) {
        console.log("Respon berjaya:", data);
        if (data.success) {
            $("#loading-overlay").hide();
            showModal("Tempahan berjaya dibatalkan.");
            $("#batalModal").modal("hide");
            location.reload();
        } else {
            showModal("Ralat: " + data.message);
        }
    })
    .fail(function (xhr, status, error) {
        console.log("XHR Response:", xhr.responseText);
        console.log("Status:", status);
        console.log("Error:", error);
        $("#loading-overlay").hide();
        showModal("Ralat semasa membatalkan tempahan: " + xhr.responseText);
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


// function closeModal() {
//     $("#customModal").fadeOut(function() {
//         location.reload(); // Refresh page selepas modal ditutup
//     });
// }



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

?>
