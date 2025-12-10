<?php
/** @var $model app\models\TempahFasiliti */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="card-permohonan mb-3 shadow-sm p-3 rounded-3 border">

    <!-- Status tempahan utama -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="fw-bold lh-sm">
            ID: <?= $model->id ?>
        </div>

        <?php
        $statusMap = [
            0 => ['label' => 'Draf', 'class' => 'bg-danger text-white'],
            1 => ['label' => 'Sedang Diproses', 'class' => 'bg-primary text-white'],
            2 => ['label' => 'Menunggu Bayaran', 'class' => 'bg-info text-dark'],
            3 => ['label' => 'Lulus', 'class' => 'bg-success text-white'],
            4 => ['label' => 'Tidak Lulus', 'class' => 'bg-danger text-white'],
            5 => ['label' => 'Bayaran Diterima', 'class' => 'bg-success text-white'],
        ];

        $status = $statusMap[$model->status_tempahan_adminKemudahan] ?? ['label' => 'Tidak diketahui', 'class' => 'bg-dark text-white'];
        
        // Kalau status = 4 → check siapa yang batalkan
        if ($model->status_tempahan_adminKemudahan == 4) {
            if (in_array($model->dibatalkanOleh->role ?? null, [3,4])) {
                // kalau user (role 3 atau 4)
                $status = ['label' => 'Dibatalkan', 'class' => 'bg-secondary text-white'];
            } else {
                // admin/pelulus
                $status = ['label' => 'Tidak Lulus', 'class' => 'bg-danger text-white'];
            }
        }

        ?>
        <span class="badge <?= $status['class'] ?>"><?= $status['label'] ?></span>
    </div>

    <!-- Info fasiliti -->
    <div class="mb-2">
        <strong><?= Html::encode($model->fasiliti->nama_fasiliti ?? '-') ?></strong>
    </div>

    <!-- Tarikh permohonan -->
    <div class="mb-2 text-muted small">
        <i class="bi bi-calendar-event"></i>
        <?= Yii::$app->formatter->asDate($model->tarikh_masuk,'php:d-m-Y') ?>
        &rarr;
        <?= Yii::$app->formatter->asDate($model->tarikh_keluar,'php:d-m-Y') ?>
    </div>

    <!-- Pemohon -->
    <div class="mb-2 small">
        <i class="bi bi-person-circle"></i>
        <?= Html::encode($model->user->nama) ?>
    </div>

    <?php
    // Mapping tempoh labels
    $labels = [
        1 => '9am - 12pm',
        2 => '2pm - 5pm',
        3 => '8pm - 11pm',
        4 => 'Sesi Pagi - Petang',
        5 => 'Satu Hari',
    ];

    // Mapping durations in hours
    $durations = [
        1 => 3,
        2 => 3,
        3 => 3,
        4 => 6,
    ];

    // Dapatkan kadar
    $rate = 0;
    if ($model->fasiliti) {
        switch ($model->tempoh) {
            case 1:
            case 2:
            case 4:
                $rate = $model->fasiliti->kadar_sewa_perJamSiang ?? $model->fasiliti->kadar_sewa_perJam ?? 0;
                break;
            case 3:
                $rate = $model->fasiliti->kadar_sewa_perJamMalam ?? $model->fasiliti->kadar_sewa_perJam ?? 0;
                break;
            case 5:
                $rate = $model->fasiliti->kadar_sewa_perHari ?? 0;
                break;
        }
    }
    ?>

    <!-- KADAR X TEMPOH -->
    <div class="small mb-2">
        <span class="fw-semibold text-dark">Kadar x Tempoh:</span>
        <span class="text-secondary">
            <?php if (!$model->fasiliti): ?>
                <span class="text-muted fst-italic">Fasiliti tidak tersedia</span>
            <?php elseif ($model->tempoh === 5): ?>
                <?php
                $date1 = new DateTime($model->tarikh_masuk);
                $date2 = new DateTime($model->tarikh_keluar);
                $days = $date1->diff($date2)->days ?: 1;
                ?>
                <?= "Satu Hari = RM{$rate} x {$days} Hari" ?>
            <?php else: ?>
                <?php
                $label = $labels[$model->tempoh] ?? 'Tidak dipilih';
                $duration = $durations[$model->tempoh] ?? 0;
                ?>
                <?= "{$label} = RM{$rate} x {$duration} jam" ?>
            <?php endif; ?>
        </span>
    </div>

    <!-- JUMLAH (RM) -->
    <?php
    $jumlah = $model->calculateJumlah(); // Make sure this method exists
    $formatJumlah = number_format($jumlah, 2);
    ?>
    <div class="small mb-2">
        <span class="fw-semibold text-dark">Jumlah (RM):</span>
        <span class="text-secondary">
            <?php if ($model->status_tempahan_adminKemudahan == 3): ?>
                <span style="text-decoration: line-through; color: red;"><?= $formatJumlah ?></span>
            <?php else: ?>
                <?= $formatJumlah ?>
            <?php endif; ?>
        </span>
    </div>


    <!-- Tambahan jika perlu: Tarikh tempah -->
    <div class="mb-2 small text-muted">
        <i class="bi bi-clock-history"></i> Ditempah pada:
        <?= Yii::$app->formatter->asDatetime($model->created_at, 'php:d-m-Y H:i') ?>
    </div>

    <!-- Butang tindakan -->
    <div class="mt-3 d-flex flex-wrap gap-2">
        <!-- <?= $model->getCardActions() ?> -->

        <!--hantar tempahan-->
        <?php if (!in_array($model->status_tempahan_adminKemudahan, [1,2,3,4,5])): ?>
        <?= Html::a('<i class="bi bi-check"></i> Hantar', ['send-email', 'id' => $model->id], [
            'class' => 'btn btn-sm btn-primary btn-pengesahan',
            'title' => 'Hantar Pengesahan',
            'aria-label' => 'Hantar Pengesahan', // Accessibility improvement
            'data' => [
                // 'confirm' => 'Adakah anda pasti untuk menghantar pengesahan kepada admin?',
                // 'method' => 'post',
                'params' => ['id' => $model->id ?? null],
                // 'params' => json_encode(['id' => $model->id ?? null])

            ],
            'data-toggle' => 'tooltip', 
        ]) ?>
    <?php endif; ?>

    <!--delete-->
    <?php if (!in_array($model->status_tempahan_adminKemudahan, [1, 2, 3, 4, 5])): ?>
        <?= Html::a('<i class="bi bi-trash"></i>', 'javascript:void(0)', [
            'class' => 'btn btn-sm btn-danger btn-delete-item',
            'title' => 'Padam Tempahan',
            'data-url' => Url::to(['tempah-fasiliti/delete', 'id' => $model->id]),
        ]) ?>
    <?php endif; ?>

     <!--cancel-->
    <?php if (in_array($model->status_tempahan_adminKemudahan, [1, 2, 3, 5])): ?>
        <?= Html::a('<i class="bi bi-x-circle"></i> <span> Batalkan</span>', 'javascript:void(0)', [
            'class' => 'btn btn-sm btn-danger cancel-booking',
            'title' => 'Batalkan Tempahan',
            'data-id' => $model->id,
            'data-bs-toggle' => 'modal',
            'data-bs-target' => '#batalModal'
           
        ]) ?>
    <?php endif; ?>

    <!--bayar ipayment-->
    <?php if (
        $model->status_tempahan_adminKemudahan == 2 &&
        $model->status_pembayaran == 2 &&
        $model->status_tempahan_pelulus == 2 &&
        empty($model->slip_pembayaran)
    ): ?>
        <?= Html::a('<i class="bi bi-cash-stack"></i> Bayar', 'https://ipayment.anm.gov.my/', [
            'class' => 'btn btn-sm btn-success',
            'target' => '_blank',
        ]) ?>
    <?php endif; ?>
    
    <!--resit pembayaran-->
    <?php
    $slip = $model->slip_pembayaran;
    if (!empty($slip)) {
        $slipUrl = Yii::getAlias('@web') . '/uploads/' . $slip;
        echo Html::a('<i class="bi bi-eye"></i> Slip', $slipUrl, [
            'class' => 'btn btn-sm btn-success',
            'target' => '_blank'
        ]);
    } else if (
        $model->status_tempahan_adminKemudahan == 2 &&
        $model->status_pembayaran == 2 &&
        $model->status_tempahan_pelulus == 2
    ) {
        echo Html::beginForm(['upload-slip', 'id' => $model->id], 'post', ['enctype' => 'multipart/form-data']);
        echo Html::fileInput('TempahFasiliti[slip_pembayaran]', null, ['class' => 'd-none slip-input', 'onchange' => 'this.form.submit()']);
        echo Html::button('<i class="bi bi-upload"></i> Upload Slip', ['class' => 'btn btn-sm btn-warning btn-upload-slip']);
        echo Html::endForm();
    }
    ?>

     <?php
        $hasApprovedBooking = (
            ($model->status_tempahan_adminKemudahan == 3 && $model->status_pembayaran == 1 && $model->status_tempahan_pelulus == 2)
            || ($model->status_tempahan_adminKemudahan == 5 && $model->status_pembayaran == 3)
        );

        if ($hasApprovedBooking) {
            echo Html::button('<i class="bi bi-printer"></i>', [
                'class' => 'btn btn-primary cetak-borang-btn',
                'title' => 'Cetak Pas',
                 'data-url' => Url::to(['tempah-fasiliti/print-pass', 'id' => $model->id]),
                
            ]);
        }
        ?>
    </div>
</div>
 <?php

    $this->registerJs('

    $(document).ready(function(){ 
    $("a i").on("click", function(){return false});
    $("i").off();
    $("a i").off();

    let selectedButton = null;

    $("#btn-pengesahan, .btn-pengesahan").on("click", function(e){
        e.preventDefault();
        selectedButton = $(this);
        let confirmText = selectedButton.data("confirm");
        $("#confirmMessage").text(confirmText);
        $("#confirmModal").show();

    });

    $("#modalYes").on("click", function(){
        console.log("User clicked YES");
        // $("#confirmModal").fadeOut();

        if (!selectedButton) return;
        let button = selectedButton;
        let params = button.data("params");

        button.prop("disabled", true).html("<i class=\"fas fa-spinner fa-spin\"></i>");

        $.post({
            url: button.attr("href"),
            data: params,
            dataType: "json",
            success: function(response) {
                $("#modalIcon").removeClass("error-icon").addClass("success-icon").html("&#10003;");
                $("#modalMessage").text(response.message || "Status berjaya dikemaskini.");
                $("#customModal").fadeIn();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $("#modalIcon").removeClass("success-icon").addClass("error-icon").html("&#10060;");
                $("#modalMessage").text("Terdapat ralat semasa menghantar pengesahan. Sila cuba lagi.");
                $("#customModal").fadeIn();
                button.prop("disabled", false).html("<i class=\"bi bi-check\"></i> hantar");
            }
        });
    });

    $("#modalNo").on("click", function(){
        $("#confirmModal").fadeOut();
        selectedButton = null;
    });

    $(".modal-close").on("click", function(){
        $("#customModal").fadeOut();
        location.reload();
    });
});


    ');

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

$this->registerJs('
$(document).on("click", ".btn-upload-slip", function () {
    $(this).closest("form").find(".slip-input").click();
});

');

?>
