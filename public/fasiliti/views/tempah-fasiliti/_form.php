<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Fasiliti;
use app\models\Info;
use yii\web\UploadedFile;

/** @var yii\web\View $this */
/** @var app\models\TempahFasiliti $model */
/** @var yii\widgets\ActiveForm $form */

// Register external CSS file
$this->registerCssFile('@web/css/tempah.css');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.js', ['depends' => 'yii\web\JqueryAsset']);
    

?>
<?php

// Retrieve the passed values from URL
$fasiliti = $model->isNewRecord ? Fasiliti::findOne(Yii::$app->request->get('fasiliti_id')) :  Fasiliti::findOne($model->fasiliti_id);
// Ambil semua info yang aktif
$infoAktif = Info::find()->where(['aktif' => 1])->all();
    if ($fasiliti) {
        $nama_fasiliti = Yii::$app->request->get('nama_fasiliti', $fasiliti->nama_fasiliti);
        
    } else {
        // Handle the case where $nama_fasiliti is null (perhaps by redirecting or showing an error)
        $nama_fasiliti = null;
    }
    ?>

<script>
    var userRole = <?= Yii::$app->user->identity->role ?>;
</script>

    <!-- Display Room Details Inline and Centered -->
    <div class="room-highlight">
        <p><strong>Fasiliti:</strong> <?= Html::encode($nama_fasiliti) ?></p>
    </div>

    
<div class="info-section mt-4 mx-auto" style="max-width: 700px;">
    <?php foreach ($infoAktif as $info): ?>
        <div class="alert alert-warning border-left border-4 border-danger shadow-sm mb-3 p-3">
            <h5 class="fw-bold" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-align: center;">
                <?= Html::encode($info->tajuk) ?>
            </h5>
            <ul style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 15px;">
                <?php foreach (explode("\n", $info->keterangan) as $point): ?>
                    <?php if (trim($point) !== ''): ?>
                        <li><?= Html::encode($point) ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</div>


    <div class="tempah-fasiliti-page">
    <div class="calendar-container">
        <h1 class="calendar-title"><?= Html::encode($this->title) ?></h1>
        <div id="calendar"></div>
        
        <!-- Add a note or legend for the calendar -->
        <div class="calendar-legend">
            <p><span class="calendar-unavailable" style="background-color: green; display: inline-block; width: 20px; height: 20px; border-radius: 3px; margin-right: 10px; margin-top: 20px;"></span> 
            Telah ditempah.</p>
        </div>

        <div id="booking-alert"></div>
    </div>

    <!-- Booking Alert Modal -->
    <div id="bookingAlertModal" class="modal" style="display: none;">
    <div class="modal-content">
        <p class="modal-text" id="bookingAlertMessage">Amaran</p>
        <div class="modal-buttons" style="text-align: center;">
        <button class="modal-close" id="bookingAlertClose">Tutup</button>
        </div>
    </div>
    </div>

    <div class="tempah-fasiliti-form">
    <div class="booking-form">
        <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data'],
            'fieldConfig' => [
                'errorOptions' => ['class' => 'text-danger'],
            ],
        ]); ?>

        <div class="form-grid"> <!-- Grid container -->
            <!-- Tarikh Masuk -->
            <div class="form-group order-6">
                <?= $form->field($model, 'tarikh_masuk')->textInput([
                    'readonly' => true, 
                    'placeholder' => 'Klik pada kalendar untuk pilih tarikh',
                    'value' => $model->tarikh_masuk ? Yii::$app->formatter->asDate($model->tarikh_masuk, 'php:d-m-Y') : ''
                ]) ?>
            </div>

            <!-- Tarikh Keluar -->
            <div class="form-group order-7">
                <?= $form->field($model, 'tarikh_keluar')->textInput([
                    'readonly' => true, 
                    'placeholder' => 'Klik pada kalendar untuk pilih tarikh',
                    'value' => $model->tarikh_keluar ? Yii::$app->formatter->asDate($model->tarikh_keluar, 'php:d-m-Y') : ''
                ]) ?>
            </div>

            <!-- No KP -->
            <div class="form-group order-8">
                <?= $form->field($model, 'no_kp_pemohon')->textInput(['class' => 'custom-input']) ?>
            </div>

            <!-- No Tel -->
            <div class="form-group order-9">
                <?= $form->field($model, 'no_tel')->textInput(['class' => 'custom-input']) ?>
            </div>

            <!-- Agensi -->
            <div class="form-group order-10">
                <?= $form->field($model, 'agensi_pemohon')->textInput(['class' => 'custom-input']) ?>
            </div>

            <!-- Alamat -->
            <div class="form-group order-1">
                <?= $form->field($model, 'alamat')->textarea(['class' => 'custom-input', 'rows' => 2]) ?>
            </div>

            <!-- Tujuan -->
            <div class="form-group order-11">
                <?= $form->field($model, 'tujuan')->textInput(['class' => 'custom-input']) ?>
            </div>

            <!-- Tempoh -->
           <div class="form-group order-3">
                <?= $form->field($model, 'tempoh')->dropDownList(
                    \app\models\TempahFasiliti::getSenaraiTempoh(), 
                    ['prompt' => 'Pilih Tempoh']
                ) ?>

            </div>

            <!-- Jangkaan Hadirin -->
            <div class="form-group order-2">
                <?= $form->field($model, 'jangkaan_hadirin')->input('number', ['class' => 'custom-input']) ?>
            </div>

            <!-- Surat Sokongan -->
            <div class="form-group order-4">
                <?= $form->field($model, 'surat_sokongan')->fileInput([
                    'id' => 'document_doc_file',
                    'class' => 'form-control',
                    'accept' => '.png, .jpeg, .jpg, .doc, .docx, .pdf',
                ])->label('Surat Sokongan <span class="text-muted">(*eg Surat Tawaran Kursus/ Surat Rasmi)</span>', ['escape' => false]) ?>
             <div id="document_doc_file_help" class="form-text mb-0 help-text">
                Max 5MB per file (PNG, JPEG, JPG, PDF).
            </div>
            </div>

            <?php if (in_array(Yii::$app->user->identity->role, [0, 1, 6])): ?>
                <div class="form-group order-5">
                    <?= $form->field($model, 'is_simpanan')->checkbox([
                        'value' => 1,
                        'label' => 'Simpan Tempahan (Hanya dibuat oleh Admin)',
                        'uncheck' => 0,
                        'checked' => $model->is_simpanan ? true : false,
                    ]) ?>
                </div>
            <?php endif; ?>
            
        </div> <!-- /form-grid -->

        <div class="form-group mt-3" style="text-align: center;">
            <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
            <?= Html::resetButton('Reset', [
                'class' => 'btn btn-secondary',
                'onclick' => 'startDateSelected = false; endDateSelected = false; return true;'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

    </div>
    </div>
    </div>

    <?php
    $this->registerCss("

        /* Style for calendar text */
        #calendar {
            font-family: Arial, sans-serif; /* Optional: Change the font */
        }

        #calendar .fc-toolbar-title {
            color: #333333; /* Change calendar header title color */
            font-size: 20px;
            font-weight: bold;
        }

        #calendar .fc-day-header {
            color: #0056b3; /* Change the color of the weekday headers (e.g., Sun, Mon) */
            font-size: 14px;
            font-weight: bold;
            text-decoration: none; /* Remove underline */
        }

        #calendar .fc-daygrid-day-number {
            color:rgb(70, 72, 74); /* Change the color of the day numbers */
            font-weight: bold;
            text-decoration: none; /* Remove underline */
        }

        #calendar .fc-daygrid-day:hover {
            background-color: #f5f5f5; /* Add a hover effect for better interactivity */
            cursor: pointer;
        }

        #calendar a {
            color: inherit; /* Ensure links in the calendar inherit the text color */
            text-decoration: none; /* Remove underline for links */
        }

        #calendar a:hover {
            color: #0056b3; /* Change color on hover */
            text-decoration: none; /* Keep no underline on hover */
        }

        .file-upload-area {
            border: 2px dashed #d3d3d3;
            border-radius: 5px;
            text-align: center;
            padding: 40px 80px;
            background-color: #f9f9f9;
            color: #6c757d;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }
        .file-upload-area:hover {
            border-color: #007bff;
        }
        .file-upload-area i {
            font-size: 48px;
            color: #007bff;
            margin-bottom: 10px;
        }
        .file-upload-area .title {
            font-weight: 600;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .file-upload-area .subtitle {
            font-size: 14px;
            color: #6c757d;
        }
        .file-details {
            margin-top: 10px;
            font-size: 14px;
            color: #245, 5, 5;
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

        .tooltip-left {
            white-space: nowrap;
            font-size: 13px;
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 6px 10px;
            border-radius: 5px;
        }

        .tooltip {
            max-width: 300px !important; /* atau lebih besar ikut kesesuaian */
            white-space: normal !important;
            word-wrap: break-word !important;
            text-align: left;
            font-size: 13px;
            line-height: 1.4;
            z-index: 9999;
        }




    ");

    $this->registerJs("
    
    document.addEventListener('DOMContentLoaded', function () {
        console.log('✅ JS Loaded!');

        // ================== File Input Handling ==================
        const fileInput = document.getElementById('file-input');
        const fileDetailsContainer = document.getElementById('file-details');
        
        if (fileInput) {
            fileInput.addEventListener('change', function () {
                const files = fileInput.files;
                if (files.length > 0) {
                    const file = files[0];
                    fileDetailsContainer.innerHTML = `<strong>File Selected:</strong> \${file.name}`;
                } else {
                    fileDetailsContainer.innerHTML = ''; 
                }
            });
        }

        // ================== Responsive Form Handling (Mobile View) ==================
        if (window.innerWidth <= 768) {
            console.log('📌 Mobile view detected!');

            let leftCol = document.querySelectorAll('.form-column:first-child .form-group');
            let rightCol = document.querySelectorAll('.form-column:last-child .form-group');
            let formRow = document.querySelector('.form-row');

            if (!formRow) {
                console.error('🚨 ERROR: .form-row NOT found!');
                return;
            }

            if (!leftCol.length || !rightCol.length) {
                console.warn('⚠ WARNING: One of the columns is empty!');
            }

            console.log('👉 Left Column:', leftCol.length, '👉 Right Column:', rightCol.length);

            let mergedFields = [];
            let i = 0;

            leftCol = Array.from(leftCol);
            rightCol = Array.from(rightCol);

            // Selang seli push masuk array
            while (leftCol.length || rightCol.length) {
                if (i % 2 === 0 && leftCol.length) {
                    let field = leftCol.shift();
                    console.log('⬅ Added from LEFT:', field.innerText);
                    mergedFields.push(field);
                } else if (rightCol.length) {
                    let field = rightCol.shift();
                    console.log('➡ Added from RIGHT:', field.innerText);
                    mergedFields.push(field);
                }
                i++;
            }

            console.log('🔄 Final Merged Fields:', mergedFields);

            formRow.innerHTML = ''; // Kosongkan form dan masukkan semula ikut susunan baru
            mergedFields.forEach(field => formRow.appendChild(field));

            console.log('✅ Form updated successfully!');
        } else {
            console.log('🖥 Desktop view, no changes made.');
        }
    });
");

$this->registerJs("

    var startDateSelected = false;
    var endDateSelected = false;

    function showBookingAlert(message) {
        $('#bookingAlertMessage').text(message);
        $('#bookingAlertModal').fadeIn();

        // Auto close after 3 seconds
        setTimeout(function () {
            $('#bookingAlertModal').fadeOut();
        }, 3000);
    }

    $('#bookingAlertClose').on('click', function () {
        $('#bookingAlertModal').fadeOut();
    });

    function scrollToInput(inputId) {
        var element = $('#' + inputId);
        if (element.length) {
            $('html, body').animate({
                scrollTop: element.offset().top - 100
            }, 500);
            element.focus();
        }
    }

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [day, month, year].join('-');
    }

    function validateDates(startDate, endDate) {
        if (!startDate || !endDate) return true;
        
        var start = new Date(startDate.split('-').reverse().join('-'));
        var end = new Date(endDate.split('-').reverse().join('-'));
        
        return end > start;
    }

    function isPublicHoliday(date) {
        var publicHolidays = ['01-01', '01-05', '31-08', '16-09', '25-11'];
        var formattedDate = formatDate(date).slice(0, 5);
        return publicHolidays.includes(formattedDate);
    }

    function isWeekend(date) {
        var day = date.getDay();
        return day === 0 || day === 6;
    }

    function isTodayOrBefore(date) {
        var today = new Date();
        today.setHours(0, 0, 0, 0);
        return date <= today;
    }

    function isStartDateAfterCurrentDate(startDate) {
        var today = new Date();
        today.setHours(0, 0, 0, 0); // Reset to midnight
        var start = new Date(startDate.split('-').reverse().join('-'));
        return start >= today;
    }

    $(document).ready(function() {
        var calendarEl = document.getElementById('calendar');
        if (!calendarEl) {
            console.error('Calendar element not found!');
            return;
        }

        var calendar = new FullCalendar.Calendar(calendarEl, {

        //tooltip
            eventDidMount: function(info) {
                var startDate = info.event.start.toLocaleDateString();
                var endDate = info.event.end ? new Date(info.event.end.getTime() - 86400000).toLocaleDateString() : '';

               var title = info.event.title;
                var datePattern = /\d{2}-\d{2}-\d{4}/g;
                var tujuan = title.replace(datePattern, '').replace(/ - /g, '').trim();


                var tooltipContent = 'Tujuan: ' + tujuan + '<br>' +
                                    'Tarikh: ' + startDate;

                if (endDate !== '' && endDate !== startDate) {
                    tooltipContent += ' - ' + endDate;
                }

                var sessionMap = {
                    1: 'pagi : 9am - 12pm',
                    2: 'petang : 2pm - 5pm',
                    3: 'malam : 8pm - 11pm',
                    4: 'Pagi - Petang',
                    5: 'Satu Hari'
                };

                if (info.event.extendedProps.tempoh && sessionMap[info.event.extendedProps.tempoh]) {
                    tooltipContent += '<br>Sesi ' + sessionMap[info.event.extendedProps.tempoh];
                }

                $(info.el).tooltip({
                    title: tooltipContent,
                    html: true,
                    container: 'body',
                    placement: 'top',
                    trigger: 'hover',
                    customClass: 'tooltip-left'
                });
            },

            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            aspectRatio: window.innerWidth < 768 ? 0.8 : 1.35,
            height: 450,
            events: '" . Url::to(['tempah-fasiliti/get-events', 'fasiliti_id' => $model->isNewRecord ? $fasiliti_id : $model->fasiliti_id]) . "',

            dateClick: function(info) {
                var events = calendar.getEvents();
                var clickedDate = new Date(info.date); // betulkan ni dulu
                selectedDate = clickedDate; // then baru assign

                updateSatuHariOption();


                // Get today's date at midnight
                var today = new Date();
                today.setHours(0, 0, 0, 0); // Reset time to 00:00:00

                // Get the clicked date
                var clickedDate = new Date(info.date);


                    

                if (isTodayOrBefore(clickedDate)) {
                    showBookingAlert('Tempahan tidak dibenarkan untuk hari ini atau tarikh lepas.');
                    return;
                }

                var clickedDate = new Date(info.date);


                if (userRole === 3 && isPublicHoliday(clickedDate)) {
                    showBookingAlert('Tempahan tidak dibenarkan pada cuti umum.');
                    return;
                }

                
                if (userRole === 3 && isWeekend(clickedDate)) {
                    showBookingAlert('Tempahan tidak dibenarkan pada hujung minggu (Sabtu & Ahad).');
                    return;
                }

                function updateSatuHariOption() {
                    const dropdown = $('#tempahfasiliti-tempoh');
                    const satuHariOption = dropdown.find('option[value=5]'); // ID 5 = satu hari

                    if (!selectedDate) {
                        satuHariOption.prop('disabled', false); // reset kalau belum pilih tarikh
                        return;
                    }

                    const clickedDateObj = new Date(selectedDate);

                    const hasAnyBooking = events.some(function(event) {
                        const eventStart = new Date(event.start);
                        const eventEnd = new Date(event.end);
                        eventEnd.setDate(eventEnd.getDate() - 1);

                        return clickedDateObj >= eventStart && clickedDateObj <= eventEnd;
                    });

                    satuHariOption.prop('disabled', hasAnyBooking);

                }


                $('#tempahfasiliti-tempoh').on('change', function () {
                    if (!selectedDate) {
                        showBookingAlert('Sila pilih tarikh dahulu sebelum memilih sesi/tempoh.');
                        return;
                    }

                    var selectedTempoh = parseInt($(this).val());
                    var clickedDateObj = new Date(selectedDate);
                    var fasilitiId = $('#tempahfasiliti-fasiliti_id').val(); // hidden input dari form

                    var hasConflict = events.some(function(event) {
                        var eventStart = new Date(event.start);
                        var eventEnd = new Date(event.end);
                        eventEnd.setDate(eventEnd.getDate() - 1);

                        var isDateInRange = clickedDateObj >= eventStart && clickedDateObj <= eventEnd;

                         if (!isDateInRange) return false;

                        var bookedTempoh = parseInt(event.extendedProps.tempoh);

                        // Case 1: Tempoh 5 (full day) block semua sesi
                        if (bookedTempoh === 5) return true;

                        // Case 2: Tempoh 4 (half day pagi+petang)
                        if (bookedTempoh === 4) {
                            if (fasilitiId >= 15 && fasilitiId <= 18) {
                                // special case gelanggang sukan → masih boleh pilih malam
                                return selectedTempoh !== 3; 
                            }
                            return true; // block semua sesi lain
                        }

                        // Case 3: Kalau user pilih full day (5), block semua existing booking
                        if (selectedTempoh === 5) return true;

                        // Case 4: Kalau user pilih half-day (4), check kalau dah ada pagi/petang
                        if (selectedTempoh === 4 && (bookedTempoh === 1 || bookedTempoh === 2)) {
                            if (fasilitiId >= 15 && fasilitiId <= 18) {
                                // gelanggang sukan → still valid
                                return false;
                            }
                            return true;
                        }

                        // Case 5: sesi sama clash
                        return bookedTempoh === selectedTempoh;
                    }); 

                    if (hasConflict) {
                        showBookingAlert('Sesi ini telah ditempah pada tarikh tersebut. Sila pilih sesi lain atau tarikh lain.');
                        $(this).val(''); // Reset pilihan
                    }
                });



                var formattedDate = formatDate(clickedDate);

                if (!startDateSelected) {
                    $('#tempahfasiliti-tarikh_masuk').val(formattedDate);
                    startDateSelected = true;
                    scrollToInput('tempahfasiliti-tarikh_masuk');
                } else if (!endDateSelected) {
                    // Validate end date is not earlier than start date
                    var startDate = $('#tempahfasiliti-tarikh_masuk').val();
                    if (!validateDates(startDate, formattedDate) && startDate !== formattedDate) {
                        showBookingAlert('Tarikh tamat mestilah sama atau selepas tarikh mula.');
                        // Reset both dates
                        $('#tempahfasiliti-tarikh_masuk').val('');
                        $('#tempahfasiliti-tarikh_keluar').val('');
                        startDateSelected = false;
                        endDateSelected = false;
                        scrollToInput('tempahfasiliti-tarikh_masuk');
                        return;
                    }

                    $('#tempahfasiliti-tarikh_keluar').val(formattedDate);
                    endDateSelected = true;
                    scrollToInput('tempahfasiliti-tarikh_keluar');
                }
                else {
                    // Reset selection
                    $('#tempahfasiliti-tarikh_masuk').val('');
                    $('#tempahfasiliti-tarikh_keluar').val('');
                    startDateSelected = false;
                    endDateSelected = false;
                    scrollToInput('tempahan-info');
                }
            }
        });

        var selectedDate = null;

        calendar.render();

        $('#tempahan-form').on('beforeSubmit', function(e) {
            var startDate = $('#tempahfasiliti-tarikh_masuk').val();
            var endDate = $('#tempahfasiliti-tarikh_keluar').val();


            if (!validateDates(startDate, endDate)) {
                showBookingAlert('Tarikh tamat mestilah selepas tarikh mula.');
                e.preventDefault();
                return false;
            }

            if (!isStartDateAfterCurrentDate(startDate)) {
                showBookingAlert('Tarikh masuk mestilah selepas hari ini.');
                e.preventDefault();
                return false;
            }

            if (
                userRole === 3 && (
                    isPublicHoliday(new Date(startDate.split('-').reverse().join('-'))) || 
                    isPublicHoliday(new Date(endDate.split('-').reverse().join('-')))
                    )
                ) {
                    showBookingAlert('Tempahan tidak dibenarkan pada cuti umum.');
                    e.preventDefault();
                    return false;
                }


            if (
                userRole === 3 && (
                    isWeekend(new Date(startDate.split('-').reverse().join('-'))) || 
                    isWeekend(new Date(endDate.split('-').reverse().join('-')))
                )
            ) {
                showBookingAlert('Tempahan tidak dibenarkan pada hujung minggu (Sabtu & Ahad).');
                e.preventDefault();
                return false;
            }


            function getDateRange(start, end) {
                var dateArray = [];
                var currentDate = new Date(start);
                var endDate = new Date(end);

                while (currentDate <= endDate) {
                    dateArray.push(new Date(currentDate));
                    currentDate.setDate(currentDate.getDate() + 1);
                }

                return dateArray;
            }



        return true;
    });
});
", \yii\web\View::POS_READY);

?>
