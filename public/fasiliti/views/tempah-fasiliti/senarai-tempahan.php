<?php

use app\models\TempahFasiliti;
use app\models\Fasiliti;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ListView;
use yii\bootstrap5\LinkPager;
// use DateTime;

/** @var yii\web\View $this */
/** @var app\models\TempahFasilitiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'MyFasiliti';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
?>
<div class="senarai-tempahan">

    <!-- Add Button -->
    <div class="text-right mb-3">
        <?= Html::a('<i class="bi bi-plus-circle"></i> <span class="fw-normal">Tambah Tempahan</span>', ['fasiliti/senarai-fasiliti'], ['class' => 'btn btn-success shadow-sm text-white']) ?>
    </div>
    

    <?php

    $hasApprovedBooking = false;

    // Check if there is at least one approved booking
    foreach ($dataProvider->models as $model) {
        if (($model->status_tempahan_adminKemudahan == 2 && $model->status_pembayaran == 2) || ($model->status_tempahan_adminKemudahan == 3 && $model->status_pembayaran == 1)) {
            $hasApprovedBooking = true;
            break;
        }
    }

    // Assuming $models contains all rows
    $totalJumlah = 0;
    foreach ($dataProvider->models as $model) {
        if ($model->status_tempahan_adminKemudahan == 2 && $model->status_pembayaran == 2) { // Include only tempahan with status 2
            $totalJumlah += $model->calculateJumlah();
        }
    }
    
    ?>
    <div class="desktop-table">
    <div class="col-lg-12 bg-light-gray"> 
        <div class="card bg-light-gray">
        <div class="card-header bg-light-gray" style="font-size:large; font-weight:bold;">
            <h1>Senarai Tempahan Fasiliti</h1>
        </div>
        <div class="card-body">
        <br>
        <div class="table-responsive">
        <?php if ($dataProvider->getCount() > 0): ?>
            <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-hover table-bordered'],
                'showFooter' => true,
                // 'footerRowOptions' => ['class' => 'table-light'],
                'summary' => false, // Disable the "Showing X-Y of Z items" text
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn', 'header' => 'No.',  'contentOptions' => ['data-label' => 'No.'],],
                                       
                    'id' => [
                        'attribute' => 'id',
                        'label' => 'ID',
                        'contentOptions' => ['data-label' => 'ID'],
                    ],

                    // 'fasiliti_id',
                    [
                        'label' => 'Jenis Fasiliti',
                        'attribute' => 'fasiliti_id',
                        'value' => function($model) {
                            if (!$model) return null; // Prevent error when $model is undefined
                            return $model->fasiliti ? $model->fasiliti->nama_fasiliti : 'Unknown';
                        },
                        'filter' => ArrayHelper::map(
                            Fasiliti::find()->select(['id', 'nama_fasiliti'])->asArray()->all(),
                            'id',
                            'nama_fasiliti'
                        ),
                        'contentOptions' => ['data-label' => 'Jenis Fasiliti', 'style' => 'text-align: left;'],
                        'headerOptions' => ['style' => 'text-align: left;'], 
                    ],
                    [
                        'attribute' => 'tarikh_masuk',
                        'label' => 'Tarikh Masuk',
                       'contentOptions' => function($model) {
                            return [
                                // 'class' => 'd-flex justify-content-between align-items-center flex-wrap py-2',
                                'data-label' => 'Tarikh Masuk',
                            ];
                        },

                        'value' => function($model) {
                            if (!$model) return null; // Prevent error when $model is undefined
                            return Yii::$app->formatter->asDate($model->tarikh_masuk, 'php:d-m-Y');
                        },
                    ],

                    [
                        'attribute' => 'tarikh_keluar',
                        'label' => 'Tarikh Keluar',
                        'contentOptions' => function ($model) {
                            return ['data-label' => 'Tarikh Keluar'];
                        },
                        'value' => function($model) {
                            if (!$model) return null; // Prevent error when $model is undefined
                            return Yii::$app->formatter->asDate($model->tarikh_keluar, 'php:d-m-Y');
                        },
                    ],
                    // [
                    //     'label' => 'Kadar X Tempoh',
                    //     'contentOptions' => function ($model) {
                    //         return ['data-label' => 'Kadar X Tempoh'];
                    //     },
                    //     'value' => function ($model) {
                    //         // Check if related fasiliti exists
                    //         if (!$model->fasiliti) {
                    //             return 'Fasiliti tidak tersedia';
                    //         }
                    
                    //         // Mapping session durations (in hours)
                    //         $durations = [
                    //             1 => 3, // sesiPagi
                    //             2 => 3, // sesiPetang
                    //             3 => 3, // sesiMalam
                    //             4 => 6, // sesiPagiPetang
                    //             // 'satuHari' => 8,
                    //         ];
                    
                    //         // Mapping tempoh labels
                    //         $labels = [
                    //             1 => 'Sesi pagi : 9am - 12pm',
                    //             2 => 'Sesi petang : 2pm - 5pm',
                    //             3 => 'Sesi malam : 8pm - 11pm',
                    //             4 => 'Sesi Pagi - Petang',
                    //             5 => 'Satu Hari',
                    //         ];
                    
                    //         $rate = 0;
                    //         switch ($model->tempoh) {
                    //             case 1:
                    //             case 2:
                    //             case 4:
                    //                 // Check for siang or general hourly rate
                    //                 $rate = $model->fasiliti->kadar_sewa_perJamSiang ?? $model->fasiliti->kadar_sewa_perJam ?? 0;
                    //                 break;
                    //             case 3:
                    //                 // Use night rate
                    //                 $rate = $model->fasiliti->kadar_sewa_perJamMalam ?? $model->fasiliti->kadar_sewa_perJam ?? 0;
                    //                 break;
                    //             case 5:
                    //                 // Kalau fasiliti ada rate satuHari guna tu
                    //                 if (!empty($this->fasiliti->kadar_sewa_perHari)) {
                    //                     $rate = $this->fasiliti->kadar_sewa_perHari;
                    //                 } else {
                    //                     // fallback ke kadar sesi pagi+petang
                    //                     $rate = $this->fasiliti->kadar_sewa_perJamSiang ?? $this->fasiliti->kadar_sewa_perJam ?? 0;
                    //                     $durations = 6; // pagi + petang = 6 jam
                    //                 }
                    //                 break;
                    //         }
                    
                    //         // Get the hourly/daily rate based on the selected session
                    //         $hourlyOrDailyRate = $rateTypeMapping[$model->tempoh] ?? 0;

                    //         if ($model->tempoh === 5) {
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
                    [
                        'label' => 'Kadar X Tempoh',
                        'contentOptions' => function ($model) {
                            return ['data-label' => 'Kadar X Tempoh'];
                        },
                        'value' => function ($model) {
                            // Check if related fasiliti exists
                            if (!$model->fasiliti) {
                                return 'Fasiliti tidak tersedia';
                            }
                    
                            // Mapping session durations (in hours)
                            $durations = [
                                1 => 3, // sesiPagi
                                2 => 3, // sesiPetang
                                3 => 3, // sesiMalam
                                4 => 6, // sesiPagiPetang
                                // 'satuHari' => 8,
                            ];
                    
                            // Mapping tempoh labels
                            $labels = [
                                1 => 'Sesi pagi : 9am - 12pm',
                                2 => 'Sesi petang : 2pm - 5pm',
                                3 => 'Sesi malam : 8pm - 11pm',
                                4 => 'Sesi Pagi - Petang',
                                5 => 'Satu Hari',
                            ];
                    
                            $rate = 0;
                            switch ($model->tempoh) {
                                case 1:
                                case 2:
                                case 4:
                                    // Check for siang or general hourly rate
                                    $rate = $model->fasiliti->kadar_sewa_perJamSiang ?? $model->fasiliti->kadar_sewa_perJam ?? 0;
                                    break;
                                case 3:
                                    // Use night rate
                                    $rate = $model->fasiliti->kadar_sewa_perJamMalam ?? $model->fasiliti->kadar_sewa_perJam ?? 0;
                                    break;
                                case 5:
                                    if (!empty($model->fasiliti->kadar_sewa_perHari)) {
                                        $rate = $model->fasiliti->kadar_sewa_perHari;
                                    } else {
                                        // fallback ke kadar sesi pagi+petang
                                        $rate = $model->fasiliti->kadar_sewa_perJamSiang ?? $model->fasiliti->kadar_sewa_perJam ?? 0;
                                        $durations[5] = 6; // pagi + petang = 6 jam
                                    }
                                    break;

                            }
                    
                            // Get the hourly/daily rate based on the selected session
                            $hourlyOrDailyRate = $rateTypeMapping[$model->tempoh] ?? 0;

                            if ($model->tempoh === 5) {
                                // Calculate the number of days for 'satuHari'
                                $date1 = new \DateTime($model->tarikh_masuk);
                                $date2 = new \DateTime($model->tarikh_keluar);
                                $days = $date1->diff($date2)->days;
                                $days = $days > 0 ? $days : 1; // At least 1 day

                                if (!empty($model->fasiliti->kadar_sewa_perHari)) {
                                    $rate = $model->fasiliti->kadar_sewa_perHari;
                                    return "Satu Hari = RM{$rate} x {$days} Hari";
                                } else {
                                    // fallback guna rate sesi pagi/petang (6 jam)
                                    $rate = $model->fasiliti->kadar_sewa_perJamSiang ?? $model->fasiliti->kadar_sewa_perJam ?? 0;
                                    $jumlah = $rate * 6; // kira macam rate untuk sehari (6 jam)

                                    return "Satu Hari = RM{$jumlah} x {$days} Hari";
                                }
                                // return "Satu Hari = RM{$rate} x {$days} Hari";
                            }

                            // For other sessions
                            $selectedTempoh = $labels[$model->tempoh] ?? 'Tidak dipilih';
                            $selectedDuration = $durations[$model->tempoh] ?? 0;

                            return "{$selectedTempoh} = RM{$rate} x {$selectedDuration} jam";
                        },
                        'footer' => "Jumlah: ",
                        'footerOptions' => ['class' => 'text-end fw-bold'],
                    ],
                    [
                        'label' => 'Jumlah (RM)',
                        'contentOptions' => function ($model) {
                            return [
                                'class' => 'text-end', // Pastikan sel kanan
                                'data-label' => 'Jumlah (RM)', // Untuk mobile display
                            ];
                        },
                        'value' => function ($model) {
                            $jumlah = $model->calculateJumlah(); // Pastikan function ni betul
                    
                            // Jika status = 3, tambah HTML span dengan style
                            if ($model->status_tempahan_adminKemudahan == 3 && $model->status_tempahan_pelulus == 2) {
                                return '<span style="text-decoration: line-through; color: red;">' . number_format($jumlah, 2) . '</span>';
                            }
                    
                            return number_format($jumlah, 2);
                        },
                        'footer' => number_format($totalJumlah, 2), // Jumlah total di footer
                        'footerOptions' => ['class' => 'text-end fw-bold'],
                        'format' => 'raw', // Supaya HTML dalam value boleh render
                        
                    ],
                    [
                        'attribute' => 'status_tempahan_adminKemudahan',
                        'label' => 'Status Tempahan',
                        'format' => 'raw',
                        'contentOptions' => ['data-label' => 'Status'],
                        'value' => function ($model) {
                            $statuses = [
                                1 => ['label' => 'Sedang Diproses', 'class' => 'bg-primary text-white'],
                                2 => ['label' => 'Menunggu Bayaran', 'class' => 'bg-blue text-white'],
                                3 => ['label' => 'Lulus', 'class' => 'bg-success text-white'],
                                4 => ['label' => 'Tidak Lulus', 'class' => 'bg-danger text-white'],
                                5 => ['label' => 'Bayaran Selesai', 'class' => 'bg-success text-white'],
                            ];

                            // Default value
                            $statusLabel = 'Sedang Diproses';
                            $statusClass = 'bg-secondary text-white';

                            if ($model->status_tempahan_adminKemudahan == 0) {
                                $statusLabel = 'Draf';
                                $statusClass = 'bg-danger fst-italic text-white';
                            } elseif ($model->status_tempahan_adminKemudahan == 4) {
                                // Check siapa batalkan
                                if (in_array($model->dibatalkanOleh->role ?? null, [3,4])) {
                                    $statusLabel = 'Dibatalkan';
                                    $statusClass = 'bg-secondary text-white';
                                } else {
                                    $statusLabel = 'Tidak Lulus';
                                    $statusClass = 'bg-danger text-white';
                                }
                            } elseif ($model->status_tempahan_pelulus != 2) {
                                // Pelulus belum luluskan
                                $statusLabel = 'Sedang Diproses';
                                $statusClass = 'bg-primary text-white';
                            } else {
                                // Pelulus dah lulus (status = 2), baru kita tengok status dari admin kemudahan
                                $statusData = $statuses[$model->status_tempahan_adminKemudahan] ?? ['label' => 'Sedang Diproses', 'class' => 'bg-secondary text-white'];
                                $statusLabel = $statusData['label'];
                                $statusClass = $statusData['class'];
                            }

                            return Html::tag('span', $statusLabel, [
                                'class' => "badge {$statusClass}",
                                'style' => "display: inline-block; max-width: fit-content; white-space: nowrap; padding: 5px 10px;"
                            ]);
                        },
                    ],

                    [
                        'header' => 'Tindakan',
                        'class' => ActionColumn::className(),
                        'contentOptions' => ['data-label' => 'Tindakan'],
                        'template' => '{bayar} {uploadSlip} {delete} {pengesahan} {print} {cancel}',
                        'buttons' => [
                            'delete' => function ($url, $model) {
                                if (!$model) return null;

                                // Kalau status dalam array ni → hide terus
                                if (in_array($model->status_tempahan_adminKemudahan, [1, 2, 3, 4, 5])) {
                                    return null; // tak render apa-apa
                                }

                                return Html::a('<i class="bi bi-trash"></i>', 'javascript:void(0)', [
                                    'class' => 'btn btn-sm btn-danger btn-delete-item',
                                    'title' => 'Padam Tempahan',
                                    'aria-label' => 'Padam Tempahan',
                                    'data-url' => $url,
                                    'data-toggle' => 'tooltip',
                                ]);
                            },
                            'cancel' => function ($url, $model) {
                                if (!$model) return null;

                                // Kalau status dalam array ni → hide terus
                                if (in_array($model->status_tempahan_adminKemudahan, [0, 4])) {
                                    return null; // tak render apa-apa
                                }

                                return Html::a('<i class="bi bi-x-circle"></i> <span>Batalkan</span>', 'javascript:void(0);', [
                                    'class' => 'btn btn-sm btn-danger cancel-booking',
                                    'title' => 'Batalkan Tempahan',
                                    'data-id' => $model->id,
                                    
                                    'data-bs-toggle' => 'modal',
                                    'data-bs-target' => '#batalModal', // Trigger modal
                                ]);
                            },
                            'pengesahan' => function ($url, $model) {
                                if (!in_array($model->status_tempahan_adminKemudahan, [1, 2, 3, 4, 5])) { //selain value ni, akan nampak button confirm
                                    return Html::a('<i class="bi bi-check"></i>hantar', ['send-email'], [
                                        'class' => 'btn btn-sm btn-primary btn-pengesahan',
                                        'title' => 'Hantar Pengesahan',
                                        'aria-label' => 'Hantar Pengesahan', // Accessibility improvement
                                        'data' => [
                                            // 'confirm' => 'Adakah anda pasti untuk menghantar pengesahan kepada admin?',
                                            // 'method' => 'post',
                                            'params' => ['id' => $model->id ?? null],
                                            // 'params' => json_encode(['id' => $model->id ?? null])

                                        ],
                                        'data-toggle' => 'tooltip', // Enable tooltips on hover
                                    ]);
                                }
                                return null;
                            },
                             
                            'print' => function ($url, $model) {
                                $hasApprovedBooking = (
                                    ($model->status_tempahan_adminKemudahan == 3 && $model->status_pembayaran == 1 && $model->status_tempahan_pelulus == 2)
                                    || ($model->status_tempahan_adminKemudahan == 5 && $model->status_pembayaran == 3)
                                );

                                if ($hasApprovedBooking) {
                                    return Html::a('<i class="bi bi-printer"></i>', 
                                        ['tempah-fasiliti/print-pass', 'id' => $model->id], // <-- link direct ke action print
                                        [
                                            'class' => 'btn btn-primary btn-sm cetak-borang-btn',
                                            'title' => 'Cetak Pas',
                                            'data-pjax' => '0', // buka dalam tab baru
                                        ]
                                    );
                                }

                                return '';
                            },
                            'bayar' => function ($url, $model) {
                                $showBayarButton = (
                                    $model->status_tempahan_adminKemudahan == 2 &&
                                    $model->status_pembayaran == 2 &&
                                    $model->status_tempahan_pelulus == 2 &&
                                    empty($model->slip_pembayaran)
                                );

                                if ($showBayarButton) {
                                    return Html::a('<i class="bi bi-cash-stack"></i> Bayar',
                                        'https://ipayment.anm.gov.my/', // link terus ke sistem iPayment
                                        [
                                            'class' => 'btn btn-sm btn-success',
                                            'title' => 'Teruskan ke pembayaran',
                                            'aria-label' => 'Teruskan ke pembayaran',
                                            'target' => '_blank', // buka dalam tab baru
                                            'data-toggle' => 'tooltip',
                                        ]
                                    );
                                }

                                return null;
                            },

                            'uploadSlip' => function ($url, $model) {
                                $bolehUpload = (
                                    $model->status_tempahan_adminKemudahan == 2 &&
                                    $model->status_pembayaran == 2 &&
                                    $model->status_tempahan_pelulus == 2
                                );

                                if (!$bolehUpload) return null;

                                $uploadUrl = Url::to(['tempah-fasiliti/upload-slip', 'id' => $model->id]);
                                $csrf = Yii::$app->request->getCsrfToken();
                                $isUploaded = !empty($model->slip_pembayaran);
                                $slipUrl = Yii::getAlias('@web') . '/uploads/' . $model->slip_pembayaran;
                                $idModal = 'modal-slip-' . $model->id;
                                $tarikhUpload = $model->tarikh_upload_slip ? date('d/m/Y H:i', strtotime($model->tarikh_upload_slip)) : '-';

                                if ($isUploaded) {
                                    $fileExt = strtolower(pathinfo($model->slip_pembayaran, PATHINFO_EXTENSION));
                                    $previewContent = '';

                                    if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])) {
                                        $previewContent = "<img src='{$slipUrl}' alt='Slip' style='max-width:100%; max-height:70vh;' class='img-fluid'>";
                                    } elseif ($fileExt === 'pdf') {
                                        $previewContent = "<embed src='{$slipUrl}' type='application/pdf' width='100%' height='500px'>";
                                    } else {
                                        $previewContent = "<p class='text-muted'>Jenis fail tidak disokong untuk preview.</p>";
                                    }

                                    return <<<HTML
                                        <!-- Button: Lihat Slip -->
                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#{$idModal}" title="Lihat Slip Pembayaran">
                                            <i class="bi bi-eye"></i> Slip Dihantar
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="{$idModal}" tabindex="-1" aria-labelledby="label-{$idModal}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h6 class="modal-title" id="label-{$idModal}">Slip Pembayaran</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <div class="mb-3">
                                                    <small class="text-muted">Tarikh Upload: {$tarikhUpload}</small>
                                                </div>
                                                {$previewContent}
                                            </div>
                                            <div class="modal-footer">
                                            
                                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                    HTML;
                                } else {
                                    // If belum upload — tunjuk butang upload
                                    return <<<HTML
                                        <form action="{$uploadUrl}" method="post" enctype="multipart/form-data" class="upload-slip-form d-inline">
                                            <input type="hidden" name="_csrf" value="{$csrf}">
                                            <input type="file" name="TempahFasiliti[slip_pembayaran]" class="d-none slip-input" onchange="this.form.submit()">
                                            <button type="button" class="btn btn-sm btn-warning btn-upload-slip" title="Upload Slip">
                                                <i class="bi bi-upload"></i> Upload Slip
                                            </button>
                                        </form>
                                    HTML;
                                }
                            }


                        ],
                    ],
                    
                ],
            ]); ?>
            </div>
            <?php Pjax::end(); ?>
            <!-- Add the note below the GridView table -->
            <div class="card-footer bg-light-gray">
                <div class="mt-4" role="alert">
                    
                <i class="bi bi-info-circle-fill me-2 text-primary"></i>
                   <span class="text-primary">
                        <strong>
                            klik butang 
                            <button class="btn btn-success" style="padding: 2px 6px; font-size: 12px; pointer-events: none; cursor: default;">
                               <i class="bi bi-check" style="font-style: normal;"> Hantar</i>
                            </button> 
                        </strong>
                        bagi pengesahan permohonan. *Butang tidak akan dipaparkan selepas permohonan berjaya dihantar.
                    </span>

                    <br>
                    <i class="bi bi-info-circle-fill me-2 text-primary"></i>
                    <span class="text-primary">
                        pembayaran perlu dibuat melalui <strong>sistem iPayment</strong></br>
                    </span>
                    <span class="text-primary">
                        Invois akan tersedia untuk pembayaran dalam tempoh 24 jam. (selepas tempahan diluluskan)</br>
                    </span>
                    <i class="bi bi-info-circle-fill me-2 text-primary"></i>
                    <span class="text-primary">
                        Penyerahan kunci di Unit Kemudahan pada hari dan waktu bekerja sahaja:<strong> Isnin - Jumaat 9:00pagi - 5:00petang</strong>
                    </span>
                </div>
                <br>
                <?php if ($dataProvider->getCount() > 0 && $hasApprovedBooking): ?>
                   
                <?php endif; ?>
                    
                <?php else: ?>
                    <div class="alert alert-info text-center mt-3">
                        <strong>Tiada Tempahan Ditemui!</strong><br>
                        Anda belum membuat sebarang tempahan lagi. Klik butang di atas untuk membuat tempahan.
                    </div>
                <?php endif; ?>

                <!-- Hide the PayPal Button if there are no bookings -->
                <?php if ($dataProvider->getCount() > 0 && $hasApprovedBooking): ?>
                <div class="center-container">
                    <?= Html::a('Bayar', 'https://www.sandbox.paypal.com/cgi-bin/webscr', [
                        'class' => 'bayar-button',
                        'target' => '_blank',
                        'data' => [
                            'method' => 'post',
                            'params' => [
                                'cmd' => '_xclick',
                                'business' => 'fasilitiCiast@gmail.com',
                                'item_name' => 'Tempahan Fasiliti - Jumlah Keseluruhan',
                                'amount' => $totalJumlah,
                                'currency_code' => 'MYR',
                                // 'return' => Url::to(['tempah-fasiliti/success', 'id' => $model->id], true),
                                // 'cancel_return' => Url::to(['tempah-fasiliti/cancel', 'id' => $model->id], true),
                            ],
                        ],
                    ]) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </div>

     <!-- Bottom scrollbar -->
    <div class="scroll-shadow">
        <div class="scroll-shadow-content"></div>
    </div>
    </div>
</div>

    <?php
  /* ======================
    MOBILE  – guna ListView + partial
    ====================== */
  ?>
  
  <div class="mobile-cards">
    <?php if ($dataProvider->getCount() > 0): ?>
    <div class="card bg-light-gray">
      <div class="card-header bg-dark" style="font-size:large; font-weight:bold;">
          <h7>Permohonan Fasiliti</h7>
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
          'itemView'      => 'cardPemohon',   // partial view di bawah
      ]); ?>
      <?php else: ?>
        <div class="alert alert-info text-center mt-3">
            <strong>Tiada Tempahan Ditemui!</strong><br>
            Anda belum membuat sebarang tempahan lagi. Klik butang di atas untuk membuat tempahan.
        </div>
    <?php endif; ?>
  </div>


<!-- Confirm Modal -->
<div id="confirmModal" class="modal">
  <div class="modal-content">
    <p class="modal-text" id="confirmMessage">Adakah anda pasti untuk menghantar tempahan ini?</p>
    <div class="modal-buttons">
      <button class="modal-yes-btn" id="modalYes">Ya</button>
      <button class="modal-no-btn" id="modalNo">Tidak</button>
    </div>
  </div>
</div>

<!-- Success Modal -->
<!-- Custom Modal (Success + Error) -->
<div id="customModal" class="modal">
  <div class="modal-content">
    <div class="modal-icon" id="modalIcon">&#10003;</div>
    <p class="modal-text" id="modalMessage">Status berjaya dikemaskini.</p>
    <button class="modal-close">Tutup</button>
  </div>
</div>

<!-- Loading Spinner -->
<div id="loading-overlay">
  <div class="spinner"></div>
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

<?php
$changeStatus = Url::to(['tempah-fasiliti/change-status']);

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


JS;
$this->registerJs($js);
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

    .bg-blue {
    background-color:rgb(0, 105, 217) !important;
    color: #ffffff !important; /* White text */
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

    .table thead th a:hover {
        color:rgb(0, 0, 0); /* Optional: Add hover effect */
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

    td, th {
        padding: 5px !important;
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

    .button-container {
        display: flex;
        gap: 5px; /* Kurangkan jarak antara button */
        align-items: center; /* Pastikan semua button sejajar */
        flex-wrap: wrap; /* Kalau ruang kecil, dia turun ke bawah */
    }


    .btn {
        margin: 0;
    }



    .btn {
        border-radius: 5px;
        padding: 5px 10px;
        transition: all 0.3s ease-in-out; /* Smooth transition */
    }

    .btn:hover {
        // background-color: #0056b3;
        color: white;
        transform: scale(1.05); /* Slight zoom on hover */
    }
            
    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .btn-primary {
        background-color:rgb(24, 148, 59);
        color: white;
    }

    .btn-primary:hover {
        background-color:rgb(15, 134, 93);
    }

    .center-container {
    display: flex;
    justify-content: center; /* Center horizontally */
    align-items: center;    /* Center vertically */
    // height: 100%;           /* Ensure it fills the parent container */
    width: 100%;     
    margin-top: 20px; 
    }
    .bayar-button {
    background-color: #28a745; /* Bright green */
    color: white;
    font-size: 18px;
    font-weight: bold;
    padding: 10px 20px;
    width: 20%;
    border: none;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: all 0.3s ease;
    display: none;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.bayar-button:hover {
    background-color: #218838; /* Darker green on hover */
    transform: scale(1.05);
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
}

.bayar-button:active {
    background-color: #1e7e34; /* Even darker green */
    transform: scale(0.98);
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

// /* Responsive stacked rows */
// @media (max-width: 768px) {
    

//     table {
//     width: 100%;
//     table-layout: fixed;
//     border-collapse: collapse; /* Pastikan border semua rapat */
// }

//     /* Hide table headers */
//     thead {
//         display: none;
//     }

//     /* Buat setiap row jadi block */
//     tr {
//         display: block;
//         border: 1px solid #ddd; /* Border penuh untuk setiap row */
//         margin-bottom: 10px;
//         padding: 0;
//     }

//     /* Gaya setiap sel (td) */
//     td {
//         display: flex;
//         align-items: center;
//         justify-content: flex-start;
//         gap: 15px;
//         width: 100%; /* Pastikan td memenuhi lebar */
//         padding: 10px;
//         border-bottom: 1px solid #ddd;
//     }


//     /* Supaya border setiap row nampak sekata */
//     td:last-child {
//         border-bottom: none;
//     }

//     /* Label bertindak sebagai header */
//     td::before {
//         content: attr(data-label);
//         font-weight: bold;
//         text-align: left;
//         background:rgb(113, 131, 107);
//         padding: 8px;
//         width: 40%;
//         border-right: 2px solid #ccc; /* Border kanan label */
//     }

//     /* Value tetap selari di sebelah kanan */
//     td span {
//         width: 60%;
//         text-align: left;
//         padding-left: 10px;
//         margin-left: 10px;
//     }

//     /* Supaya border keseluruhan row selari macam table biasa */
//     .grid-view td {
//         border-bottom: 1px solid #ddd;
//         border-right: none;
//     }

//     .grid-view tr {
//         border: 1px solid #ccc;
//     }

//     /* Buang footer jika ada */
//     .grid-view tfoot {
//         display: none;
//     }
// }

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

