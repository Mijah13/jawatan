<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Kalendar Tempahan';

$this->registerJsFile('https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.js', ['depends' => 'yii\web\JqueryAsset']);

$this->registerCss("
    .calendar-container {
        width: 50%;
        margin: 0 auto;
        padding: 20px;
    }
    .calendar-title {
        text-align: center;
        margin-bottom: 20px;
    }
    #calendar {
        background: white;
        padding: 20px;
        min-height: 400px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .form-container {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .alert {
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 4px;
    }
    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }
    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }
    #booking-alert {
        display: none;
        margin: 20px 0;
        padding: 10px 15px;
        border-radius: 4px;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
        text-align: center;
        font-weight: 500;
    }
    
    #booking-alert:focus {
        box-shadow: 0 0 0 0.2rem rgba(220,53,69,.25);
    }
");

$this->registerJs("
    var startDateSelected = false;
    var endDateSelected = false;

    function showBookingAlert(message) {
        var alert = $('#booking-alert');
        alert.text(message);
        alert.fadeIn();
        setTimeout(function() {
            alert.fadeOut();
        }, 3000);
    }

    function scrollToInput(inputId) {
        var element = $('#' + inputId);
        if (element.length) {
            $('html, body').animate({
                scrollTop: element.offset().top - 100 // Scroll to 100px above the input
            }, 500); // Animation duration in milliseconds
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

    $(document).ready(function() {
        var calendarEl = document.getElementById('calendar');
        if (!calendarEl) {
            console.error('Calendar element not found!');
            return;
        }

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            height: 450,
            events: '" . Url::to(['calendar/get-events', 'room_id' => $room_id]) . "',
            dateClick: function(info) {
                // Check if the clicked date has any events
                var events = calendar.getEvents();
                var hasEvent = events.some(function(event) {
                    return info.dateStr >= event.startStr && info.dateStr <= event.endStr;
                });

                if (hasEvent) {
                    showBookingAlert('Tarikh ini telah ditempah. Sila pilih tarikh lain.');
                    return;
                }

                var formattedDate = formatDate(info.date);

                if (!startDateSelected) {
                    $('#tempahasrama-tarikh_masuk').val(formattedDate);
                    startDateSelected = true;
                    scrollToInput('tempahasrama-tarikh_masuk');
                } else if (!endDateSelected) {
                    // Validate end date is after start date
                    var startDate = $('#tempahasrama-tarikh_masuk').val();
                    if (!validateDates(startDate, formattedDate)) {
                        showBookingAlert('Tarikh tamat mestilah selepas tarikh mula.');
                        // Reset both dates
                        $('#tempahasrama-tarikh_masuk').val('');
                        $('#tempahasrama-tarikh_keluar').val('');
                        startDateSelected = false;
                        endDateSelected = false;
                        scrollToInput('tempahasrama-tarikh_masuk');
                        return;
                    }
                    
                    $('#tempahasrama-tarikh_keluar').val(formattedDate);
                    endDateSelected = true;
                    scrollToInput('tempahasrama-tarikh_keluar');
                } else {
                    // Reset selection
                    $('#tempahasrama-tarikh_masuk').val('');
                    $('#tempahasrama-tarikh_keluar').val('');
                    startDateSelected = false;
                    endDateSelected = false;
                    scrollToInput('tempahan-info');
                }
            }
        });

        calendar.render();

        // Add form submit validation
        $('#tempahan-form').on('beforeSubmit', function(e) {
            var startDate = $('#tempahasrama-tarikh_masuk').val();
            var endDate = $('#tempahasrama-tarikh_keluar').val();

            if (!validateDates(startDate, endDate)) {
                showBookingAlert('Tarikh tamat mestilah selepas tarikh mula.');
                e.preventDefault();
                return false;
            }
            return true;
        });
    });
", \yii\web\View::POS_READY);
?>

<div class="calendar-container">
    <h1 class="calendar-title"><?= Html::encode($this->title) ?></h1>
    
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>
    
    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>

    <div id="calendar"></div>
    <div id="booking-alert"></div>

    <div class="form-container">
        <?php $form = ActiveForm::begin([
            'id' => 'tempahan-form',
            'action' => ['calendar/index', 'room_id' => $room_id],
            'method' => 'post',
        ]); ?>
            <?= $form->field($model, 'room_id')->hiddenInput(['value' => $room_id])->label(false) ?>
            <?= $form->field($model, 'info')->textInput(['maxlength' => true, 'placeholder' => 'Masukkan Maklumat Tempahan']) ?>
            <?= $form->field($model, 'startdate')->textInput([
                'readonly' => true, 
                'placeholder' => 'Klik pada kalendar untuk pilih tarikh',
                'value' => $model->startdate ? Yii::$app->formatter->asDate($model->startdate, 'php:d-m-Y') : ''
            ]) ?>
            <?= $form->field($model, 'enddate')->textInput([
                'readonly' => true, 
                'placeholder' => 'Klik pada kalendar untuk pilih tarikh',
                'value' => $model->enddate ? Yii::$app->formatter->asDate($model->enddate, 'php:d-m-Y') : ''
            ]) ?>
            <div class="form-group">
                <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
                <?= Html::resetButton('Reset', [
                    'class' => 'btn btn-secondary',
                    'onclick' => 'startDateSelected = false; endDateSelected = false; return true;'
                ]) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<!-- Add this for debugging -->
<script>
    console.log('Page loaded');
</script> 