<?php

use app\models\TempahFasiliti;
use app\models\Fasiliti;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\TempahFasilitiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'MyFasiliti';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
?>
<?php
 // Assuming $models contains all rows
 $totalJumlah = 0;
 foreach ($dataProvider->models as $model) {
     if ($model->status_tempahan_adminKemudahan == 2 && $model->status_pembayaran == 2) { // Include only tempahan with status 2
         $totalJumlah += $model->calculateJumlah();
     }
 }
 
 ?>
 
<div class="tempah-fasiliti-pelulus">
<div class="col-lg-12 bg-light-gray"> 
    <div class="card bg-light-gray">
        <div class="card-header bg-light-gray" style="font-size:large; font-weight:bold;">
            <h1>Senarai Tempahan Pemohon -Fasiliti</h1>
        </div>
        <div className="overflow-auto max-h-[500px] scrollbar-thin scrollbar-thumb-gray-500 scrollbar-track-gray-300">
        <div class="scroll-shadow">
        </div>
        <div id="inner-container" class="inner-container">
            <div class="card-body">
            <br>
            <div class="table-responsive">
            <?php Pjax::begin(); ?>
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
                        'class' => ActionColumn::className(),
                        'template' => '{uploadSlip}',
                        'buttons' => [
                            
                            'delete' => function ($url, $model) {
                                return Html::a('<i class="bi bi-trash"></i>', 'javascript:void(0)', [
                                    'class' => 'btn btn-sm btn-danger btn-delete-item',
                                    'title' => 'Padam Rekod',
                                    'data-url' => $url,
                                    // 'data-confirm' => 'Adakah anda pasti untuk memadam pengguna ini?',
                                    // 'data-method' => 'post',
                                ]);
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
                        'visibleButtons' => [
                            'uploadSlip' => function ($model) {
                                return in_array(Yii::$app->user->identity->role, [0,1,6]);
                            },
                        ],
                        // Hide whole ActionColumn kalau bukan role admin
                        'visible' => in_array(Yii::$app->user->identity->role, [0,1,6]),
                    ],     
                    [
                        'header' => 'Bil',
                        'class' => ActionColumn::className(),
                        'template' => '{print}',
                        'buttons' => [
                            'print' => function ($url, $model) {
                                $hasApprovedBooking = ($model->status_tempahan_adminKemudahan == 2 && $model->status_pembayaran == 2 && $model->status_tempahan_pelulus == 2) 
                                    || ($model->status_tempahan_adminKemudahan == 3 && $model->status_pembayaran == 1 && $model->status_tempahan_pelulus == 2);

                                if ($hasApprovedBooking) {
                                    return Html::a('<i class="bi bi-printer"></i>', 
                                        ['tempah-fasiliti/print-single', 'id' => $model->id], 
                                        [
                                            'class' => 'btn btn-primary btn-sm cetak-borang-btn',
                                            'title' => 'Cetak Borang',
                                            'data-pjax' => '0',
                                        ]
                                    );
                                }

                                return ''; // Kalau syarat tak cukup, return empty string (tak render apa-apa)
                            },

                        ],
                    ],      
                    [
                        'label' => 'Bil Dijana',
                        'format' => 'raw',
                        'visible' => in_array(Yii::$app->user->identity->role, [0, 7]),
                        'value' => function ($model) {
                            $isChecked = $model->invois_dijana === 1 ? 'checked' : '';
                            return Html::checkbox('status_toggle', $isChecked, [
                                'class' => 'status-toggle-invois',
                                'data-id' => $model->id
                            ]);
                        }
                    ],

                    [
                        'header' => 'Resit',
                        'format' => 'raw',
                        'value' => function ($model) {
                            // Semak kalau tempahan dah diluluskan + bayaran disahkan
                            $isConfirmed = (
                                $model->status_tempahan_adminKemudahan == 2 &&
                                $model->status_tempahan_pelulus == 2 &&
                                in_array($model->status_pembayaran, [1, 2]) // maybe nak support belum disahkan pun
                            );

                            if ($isConfirmed && $model->slip_pembayaran) {
                                $slipUrl = Yii::getAlias('@web') . '/uploads/' . $model->slip_pembayaran;
                                return Html::a('<i class="bi bi-file-earmark-text-fill" style="font-size: 1.2rem;"></i>', $slipUrl, [
                                    'target' => '_blank',
                                    'class' => 'btn btn-outline-danger btn-sm',
                                    'title' => 'Lihat Slip Pembayaran',
                                    'data-pjax' => 0,
                                ]);
                            }

                            return '<span class="text-muted"><i>Tiada</i></span>';
                        },
                    ],
                    // [
                    //     'attribute' => 'status_pembayaran',
                    //     'label' => 'Status Pembayaran',
                    //     'format' => 'raw',
                    //     'value' => function ($model) {
                    //         $statuses = [
                    //             0 => ['label' => 'Belum Disemak', 'class' => 'bg-warning text-dark'], 
                    //             1 => ['label' => 'Tidak Berbayar', 'class' => 'bg-blue text-white'], 
                    //             2 => ['label' => 'Berbayar', 'class' => 'bg-blue text-white']
                    //         ];
                
                    //         $status = $statuses[$model->status_pembayaran] ?? ['label' => 'Unknown', 'class' => 'bg-secondary text-white'];
                    
                    //         return Html::tag(
                    //             'span',
                    //             $status['label'],
                    //             ['class' => "badge {$status['class']} px-3 py-2"]
                    //         );
                    //     },
                    // ],
                    [
                        'label' => 'Sahkan Bayaran',
                        'format' => 'raw',
                        'visible' => in_array(Yii::$app->user->identity->role, [0, 7]),
                        'value' => function ($model) {
                            $isDisabled = $model->invois_dijana == 0;
                            $isChecked = $model->status_pembayaran == 3;

                            return Html::checkbox('status_toggle', $isChecked, [
                                'class' => 'status-toggle-bayaran',
                                'data-id' => $model->id,
                                'data-disabled' => $isDisabled ? '1' : '0',
                                'disabled' => $isDisabled,
                            ]);
                        }
                    ],
                    [
                        'label' => 'Disokong Oleh',
                        'value' => function ($model) {
                            return $model->disokongOleh ? $model->disokongOleh->nama : '-';
                        
                        },
                        'format' => 'raw', // To allow HTML for fallback text
                        'contentOptions' => ['class' => 'text-center'], // Optional: Center align
                    ],
                    [
                        'label' => 'Diluluskan Oleh',
                        'value' => function ($model) {
                            return $model->diluluskanOleh ? $model->diluluskanOleh->nama : '-';
                        
                        },
                        'format' => 'raw', // To allow HTML for fallback text
                        'contentOptions' => ['class' => 'text-center'], // Optional: Center align
                    ], 
                    

                    'id',
                    // 'id_fasiliti',
                    // 'user.nama',
                    
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
                    // [
                    //     'attribute' => 'surat_sokongan',
                    //     'format' => 'raw',
                    //     'value' => function ($model) {
                    //         if ($model->surat_sokongan) {
                    //             return Html::a(
                    //                 'Buka Fail',
                    //                 Yii::getAlias('@web/uploads/' . $model->surat_sokongan),
                    //                 ['target' => '_blank', 'class' => 'btn btn-primary btn-sm']
                    //             );
                    //         }
                    //         return '<span class="text-danger">Tiada fail</span>';
                    //     },
                    // ],
                    // [
                    //     'label' => 'Kadar X Tempoh',
                    //     'value' => function ($model) {
                    //         // Check if related fasiliti exists
                    //         if (!$model->fasiliti) {
                    //             return 'Fasiliti tidak tersedia';
                    //         }
                    
                    //         // Mapping session durations (in hours)
                    //         $durations = [
                    //             'sesiPagi' => 3,
                    //             'sesiPetang' => 3,
                    //             'sesiMalam' => 3,
                    //             'sesiPagiPetang' => 6,
                    //             // 'satuHari' => 8,
                    //         ];
                    
                    //         // Mapping tempoh labels
                    //         $labels = [
                    //             'sesiPagi' => 'Sesi pagi',
                    //             'sesiPetang' => 'Sesi petang',
                    //             'sesiMalam' => 'Sesi malam',
                    //             'sesiPagiPetang' => 'Pagi - Petang',
                    //             'satuHari' => 'Satu Hari',
                    //         ];
                    
                    //         $rate = 0;
                    //         switch ($model->tempoh) {
                    //             case 'sesiPagi':
                    //             case 'sesiPetang':
                    //             case 'sesiPagiPetang':
                    //                 // Check for siang or general hourly rate
                    //                 $rate = $model->fasiliti->kadar_sewa_perJamSiang ?? $model->fasiliti->kadar_sewa_perJam ?? 0;
                    //                 break;
                    //             case 'sesiMalam':
                    //                 // Use night rate
                    //                 $rate = $model->fasiliti->kadar_sewa_perJamMalam ?? 0;
                    //                 break;
                    //             case 'satuHari':
                    //                 // Use full-day rate
                    //                 $rate = $model->fasiliti->kadar_sewa_perHari ?? 0;
                    //                 break;
                    //         }
                    
                    //         // Get the hourly/daily rate based on the selected session
                    //         $hourlyOrDailyRate = $rateTypeMapping[$model->tempoh] ?? 0;

                    //         if ($model->tempoh === 'satuHari') {
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
                        'label' => 'Jumlah (RM)',
                        'contentOptions' => ['class' => 'text-end'],
                        'value' => function ($model) {
                            // Calculate the amount for each row
                            $jumlah = $model->calculateJumlah(); // Ensure calculateJumlah() is defined in your model
                            return $jumlah !== null ? number_format($jumlah, 2) : '0.00';
                        },
                        'footer' => number_format($totalJumlah, 2), // Footer to display total of all rows
                        'footerOptions' => ['class' => 'text-end fw-bold'],
                    ],
                    // 'no_kp_pemohon',
                  
                    // 'no_tel',
                    // 'alamat',
                    // 'email:email',
                   
                   
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

 <div class="modal fade" id="resitModal" tabindex="-1" aria-labelledby="resitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="resitForm" method="post" action="">
        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>
        <input type="hidden" name="id" id="resit-model-id">
        <div class="modal-content">
            <div class="modal-header text-center">
            <h5 class="modal-title" id="resitModalLabel">Masukkan No Resit (6 digit):</h5>
            <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button> -->
            </div>
            <div class="modal-body">
            <div class="form-group text-center">
                <!-- <label for="no-resit">Masukkan No Resit (6 digit):</label> -->
                <div id="otp-container" class="d-flex justify-content-center gap-2 mt-2">
                    <?php for ($i = 0; $i < 6; $i++): ?>
                        <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]*" required>
                    <?php endfor; ?>
                </div>
                <input type="hidden" name="no_resit" id="no-resit" required>
            </div>

            </div>
            <div class="modal-footer">
            <button type="button" class="modal-no-btn" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="modal-yes-btn">Sahkan</button>
            </div>
        </div>
        </form>
    </div>
</div>

<?php
$this->registerJs("
    $(document).on('change', '.status-toggle-bayaran', function (e) {
        const checkbox = $(this);
        const isDisabled = checkbox.data('disabled') === 1 || checkbox.prop('disabled');
        
        if (isDisabled) {
            e.preventDefault();
            return false;
        }

        const id = checkbox.data('id');

        $('#resitForm')[0].reset();
        $('.otp-input').val('');
        $('#no-resit').val('');

        $('#resit-model-id').val(id);
        $('#resitForm').attr('action', '/tempah-fasiliti/tukar-status-bayaran?id=' + id);

        checkbox.prop('checked', false);
        $('#resitModal').modal('show');
        setTimeout(() => $('.otp-input').first().focus(), 250);
    });

    const \$otpInputs = $('.otp-input');
    const \$hiddenInput = $('#no-resit');
    const \$form = $('#resitForm');

    function updateHiddenField() {
        let combined = '';
        \$otpInputs.each(function () {
            combined += \$(this).val();
        });
        \$hiddenInput.val(combined);
    }

    \$otpInputs.on('input', function () {
        const \$this = \$(this);
        const value = \$this.val().replace(/\\D/g, '').slice(0, 1);
        \$this.val(value);

        if (value && \$this.next('.otp-input').length) {
            \$this.next('.otp-input').focus();
        }

        updateHiddenField();
    });

    \$otpInputs.on('keydown', function (e) {
        const \$this = \$(this);

        if (e.key === 'Backspace' && !\$this.val() && \$this.prev('.otp-input').length) {
            \$this.prev('.otp-input').focus();
        }

        if (e.key === 'Enter') {
            if (\$hiddenInput.val().length === 6) {
                \$form.submit();
            }
        }
    });

    $('#resitModal').on('shown.bs.modal', function () {
        \$otpInputs.val('');
        \$hiddenInput.val('');
        \$otpInputs.first().focus();
    });
");
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

 /* Toggle Switch */
   /* Toggle Switch - Apply kepada semua jenis toggle */
.status-toggle,
.status-toggle-invois,
.status-toggle-bayaran {
    width: 40px;
    height: 20px;
    position: relative;
    -webkit-appearance: none;
    background: #c6c6c6;
    outline: none;
    border-radius: 20px;
    transition: 0.3s;
}

.status-toggle:checked,
.status-toggle-invois:checked,
.status-toggle-bayaran:checked {
    background: #28a745;
}

.status-toggle::before,
.status-toggle-invois::before,
.status-toggle-bayaran::before {
    content: "";
    position: absolute;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    top: 1px;
    left: 1px;
    background: white;
    transition: 0.3s;
}

.status-toggle:checked::before,
.status-toggle-invois:checked::before,
.status-toggle-bayaran:checked::before {
    left: 21px;
}

.status-toggle:disabled,
.status-toggle-invois:disabled,
.status-toggle-bayaran:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

input.status-toggle,
input.status-toggle-invois,
input.status-toggle-bayaran {
    transform: scale(1.3);
    cursor: pointer;
}


    /* Modal Body Styling */
    #resitModal .modal-content {
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        border: none;
        overflow: hidden;
        animation: fadeInUp 0.3s ease-out;
    }

    /* Header Styling */
    #resitModal .modal-header {
        // background: #007bff;
        // color: white;
        // padding: 16px 24px;
        text-align: center;
    }

    #resitModal .modal-title {
        font-weight: 600;
        font-size: 18px;
    }

    /* Button close */
    #resitModal .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    #resitModal .btn-close:hover {
        opacity: 1;
    }

    /* Form Styling */
    #resitModal .modal-body {
        padding: 24px;
    }

    #resitModal .form-group label {
        font-weight: 500;
        color: #333;
    }

    #resitModal .form-control {
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 15px;
        border: 1px solid #ccc;
        transition: border-color 0.2s ease-in-out;
    }

    #resitModal .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.1rem rgba(0, 123, 255, 0.25);
    }

    /* Footer */
    #resitModal .modal-footer {
        padding: 16px 24px;
        background-color: #f9f9f9;
    }

    /* Buttons */
    #resitModal .btn-success {
        padding: 8px 20px;
        font-weight: 500;
        border-radius: 6px;
        font-size: 14px;
    }

    #resitModal .btn-secondary {
        padding: 8px 18px;
        font-size: 14px;
        border-radius: 6px;
        background-color: #e0e0e0;
        color: #333;
    }

    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Fix modal to center vertically and horizontally in viewport */
    #resitModal .modal-dialog {
        display: flex !important;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        margin: 0 auto;
    }

    /* OTP Input Box — kemas untuk satu nombor */
    #otp-container .otp-input {
        width: 38px;
        height: 48px;
        font-size: 20px;
        text-align: center;
        border: 1px solid #ccc;
        border-radius: 6px;
        outline: none;
        transition: border-color 0.2s;
        background-color: #fff;
        padding: 0;
        line-height: 1;
    }

    #otp-container .otp-input:focus {
        border-color: #007bff;
        box-shadow: 0 0 3px rgba(0,123,255,0.5);
    }
    @media (max-width: 576px) {
        #otp-container .otp-input {
            width: 32px;
            height: 42px;
            font-size: 18px;
        }
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
    $('.status-toggle-invois').on('change', function() {
        let checkbox = $(this);
        let id = checkbox.data('id');
        let isChecked = checkbox.is(':checked') ? 1 : 0;

        $.post({
            url: '/tempah-fasiliti/invois-dijana?id=' + id,
            data: {
                invois_dijana: isChecked,
                _csrf: yii.getCsrfToken()
            },
            success: function(res) {
                // Optional: show feedback
                if (res.success) {
                    console.log('Status updated.');
                }
            }
        });
    });
JS;

$this->registerJs($js);

?>
