<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Expression;
use app\models\Asrama;
use app\models\JenisAsrama;
use app\models\Fasiliti;

$this->title = 'MyFasiliti';
$this->registerCssFile('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.css');
$this->registerJsFile(
    'https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);

$this->registerJsFile(
    'https://code.jquery.com/ui/1.12.1/jquery-ui.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
$this->registerCssFile('https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');

// Gabungkan dua jenis (fasiliti + asrama) untuk dropdown
$fasilitiList = ArrayHelper::map(
    Fasiliti::find()->where(['!=', 'id', 13])->all(),
    'id',
    'nama_fasiliti'
);

$asramaList = ArrayHelper::map(
    JenisAsrama::find()->where(['!=', 'id', 5])->all(),
    'id',
    'jenis_bilik'
);

?>

<div class="dashboard">

<div class="calendar-container">
    <div class="card-header bg-light-gray" style="font-size:large; font-weight:bold;">
        <h1>Kalendar Tempahan</h1>
    </div>
    <?php
    // Tambah prefix untuk identify source
    foreach ($fasilitiList as $id => $name) {
        $filterOptions['f-' . $id] = 'Fasiliti: ' . $name;
    }
    foreach ($asramaList as $id => $name) {
        $filterOptions['a-' . $id] = 'Asrama: ' . $name;
    }

    $filterOptions = ['all' => 'Semua Jenis'] + $filterOptions;

    echo Html::dropDownList('filter', 'all', $filterOptions, [
        'class' => 'form-select mb-3 px-3 py-2',
        'id' => 'eventFilter',
        'style' => 'max-width: 300px; background-color:rgb(255, 255, 255); font-weight: 500;',
    ]);

    ?>
    <div id="calendar"></div>
</div>

    <!-- Statistik Section -->
    <!-- Statistik Section -->
<div class="laporan-statistik row d-none d-md-flex"> <!-- Tambah kelas ni -->
    <div class="col-md-12 d-flex flex-wrap justify-content-between align-items-start">
        <!-- Bar Chart: Statistik Tempahan Fasiliti -->
        <div class="card shadow-lg rounded border-0 p-4 me-3" style="flex: 1;">
            <div class="panel panel-primary">
                <div class="panel-heading fw-bold mb-3">Statistik Tempahan Fasiliti</div>
                <div class="panel-body">
                    <!-- Dropdown for Chart 1 -->
                    <form method="get" class="dropdown-form mb-3">
                        <label for="year_chart1" class="dropdown-label">Pilih Tahun:</label>
                        <select name="year_chart1" id="year_chart1" class="form-select stylish-dropdown" onchange="this.form.submit()">
                            <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                                <option value="<?= $y ?>" <?= isset($_GET['year_chart1']) && $_GET['year_chart1'] == $y ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </form>
                    <?php if (!empty($facilityStats)): ?>
                        <canvas id="tempahanFasilitiChart" width="400" height="200"></canvas>
                    <?php else: ?>
                        <p class="text-muted">Tiada data statistik tempahan fasiliti.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Pie Chart: Statistik Penghuni Asrama -->
        <div class="card shadow-lg rounded border-0 p-4" style="flex: 0.5;">
            <div class="panel panel-success">
                <div class="panel-heading fw-bold mb-3">Statistik Penghuni Asrama</div>
                <div class="panel-body">
                    <!-- Dropdown for Chart 2 -->
                    <form method="get" class="dropdown-form mb-3">
                        <label for="year_chart2" class="dropdown-label">Pilih Tahun:</label>
                        <select name="year_chart2" id="year_chart2" class="form-select stylish-dropdown" onchange="this.form.submit()">
                            <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                                <option value="<?= $y ?>" <?= isset($_GET['year_chart2']) && $_GET['year_chart2'] == $y ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </form>
                    <?php if (!empty($asramaStats)): ?>
                        <canvas id="penghuniAsramaChart" width="300" height="200"></canvas>
                    <?php else: ?>
                        <p class="text-muted">Tiada data statistik penghuni asrama.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</div>


<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
// Data for Tempahan Fasiliti Chart
<?php if (!empty($facilityStats)): ?>
    const tempahanFasilitiLabels = <?= json_encode(array_column($facilityStats, 'jenis_fasiliti')) ?>;
    const tempahanFasilitiData = <?= json_encode(array_column($facilityStats, 'total_bookings')) ?>;

    new Chart(document.getElementById('tempahanFasilitiChart'), {
        type: 'bar',
        data: {
            labels: tempahanFasilitiLabels,
            datasets: [{
                label: 'Jumlah Tempahan',
                data: tempahanFasilitiData,
                backgroundColor: 'rgba(40, 96, 143, 0.8)', // corporate blue
                borderColor: 'rgba(40, 96, 143, 0.8)', 
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            // scales: {
            //     y: { beginAtZero: true }
            // }
            scales: {
                x: {
                    ticks: { color: '#333' },
                    grid: { color: '#eee' }
                },
                y: {
                    ticks: { color: '#333' },
                    grid: { color: '#eee' }
                }
            }
        }
    });
<?php endif; ?>


// Data for Penghuni Asrama Chart
<?php if (!empty($asramaStats)): ?>

    const penghuniAsramaLabels = <?= json_encode(array_column($asramaStats, 'jenis_bilik')) ?>;
    const penghuniAsramaData = <?= json_encode(array_column($asramaStats, 'total_residents')) ?>;
    const totalResidents = penghuniAsramaData.reduce((a, b) => a + b, 0);
    const totalMaleData = <?= json_encode(array_column($asramaStats, 'total_male')) ?>;
    const totalFemaleData = <?= json_encode(array_column($asramaStats, 'total_female')) ?>;
    
    new Chart(document.getElementById('penghuniAsramaChart'), {
        type: 'pie',
        data: {
            labels: penghuniAsramaLabels,
            datasets: [{
                label: 'Jumlah Penghuni',
                data: penghuniAsramaData,
                backgroundColor: [
                    'rgba(40, 96, 143, 0.8)',   // Corporate Blue
                    'rgba(76, 175, 80, 0.7)',    // Medium Green
                    'rgba(255, 152, 0, 0.7)',    // Soft Orange
                    'rgba(156, 39, 176, 0.7)',   // Purple
                    'rgba(244, 67, 54, 0.7)'     // Soft Red
                ],
                borderColor: [
                    'rgba(40, 96, 143, 0.8)',
                    'rgba(76, 175, 80, 1)',
                    'rgba(255, 152, 0, 1)',
                    'rgba(156, 39, 176, 1)',
                    'rgba(244, 67, 54, 1)'
                ],
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                    labels: { boxWidth: 20, padding: 10 }
                },
                datalabels: {
                    color: '#fff',
                    font: { weight: 'bold', size: 10 },
                    formatter: (value, context) => {
                        let index = context.dataIndex; // Get the index of the data point
                        let percentage = ((value / totalResidents) * 100).toFixed(2);
                        return percentage + '%';
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const index = context.dataIndex;
                            const label = context.label;
                            const value = context.raw;
                            const male = totalMaleData[index];
                            const female = totalFemaleData[index];
                            // return `${label}: ${value} penghuni (L = ${male}, P = ${female})`;
                             return label + ': ' + value + ' penghuni (L = ' + male + ', P = ' + female + ')';

                        }
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
<?php endif; ?>

</script>

<?php
$this->registerCss('
.dashboard {
    padding: 20px;
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    // padding-top: -100px;
}

.panel-heading {
    font-size: 18px;
    font-weight: bold;
}
.table {
    width: 100%;
    margin-top: 10px;
}
.table th, .table td {
    text-align: center;
    padding: 10px;
}

.row.report-buttons { 
    padding-left: 120px;
    padding-right: 120px;
}

@media (max-width: 768px) {
    .row.report-buttons {
        padding-left: 20px;
        padding-right: 20px;
    }
}

.report-tile {
    border: 1px solid rgb(94, 88, 88);
    border-radius: 10px;
    transition: all 0.2s ease;
    background-color: #2C3E50;   /* Primary */
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

h1 {
        font-size: 1.5rem; /* Adjust size */
        font-weight: bold; /* Make it bold */
        color:rgb(32, 42, 111); /* Text color */
    }
    
.calendar-container {
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    margin: 30px auto;
    margin-top: -18px; /* atau 0px kalau nak betul-betul rapat */
    // max-width: 1050px;
    width: 100%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}



/* Responsive */
@media (max-width: 768px) {
    .calendar-container {
        width: 100%;
        overflow-x: auto; 
        padding: 10px;
    }

    #calendar {
        font-size: 10px;
        padding: 15px;
    }

    /* Scroll for multiple events */
    .fc-daygrid-day-frame {
        max-height: 100px;
        overflow-y: auto;
    }

    .fc-daygrid {
        min-width: 100% !important;
    }
    
}

/* Make label and select inline */
.dropdown-form {
    display: flex;
    align-items: center;
    gap: 8px; /* Adjust spacing */
}

.dropdown-label {
    font-weight: bold;
    color: #333;
    white-space: nowrap; /* Prevents label from breaking */
}

.stylish-dropdown {
    border-radius: 3px;
    padding: 6px 12px;
    width: auto;
    min-width: 100px;
    border: 1px solid #ccc;
    background-color: #fff;
    cursor: pointer;
    transition: all 0.3s ease;
}

.stylish-dropdown:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
}

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

.tooltip-left .tooltip-inner {
    text-align: left;
}

        
@media (max-width: 480px) { /* Lebih ketat untuk saiz mobile kecil */
    .calendar-container {
        overflow-x: auto !important; /* Pastikan boleh scroll kalau besar */
        width: 100% !important;
        max-width: 100% !important; /* Supaya tak melebihi skrin */
    }

    .calendar {
        max-width: 100% !important; /* Paksa calendar ikut container */
        width: 100% !important;
        /* min-width: 300px !important; */
        font-size: 9px !important; /* Lebih kecil */
        padding: 10px;
        overflow-x: hidden !important;
    }

    .fc {
        font-size: 12px !important; /* General calendar text */
    }

    .fc-toolbar-title {
        font-size: 14px !important; /* Tajuk bulan lebih kecil */
    }

    .fc-daygrid-day {
        min-width: 30px !important; /* Kecilkan setiap sel */
        padding: 1px !important;
    }

    .fc-daygrid-day-number {
        font-size: 12px !important; /* Nombor tarikh lebih kecil */
    }

    .fc-daygrid-day-frame {
        max-height: 80px !important; /* Kurangkan tinggi setiap kotak hari */
        overflow-y: auto; /* Boleh scroll kalau banyak event */
    }

    .fc-event {
        font-size: 10px !important; /* Event text kecilkan lagi */
        padding: 1px 3px !important; /* Kurangkan padding dalam event */
    }

    .fc-button {
        font-size: 9px !important; /* Button kecilkan */
        padding: 3px 3px !important;
    }

    .fc-toolbar {
        flex-wrap: wrap;
        justify-content: center;
    }

    .form-grid {
        grid-gap: 5px !important; /* Kurangkan jarak antara field */
    }


    .fc-view {
        min-width: 350px !important;
        max-width: 100%;
        overflow-x: auto;
    }
    
    
}


');
   
$this->registerJs("
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
            aspectRatio: window.innerWidth < 768 ? 0.8 : 1.35,
            height: window.innerWidth < 768 ? 400 : 500,
            // events: '" . Url::to(['laporan/get-events']) . "', 
            events: function(info, successCallback, failureCallback) {
                var filter = $('#eventFilter').val(); // Ambil value dari dropdown

                $.ajax({
                    url: '" . Url::to(['laporan/get-events']) . "',
                    data: { filter: filter },
                    success: function(data) {
                        successCallback(data);
                    },
                    error: function() {
                        failureCallback();
                    }
                });
            },
           
            
            eventDidMount: function(info) {
                var startDate = info.event.start.toLocaleDateString();
                var endDate = info.event.end ? new Date(info.event.end.getTime() - 86400000).toLocaleDateString() : '';

                var splitTitle = info.event.title.split(' - ');
                var fasiliti = splitTitle[0] || '';
                var tujuan = splitTitle[1] || '';
                var nama = splitTitle[2] || '';

                var tooltipContent = 'Fasiliti: ' + fasiliti + '<br>' +
                     'Nama: ' + nama + '<br>' +
                     'Tujuan: ' + tujuan + '<br>' +
                     'Tarikh: ' + startDate;

                if (endDate !== '' && endDate !== startDate) {
                    tooltipContent += ' - ' + endDate;
                }

                // Sesi map
                var sessionMap = {
                    1: 'pagi : 9am - 12pm',
                    2: 'petang : 2pm - 5pm',
                    3: 'malam : 8pm - 11pm',
                    4: 'Pagi - Petang',
                    5: 'Satu Hari'
                };

                if (info.event.extendedProps.session && sessionMap[info.event.extendedProps.session]) {
                    tooltipContent += '<br>Sesi ' + sessionMap[info.event.extendedProps.session];
                }

                $(info.el).tooltip({
                    title: tooltipContent,
                    html: true,
                    container: 'body',
                    placement: 'top',
                    trigger: 'hover',
                    customClass: 'tooltip-left' // add this
                });
            }



        });

        calendar.render();
         $('#eventFilter').on('change', function() {
                calendar.refetchEvents();
            });
    });
", \yii\web\View::POS_READY);



?>