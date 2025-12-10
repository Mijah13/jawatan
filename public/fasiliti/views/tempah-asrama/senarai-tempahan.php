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
use yii\widgets\Pjax;
use yii\widgets\ListView;
use yii\bootstrap5\LinkPager;
// use DateTime;

/** @var yii\web\View $this */
/** @var app\models\TempahAsramaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'MyFasiliti';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
?>
<div class="senarai-tempahan">

     <!-- Add Button -->
     <div class="text-right mb-3">
        <?= Html::a('<i class="bi bi-plus-circle"></i> <span class="fw-normal">Tambah Tempahan</span>', ['asrama/bilik'], ['class' => 'btn btn-success shadow-sm text-white']) ?>
    </div>
    
    <?php

    $hasApprovedBooking = false;

    // Check if there is at least one approved booking
    foreach ($dataProvider->models as $model) {
        if (($model->status_tempahan_adminKemudahan == 2 && $model->status_pembayaran == 2) || ($model->status_tempahan_adminKemudahan == 3 && $model->status_pembayaran == 1) || ($model->status_tempahan_adminKemudahan == 5 && $model->status_pembayaran == 3)) {
            $hasApprovedBooking = true;
            break;
        }
    }
    // Calculate total beforehand
    $totalJumlah = 0;

    foreach ($dataProvider->models as $model) {
        if ($model->status_tempahan_adminKemudahan == 2 && $model->status_pembayaran == 2) {
            $date1 = new DateTime($model->tarikh_masuk);
            $date2 = new DateTime($model->tarikh_keluar);
            $days = $date1->diff($date2)->days;

            if ($model->pengiraan_bayaran == 1) {
                // Gunakan kadar diskaun
                $kadar = $model->diskaun ?? 0;
                $jumlah = $kadar * $days;
            } elseif (!empty($model->kadar_override)) {
                // Override kadar custom
                $jumlah = $model->kadar_override * $days;
            } else {
                // Default ikut jenis bilik
                $jumlah = $model->jenisBilik ? $model->jenisBilik->kadar_sewa * $days : 0;
            }

            $totalJumlah += $jumlah;
        }
    }

    ?>
    <div class="desktop-table">
    <div class="col-lg-12 bg-light-gray"> 
        <div class="card bg-light-gray">
        <div class="card-header bg-light-gray" style="font-size:large; font-weight:bold;">
            <h1>Senarai Tempahan</h1>
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
                'summary' => false, // Disable the "Showing X-Y of Z items" text
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn', 'header' => 'No.',  'contentOptions' => ['data-label' => 'No.'],],

                    'id' => [
                        'attribute' => 'id',
                        'label' => 'ID',
                        'contentOptions' => ['data-label' => 'ID'],
                    ],
                    // [
                    //     'attribute' => 'id_asrama',
                    //     'label' => 'Asrama',
                    //     'value' => function($model) {
                    //         if (!$model) return null; // Prevent error when $model is undefined
                    //         $asrama = Asrama::findOne($model->id_asrama);
                    //         return $asrama->blok.$asrama->aras.$asrama->no_asrama;
                    //     }
                    // ],
                    // [
                    //     'attribute' => 'jenis_penginap',
                    //     'value' => function($model) {
                    //         if (!$model) return null; // Prevent error when $model is undefined
                    //         $jenis_penginapan = ArrayHelper::map(PenginapKategori::find()->all(), 'id', 'jenis_penginap');
                    //         return $jenis_penginapan[$model->jenis_penginap];
                    //     }
                    // ],
                    [
                        'label' => 'Jenis Bilik',
                        'attribute' => 'jenis_bilik',
                        'filterInputOptions' => [
                            'class' => 'form-select',
                        ],
                        'value' => function($model) {
                            if (!$model) return null; // Prevent error when $model is undefined
                            $jenisBilikMapping = ArrayHelper::map(
                                JenisAsrama::find()->all(),
                                'id',
                                'jenis_bilik'
                            );

                            return $jenisBilikMapping[$model->jenis_bilik] ?? 'Unknown';
                        },
                        'filter' => ArrayHelper::map(
                            JenisAsrama::find()->all(),
                            'id',
                            'jenis_bilik'
                        ),
                        'contentOptions' => ['data-label' => 'Jenis Bilik', 'style' => 'text-align: left;'],  // ⬅️ Align teks dalam cell ke kiri
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
                            return Yii::$app->formatter->asDate($model->tarikh_keluar, 'php:d-m-Y');
                        },
                    ],
                    [
                        'label' => 'Kadar X Malam',
                        'contentOptions' => function ($model) {
                            return ['data-label' => 'Kadar X Malam'];
                        },
                        'value' => function($model) {
                            if (!$model) return null;

                            $date1 = new DateTime($model->tarikh_masuk);
                            $date2 = new DateTime($model->tarikh_keluar);
                            $days = $date1->diff($date2)->days;

                            // Kalau pengiraan guna diskaun sebagai kadar override
                            if ($model->pengiraan_bayaran == 1) {
                                $kadar = $model->diskaun ?? 0;
                                return '<span class="text-dark">RM' . number_format($kadar, 2) . ' × ' . $days . '</span>';
                            }

                            // Kalau admin override kadar
                            if (!empty($model->kadar_override)) {
                                return 'RM' . number_format($model->kadar_override, 2) . ' × ' . $days . '';
                            }

                            // Default guna kadar dari jenisBilik
                            if ($model->jenisBilik) {
                                return 'RM' . number_format($model->jenisBilik->kadar_sewa, 2) . ' × ' . $days . '';
                            }

                            return '-';
                        },
                        'footer' => "Jumlah: ",
                        'footerOptions' => ['class' => 'text-end fw-bold'],
                        'format' => 'raw',
                    ],


                    // [
                    //     'label' => 'Jumlah (RM)',
                    //     'contentOptions' => function ($model) {
                    //         return [
                    //             'class' => 'text-end', // Pastikan sel kanan
                    //             'data-label' => 'Jumlah (RM)', // Untuk mobile display
                    //         ];
                    //     },
                    //     'value' => function($model) {
                    //         if (!$model) return null; // Prevent error when $model is undefined
                    //         $date1 = new DateTime($model->tarikh_masuk);
                    //         $date2 = new DateTime($model->tarikh_keluar);
                    //         $days = $date1->diff($date2)->days;
                    //         $amount = $model->jenisBilik ? $model->jenisBilik->kadar_sewa * $days : 0;

                    //          // Jika status = 3, tambah HTML span dengan style
                    //          if ($model->status_tempahan_adminKemudahan == 3) {
                    //             return '<span style="text-decoration: line-through; color: red;">' . number_format($amount, 2) . '</span>';
                    //         }

                    //         return number_format($amount, 2);
                    //     },
                    //     'footer' => number_format($totalJumlah, 2),
                    //     'footerOptions' => ['class' => 'text-end fw-bold'],
                    //     'format' => 'raw',

                    // ],
                    [
                        'label' => 'Jumlah (RM)',
                        'contentOptions' => function ($model) {
                            return [
                                'class' => 'text-end',
                                'data-label' => 'Jumlah (RM)',
                            ];
                        },
                        'value' => function($model) {
                            if (!$model) return null;

                            $date1 = new DateTime($model->tarikh_masuk);
                            $date2 = new DateTime($model->tarikh_keluar);
                            $days = $date1->diff($date2)->days;

                            if ($model->pengiraan_bayaran == 1) {
                                $kadar = $model->diskaun ?? 0;
                                $jumlah = $kadar * $days;
                                return '<span class="text-dark fw-bold">RM' . number_format($jumlah, 2) . '</span>';
                            }

                            // Kira ikut kadar override kalau ada
                            if (!empty($model->kadar_override)) {
                                $amount = $model->kadar_override * $days;
                            } else {
                                $amount = $model->jenisBilik ? $model->jenisBilik->kadar_sewa * $days : 0;
                            }

                            if ($model->status_tempahan_adminKemudahan == 3) {
                                return '<span style="text-decoration: line-through; color: red;">' . number_format($amount, 2) . '</span>';
                            }

                            return number_format($amount, 2);
                        },
                        'footer' => number_format($totalJumlah, 2),
                        'footerOptions' => ['class' => 'text-end fw-bold'],
                        'format' => 'raw',
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
                        'template' => '{bayar} {uploadSlip} {print} {delete} {pengesahan} {cancel}',
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
                                    return Html::button('<i class="bi bi-printer"></i>', [
                                        'class' => 'btn btn-primary btn-sm open-plate-modal',
                                        'title' => 'Cetak Pas',
                                            'data-id' => $model->id, // simpan ID booking
                                    ]);
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
                                        'https://ipayment.anm.gov.my/',
                                        [
                                            'class' => 'btn btn-sm btn-success',
                                            'title' => 'Teruskan ke pembayaran',
                                            'aria-label' => 'Teruskan ke pembayaran',
                                            'target' => '_blank',
                                            'data-toggle' => 'tooltip',
                                        ]
                                    );
                                }

                                return null;
                            },
                           'uploadSlip' => function ($url, $model) {
                                $bolehUpload = (
                                    (
                                        $model->status_tempahan_adminKemudahan == 2 &&
                                        $model->status_pembayaran == 2 &&
                                        $model->status_tempahan_pelulus == 2
                                    // ) || (
                                    //     $model->status_tempahan_adminKemudahan == 5 &&
                                    //     $model->status_pembayaran == 3
                                    )
                                );

                                if (!$bolehUpload) return null;

                                $uploadUrl = Url::to(['tempah-asrama/upload-slip', 'id' => $model->id]);
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
                                            <i class="bi bi-eye"></i> Resit
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="{$idModal}" tabindex="-1" aria-labelledby="label-{$idModal}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content-resit">
                                            <!-- <div class="modal-header bg-primary text-white">
                                                <h6 class="modal-title" id="label-{$idModal}">Resit</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div> -->
                                            <div class="modal-body text-center">
                                                <div class="mb-3">
                                                    <small class="text-muted">Tarikh Muat naik: {$tarikhUpload}</small>
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
                                            <input type="file" name="TempahAsrama[slip_pembayaran]" class="d-none slip-input" onchange="this.form.submit()">
                                            <button type="button" class="btn btn-sm btn-warning btn-upload-slip" title="Muat naik Resit">
                                                <i class="bi bi-upload"></i> Muat naik Resit
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
                        pembayaran perlu dibuat melalui <strong>sistem iPayment</strong> dan Resit Pembayaran asal perlu dimuat naik di halaman ini.</br>
                    </span>
                   
                    <i class="bi bi-info-circle-fill me-2 text-primary"></i>
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
                            'item_name' => 'Tempahan Asrama - Jumlah Keseluruhan',
                            'amount' => $totalJumlah,
                            'currency_code' => 'MYR',
                            // 'return' => Url::to(['tempah-asrama/success', 'id' => $model->id], true),
                            // 'cancel_return' => Url::to(['tempah-asrama/cancel', 'id' => $model->id], true),
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


<!-- Plate Modal -->
<div class="modal fade plate-modal" id="modalNoPlate" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content plate-modal-content">
      <div class="modal-header plate-modal-header">
         <label for="NoPlate" class="form-label mb-bold"><strong>Masukkan No. Plat Kenderaan</strong></label>
      </div>
      <div class="modal-body plate-modal-body">
        <form id="formNoPlate">
          <div class="mb-3">
            <label class="form-label">Kenderaan 1</label>
            <input type="text" class="form-control plate-input" name="no_plate[]" maxlength="20" placeholder="ABC 1234">
          </div>
          <div id="extraVehicle" style="display:none;">
            <label class="form-label">Kenderaan 2</label>
            <input type="text" class="form-control plate-input" name="no_plate[]" maxlength="20" placeholder="ABC 1234">
          </div>
          <button type="button" class="btn btn-outline-secondary btn-sm" id="btnAddVehicle">
            + Tambah Kenderaan
          </button>
        </form>
      </div>
      <div class="plate-modal-footer">
        <button type="button" class="modal-no-btn" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="modal-yes-plate-btn" id="btnConfirmPlate">Simpan & Print</button>
      </div>
    </div>
  </div>
</div>

<?php

$this->registerJs('

    // $(document).ready(function(){
    

    //     $("a i").on("click", function(){return false});
    //     $("i").off();
    //     $("a i").off();
    //     $("#btn-pengesahan, .btn-pengesahan").on("click", function(e){
    //         e.preventDefault();
    //         let button = $(this);
    //         let params = button.data("params"); // Get parameters from the button data

    //         if (confirm(button.data("confirm"))) {
    //             button.prop("disabled", true).html("<i class=\"fas fa-spinner fa-spin\"></i>"); // Disable the button and show loading state

    //             // Send POST request to update status and trigger email
    //             $.post({
    //                 url: button.attr("href"), // The action URL for sending the email
    //                 data: params, // Send parameters for the request
    //                 dataType: "json", // Expect JSON response
    //                 success: function(response) {
    //                     console.log(response); // Log the full response from the server
                        
    //                     if (response.success) {
    //                         alert(response.message); // Show success message
    //                         location.reload(); // Reload the page to reflect changes

    //                     } else {
    //                         console.error("Error:", response.message);
    //                         alert("Error: " + response.message);
    //                         button.prop("disabled", false).html("<i class=\"bi bi-check\"></i>"); // ✅ Pastikan button boleh klik semula
    //                     }
    //                 },
    //                 error: function(jqXHR, textStatus, errorThrown) {
    //                     console.error("Error processing request:", textStatus, errorThrown);
    //                     console.log("Server response:", jqXHR.responseText);
                        
    //                     alert("Terdapat ralat semasa menghantar pengesahan. Sila cuba lagi.");
                        
    //                     // ✅ Pastikan button kembali enable supaya user boleh cuba semula
    //                     button.prop("disabled", false).html("<i class=\"bi bi-check\"></i>");
    //                 }
    //             });
    //         }
    //     });
    // });

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

    .loading-spin {
        display: inline-block;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .btn-circle {
        // background-color: rgb(72, 99, 180) !important;
        width: 30px !important;
        height: 30px !important;
        border-radius: 50% !important;
        text-align: center !important;
        display: inline-flex; /* Flexbox for perfect alignment */
        align-items: center; /* Center items vertically */
        justify-content: center; /* Center items horizontally */
        transition: all 0.3s ease; /* Smooth hover effect */
    }
    .btn-circle:hover {
        background-color:rgb(10, 10, 10); /* Hover color */
        color: #ffffff; /* Change icon color on hover */
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); /* Add a shadow on hover */
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
        margin-bottom: 20px;
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

    .modal-content.plate-modal-content {
        background-color: white;
        margin: 10% auto;
        padding: 0;
        border-radius: 10px;
        text-align: center;
        width: 100%;          /* Ikut lebar parent */
        max-width: 350px;     /* Cap max width */
        border: 2px solid #32a248ff;
        border-radius: 12px;

        /* Buang flex center sebab ni yang sempitkan content */
        display: block; 
    }


    .modal-plate-content {
        background-color: white;
        margin: 10% auto;
        padding: 30px;
        border-radius: 10px;
        width: 400px;
        text-align: center;
    }


    .plate-modal-content {
        border: 2px solid #32a248ff;
        border-radius: 12px;
    }

    .plate-modal-header {
        justify-content: center;
        background: #32a248ff;
        color: white;
    }

    .plate-modal-body {
        background: #ffffff23;
    }

    .plate-modal-footer {
        margin: 10% auto;
        margin-top: 20px;
        background: #ffffffff;
    }

    .modal-content-resit {
    background-color: #fff;
    border-radius: .3rem;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
    padding: 1rem;
}

.modal-content-resit {
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,.2);
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

     .modal-yes-plate-btn {
        background-color: #1a8d20ff;
        border: none;
        color: white;
        padding: 10px 25px;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .modal-yes-plate-btn:hover {
        background-color: #1f8825ff;
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

    /* Responsive stacked rows */
    // @media (max-width: 768px) {
        

    //     table {
    //         width: 100%;
    //         border-collapse: collapse; /* Gabungkan border supaya nampak penuh */
    //     }

    //     td, th {
    //         border: 1px solid #ddd; /* Pastikan semua cell ada border */
    //         padding: 10px;
    //     }

    //     thead {
    //         display: table-header-group; /* Pastikan header ada (kalau tak hide) */
    //     }

    //     tfoot {
    //         display: table-footer-group; /* Kalau ada footer, bagi dia ikut struktur */
    //     }


    //     /* Hide table headers */
    //     thead {
    //         display: none;
    //     }

    //     // /* Buat setiap row jadi block */
    //     // tr {
    //     //     display: block;
    //     //     border: 1px solid #ddd; /* Border penuh untuk setiap row */
    //     //     margin-bottom: 10px;
    //     //     padding: 0;
    //     // }

    //     /* Supaya setiap row tak terlalu besar */
    //     tr {
    //         display: table-row; 
    //     }

    //     /* Supaya border setiap row nampak sekata */
    //     td:last-child {
    //         border-bottom: none;
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


        

    //     /* Label bertindak sebagai header */
    //     td::before {
    //         content: attr(data-label);
    //         font-weight: bold;
    //         text-align: left;
    //         background:rgb(130, 187, 206);
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
');
$changeStatus = Url::to(['tempah-asrama/change-status']);

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

?>

<?php
$savePlateUrl = \yii\helpers\Url::to(['tempah-asrama/save-plate']);
$printUrlBase = \yii\helpers\Url::to(['tempah-asrama/print-pass']);

$this->registerJs("
let selectedBookingId = null;

$(document).on('click', '.open-plate-modal', function () {
    selectedBookingId = $(this).data('id'); // id diambil dari button attribute
    $('#modalNoPlate').modal('show');
});

$('#btnAddVehicle').on('click', function () {
    $('#extraVehicle').show();
    $(this).hide();
});

$('#btnConfirmPlate').on('click', function () {
    let plates = [];
    $('input[name=\"no_plate[]\"]').each(function () {
        let val = $(this).val().trim();
        if (val !== '') {
            plates.push(val.toUpperCase());
        }
    });

    if (plates.length === 0) {
        alert('Sila masukkan nombor plat.');
        return;
    }

    $.post('$savePlateUrl', {
        id: selectedBookingId,
        plate: JSON.stringify(plates),
        _csrf: yii.getCsrfToken()
    }, function (res) {
        if (res.success) {
            let printUrl = '$printUrlBase' + '?id=' + selectedBookingId;
            window.open(printUrl, '_blank');
            $('#modalNoPlate').modal('hide');
        } else {
            alert(res.message || 'Gagal simpan nombor plat');
        }
    });
});
");

?>

