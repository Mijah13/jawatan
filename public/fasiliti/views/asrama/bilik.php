<?php
namespace app\controllers;

use app\models\JenisAsrama;
use app\models\TempahAsrama;
use app\models\Asrama;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use Yii;

$this->title = 'MyFasiliti';
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');//Flatpickr Css
$this->registerJsFile('https://cdn.jsdelivr.net/npm/flatpickr');


$jenisBilikMapping = ArrayHelper::map(
    JenisAsrama::find()->all(),
    'id',
    'jenis_bilik'
);
?>
<div class="floating-box">
    <div class="floating-content d-flex gap-3 w-100">
        <!-- Date Picker Input -->
        <div class="input-group">
            <span class="input-group-text" id="calendarTrigger"><i class="bi bi-calendar"></i></span>
            <input type="text" id="date-range" class="form-control"
                placeholder="Tarikh masuk - Tarikh keluar"
                autocomplete="off">
            <input type="hidden" id="tarikh_masuk" name="tarikh_masuk">
            <input type="hidden" id="tarikh_keluar" name="tarikh_keluar">

        </div>


        <!-- Search Button -->
        <button type="button" class="btn btn-primary"><i class="bi bi-search"></i></button>
    </div>
</div>

<?php
$asramaCounts = Asrama::find()
    ->select(['jenis_asrama_id', 'COUNT(*) AS total'])
    ->where(['status_asrama' => 0])
    ->groupBy('jenis_asrama_id')
    ->indexBy('jenis_asrama_id')
    ->asArray()
    ->all();

$jenisAsrama = JenisAsrama::find()->all();
?>

<div class="card shadow p-3 mb-5 bg-white rounded" >
    <div class="card-body">
        <div class="all-room-container">
            <div class="room-grid">
            <?php foreach ($jenisAsrama as $roomType): ?>
                <?php
                    $userRole = Yii::$app->user->identity->role;
                    $akses = (int) $roomType->akses_pengguna;

                    // Skip jika akses_pengguna = 1 dan role = 3
                    if ($akses === 1 && $userRole === 3) {
                        continue;
                    }

                    // Optional tambahan: contoh filter lama bilik id == 5 untuk role 3/4
                    if (($userRole == 3 || $userRole == 4) && $roomType->id == 5) {
                        continue;
                    }
                ?>
                <div class="facility-card row">
                    <div class="col-md-4">
                    <?php 
                    $images = json_decode($roomType->gambar, true) ?? ['/images/Office.jpeg']; 
                    ?>
                    <div id="carousel<?= $roomType->id ?>" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($images as $index => $image): ?>
                                <?php 
                                $imagePath = Yii::$app->request->baseUrl . '/images/' . trim($image);
                                ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                    <img src="<?= $imagePath ?>" 
                                        alt="<?= Html::encode($roomType->jenis_bilik) ?>" 
                                        class="d-block w-100 fixed-image" 
                                        onerror="this.onerror=null; this.src='/images/Office.jpeg';">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?= $roomType->id ?>" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carousel<?= $roomType->id ?>" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <h3 class="facility-name" data-id="<?= $roomType->id ?>">
                        <?= Html::encode($roomType->jenis_bilik) ?>
                    </h3>
                    <p><?= nl2br(Html::encode($roomType->deskripsi)) ?></p>
                    
                    <?php
                    $jumlahKosong = $asramaCounts[$roomType->id]['total'] ?? 0;
                    ?>

                    <p class="availability-status"><strong>Jumlah Kekosongan:</strong> <?= $jumlahKosong ?> bilik</p>
                    </div>
                        <div class="col-md-4 text-end">
                            <p class="rental-rate"><strong>RM: </strong><?= Html::encode($roomType->kadar_sewa) ?></p>
                            <div class="facility-action">
                            <?php
                                $tarikhMasuk = Yii::$app->request->get('tarikh_masuk');
                                $tarikhKeluar = Yii::$app->request->get('tarikh_keluar');

                                $redirectUrl = Url::to([
                                    '/tempah-asrama/create', 
                                    'jenis_bilik' => $roomType->id,
                                    'tarikh_masuk' => $tarikhMasuk,
                                    'tarikh_keluar' => $tarikhKeluar
                                ]);

                                echo Html::a('Tempah', $redirectUrl, [
                                    'class' => 'btn btn-primary bayar-button',
                                    'aria-label' => 'Tempah bilik ' . Html::encode($roomType->jenis_bilik),
                                    'title' => 'Tempah bilik ini'
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
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

<script>
    var bilikMapping = <?= json_encode($jenisBilikMapping); ?>;
    console.log("Mapping bilik:", bilikMapping);
</script>
<?php
$role = Yii::$app->user->identity->role;
?>

<script>
    const userRole = <?= $role ?>;
</script>

<?php
$script = <<< JS

$(document).ready(function() {

function formatDateToLocal(date) {
    return date.toLocaleDateString("en-CA"); // Format YYYY-MM-DD ikut timezone user
}

// Function untuk check jika tarikh adalah Sabtu (6) atau Ahad (0)
function isWeekend(date) {
    var day = date.getDay();
    return (day === 6 || day === 0); // 6 = Sabtu, 0 = Ahad
}


// Initialize Flatpickr
let flatpickrConfig = {
    mode: "range",
    dateFormat: "d M Y",
    // minDate: "today",
    showMonths: 1,
    locale: {
        firstDayOfWeek: 1
    },
    onDayCreate: function(dObj, dStr, fp, dayElem) {
        var date = new Date(dayElem.dateObj);
        if (isWeekend(date)) {
            // Weekend = MERAH (warning)
            dayElem.classList.add("disabled-day"); 
            dayElem.style.backgroundColor = "#f8d7da"; // Merah lembut
            dayElem.style.color = "#721c24"; // Merah gelap (text)

            dayElem.addEventListener("click", function() {
                alert("Kami tidak menerima pelanggan untuk check-in pada hari Sabtu atau Ahad. Sila pilih tarikh lain.");
            });
        } else {
            // Weekday = BIRU CAIR
            dayElem.style.backgroundColor = "#d9edf7"; // Biru cair
            dayElem.style.color = "#31708f"; // Biru gelap (text)
        }
    },

    onChange: function(selectedDates, dateStr, instance) {
        if (selectedDates.length >= 1) {
            function formatCustomDate(date) {
                return date.getDate() + "/" + (date.getMonth() + 1) + "/" + date.getFullYear();
            }

            if (selectedDates.length === 1) {
                $("#date-range").val(formatCustomDate(selectedDates[0]));
            } else if (selectedDates.length === 2) {
                $("#date-range").val(formatCustomDate(selectedDates[0]) + " - " + formatCustomDate(selectedDates[1]));
            }
        }
    },
    onClose: function(selectedDates, dateStr, instance) {
        if (selectedDates.length === 2) {
            var tarikhMasuk = selectedDates[0];
            var tarikhKeluar = selectedDates[1];

            function toLocalISO(date) {
                const offsetDate = new Date(date.getTime() - (date.getTimezoneOffset() * 60000));
                return offsetDate.toISOString().slice(0, 10);
            }

            $("#tarikh_masuk").val(toLocalISO(tarikhMasuk));
            $("#tarikh_keluar").val(toLocalISO(tarikhKeluar));

                    }
    }

};

// Kalau role dia bukan 5 (student), baru enforce minDate
if (userRole != 5) {
    flatpickrConfig.minDate = "today";
}

flatpickr("#date-range", flatpickrConfig);
// Simpan instance flatpickr
let datepicker = flatpickr("#date-range", flatpickrConfig);

// Bila klik icon calendar → buka calendar
$("#calendarTrigger").on("click", function() {
    datepicker.open();
});

// Function untuk check jika tarikh adalah Sabtu (6) atau Ahad (0)
function isWeekend(date) {
    var day = date.getDay();
    return (day === 6 || day === 0); // 6 = Sabtu, 0 = Ahad
}

function showBookingAlert(message) {
    $("#bookingAlertMessage").text(message);
    $("#bookingAlertModal").fadeIn();
}

// Tutup modal bila tekan butang "Tutup"
$("#bookingAlertClose").click(function () {
    $("#bookingAlertModal").fadeOut();
});


// Validasi sebelum AJAX request
function validateTarikh() {
    var tarikhMasuk = $("#tarikh_masuk").val();
    var tarikhKeluar = $("#tarikh_keluar").val();

    if (!tarikhMasuk || !tarikhKeluar) {
       showBookingAlert("Sila pilih tarikh masuk dan keluar terlebih dahulu.");
        return false;
    }
    return true;
}

// AJAX request on button click
$(".btn-primary").click(function() {
    if (!validateTarikh()) return;

    var tarikhMasuk = $("#tarikh_masuk").val();
    var tarikhKeluar = $("#tarikh_keluar").val();

    $.ajax({
        url: "/asrama/get-available-rooms",
        type: "GET",
        data: { tarikh_masuk: tarikhMasuk, tarikh_keluar: tarikhKeluar },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                updateAvailableRooms(response.bilikKosong);
            } else {
                showBookingAlert("Ralat: " + (response.error || "Gagal mendapatkan data."));
            }
        },
        error: function() {
            showBookingAlert("Terdapat masalah pada server. Sila cuba lagi.");
        }
    });
});

// Handle butang bayaran
$(".bayar-button").click(function(e) {
    e.preventDefault();
    if ($(this).prop("disabled")) {
        showBookingAlert("Bilik penuh! Sila pilih bilik lain.");
        return false;
    }
    if (!validateTarikh()) return false;

    var jenisBilik = $(this).closest(".facility-card").find(".facility-name").data("id");
    var tarikhMasuk = $("#tarikh_masuk").val();
    var tarikhKeluar = $("#tarikh_keluar").val();

    var redirectUrl = "/tempah-asrama/create?jenis_bilik=" + jenisBilik + 
                    "&tarikh_masuk=" + tarikhMasuk + 
                    "&tarikh_keluar=" + tarikhKeluar;

    window.location.href = redirectUrl;
});

function updateAvailableRooms(data) {
    $(".facility-card").each(function() {
        var jenisBilikID = $(this).find(".facility-name").data("id");
        var jumlahKosong = data[jenisBilikID] ? data[jenisBilikID].total : 0;

        console.log("Jenis Bilik:", jenisBilikID, "Jumlah kosong:", jumlahKosong);

        // Update teks jumlah kekosongan
        $(this).find(".availability-status").html("<strong>Jumlah Kekosongan:</strong> " + jumlahKosong + " bilik tersedia");

        // Disable button jika bilik penuh
        var btnTempah = $(this).find(".bayar-button");
        if (jumlahKosong === 0) {
            btnTempah.prop("disabled", true).addClass("btn-secondary").removeClass("btn-primary").text("Penuh");
        } else {
            btnTempah.prop("disabled", false).addClass("btn-primary").removeClass("btn-secondary").text("Tempah Sekarang");
        }
    });
}
 
});


JS;
$this->registerJs($script, \yii\web\View::POS_READY);
?>



<?php
$this->registerCss('
    .availability-status {
        font-family: "Poppins", sans-serif;
        font-weight: 600; /* Lebih bold, nampak jelas */
        color: #1E3A8A; /* Biru pekat */
    }

    .room-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
        justify-content: center;
    }
    .facility-card {
        border: 1px solid #ddd;
        padding: 20px;
        background: #fff;
        // box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        // width: 800px;
        // display: flex;
        flex-wrap: wrap;
        align-items: center;
    }
    .facility-name {
        font-size: 20px;
        font-weight: bold;
    }
    .availability-status {
        font-size: 16px;
        color: green;
        font-weight: bold;
    }
    .rental-rate {
        font-size: 22px;
        margin-top: 0px;
        text-decoration: underline;
        
    }
    // .facility-image img {
    //     width: 100%;
    //     // height: 180px; /* Tetapkan tinggi gambar supaya semua sama */
    //     object-fit: cover;
    //     border-radius: 6px;
    // }
    // .fixed-image {
    //     width: 100%;
    //     height: 20px;
    //     object-fit: cover;
    // }

    .facility-image {
        width: 100%;
        height: 220px; /* Pastikan tinggi fix untuk semua gambar */
        // display: flex; /* Supaya gambar ikut container */
        align-items: center; /* Pusatkan gambar dalam container */
        justify-content: center; /* Kalau gambar kecil, dia tengah */
        overflow: hidden; /* Elak gambar terlebih */
        border-radius: 6px;
        background: #f4f4f4; /* Optional: Bagi background kalau gambar ada transparent */
    }

    .facility-image img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Pastikan gambar penuh dan tak herot */
    }

    .fixed-image {
        width: 100%;
        height: 220px; /* Pastikan tinggi standard */
        object-fit: cover; /* Pastikan gambar fit container */
    }


    .bayar-button { 
        display: inline-block;
        font-size: 16px;
        margin-top: 100px;
        border-radius: 5px;
        // background-color: rgb(23, 42, 98);
    }
    //     .carousel-item {
    //     height: 200px; /* Tetapkan ketinggian supaya semua gambar konsisten */
    //     width: 100%;
    //     // display: absolute;
    //     align-items: center;
    //     justify-content: center;
    // }

    .carousel-inner {
    height: 220px !important; /* Paksa height tetap */
    overflow: hidden; /* Elak gambar overflow */
}

    .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .facility-action {
        text-align: right;
    }

    .btn-primary {
        background-color: rgb(23, 42, 98);
    }

    .container {
     position: relative;
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


');

$this->registerCss('

    // .floating-box {
    //     position: fixed;
    //     top: 100px; /* Adjust ikut header */
    //     left: 50%;
    //     transform: translateX(-50%);
    //     background: #fff;
    //     box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    //     padding: 15px;
    //     border-radius: 10px;
    //     z-index: 1000;
    //     width: auto;
    //     display: flex;
    //     align-items: center;
    // }

    .floating-box {
    position: absolute;
    top: 40px; /* Laraskan nilai negatif ni untuk naik ke atas */
    left: 50%;
    transform: translateX(-50%);
    background: #fff;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    padding: 10px 15px;
    border-radius: 10px;
    z-index: 1000;
    width: 100%;
    max-width: 400px;
    display: flex;
    align-items: center;
}

/* Input-group perlu penuh dalam floating-box */
.input-group {
    display: flex;
    width: 100%;
    align-items: center;
}

/* Tambah dalam file CSS kau atau <style> tag dalam view */
.flatpickr-day.selected,
.flatpickr-day.startRange,
.flatpickr-day.endRange {
    background-color: #007bff !important; /* Biru pekat */
    color: #fff !important; /* Tulisan putih */
    border-color: #0056b3;
}
.flatpickr-day:hover {
    background-color: #0069d9 !important;
    color: #fff !important;
}


/* Pastikan input grow ikut parent */
.input-group input {
    flex-grow: 1;
    min-width: 0; /* Elak overflow */
}

/* Pastikan button ikut height input */
.input-group .btn {
    flex-shrink: 0; /* Supaya button tak compress */
    height: 100%; /* Sama tinggi dengan input */
    padding: 0 12px; /* Padding standard */
}

/* Untuk Tablet & Mobile */
@media (max-width: 1024px) {
    .floating-box {
        width: 90%;
        max-width: 400px;
    }

    .input-group {
        width: 100%;
    }

    .facility-name {
        font-size: 15px;
        font-weight: bold;
        margin-top: 10px;
        text-decoration: underline;
    }

    .bayar-button { 
        display: inline-block;
        font-size: 12px;
        margin-top: 10px;
        border-radius: 5px;
        // background-color: rgb(23, 42, 98);
    }

    .facility-image img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Pastikan gambar penuh dan tak herot */
    }

    .fixed-image {
        width: 100%;
        height: 200px; /* Pastikan tinggi standard */
        object-fit: cover; /* Pastikan gambar fit container */
    }

         .carousel-item {
        height: 200px; /* Tetapkan ketinggian supaya semua gambar konsisten */
        width: 100%;
        // display: absolute;
        align-items: center;
        justify-content: center;
    }
}

/* Untuk Mobile */
@media (max-width: 768px) {
    .floating-box {
        width: 90%;
        max-width: 300px;
    }

    .facility-card {
        border: 1px solid #ddd;
        padding: 10px;
        background: #fff;
        // box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 2px;
        margin-top: 5px;

        flex-wrap: wrap;
        align-items: center;
    }

     .rental-rate {
        font-size: 18px;
        margin-top: 0px;
        text-decoration: underline;
        
    }

    .availability-status {
        font-size: 14px;
        color: green;
        font-weight: bold;
    }
}


//     .floating-box {
//     position: static; /* Remove fixed positioning */
//     margin-top: 1px; /* Maintain distance from top */
//     background: #fff;
//     box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
//     padding: 15px;
//     border-radius: 10px;
//     left: 50%;
//     display: flex;
//     align-items: center;
//     justify-content: center;
//     width: 380px;
// }



');
?>
