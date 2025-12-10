<?php
namespace app\controllers;
use yii\helpers\Html;

$this->title = 'MyFasiliti';

// Link to external CSS file
$this->registerCssFile("@web/css/kemudahan.css");
?>

<div class="site-kemudahan" style="display: flex;">
    <!-- Left Sidebar (Facility Buttons) -->
    <div class="facility-buttons">
        <div class="menu-bar">
            <?php foreach ($Fasiliti as $facility): ?>
                <a href="#facility-<?= $facility->id ?>" class="facility-link" 
                   data-facility-id="<?= $facility->id ?>"
                   data-desc="<?= Html::encode(nl2br($facility->deskripsi ?: 'Tiada deskripsi tersedia.')) ?>"
                   data-rental-rate='<?= json_encode([
                    'perHour' => $facility->kadar_sewa_perJam,
                    'perDay' => $facility->kadar_sewa_perHari,
                    'perHourSiang' => $facility->kadar_sewa_perJamSiang,
                    'perHourMalam' => $facility->kadar_sewa_perJamMalam
                ]) ?>'>
                    <?= Html::encode($facility->nama_fasiliti) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Right Content (Facility Information) -->
    <div class="facility-info-box" style="margin-left: 20px;">
        <h2 id="facility-name"></h2>
        <p id="facility-desc"></p>
        <h3>Kadar Sewa</h3>
        <div id="facility-rental-rates"></div>
    </div>
</div>

<!-- jQuery and FullCalendar Integration -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet" /> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script> -->

<script>
$(document).ready(function() {
    // Set Dewan Besar as the default facility
    var defaultFacilityId = 1; // Replace this with the correct type_id for Dewan Besar
    var defaultFacilityLink = $('.facility-link[data-facility-id="' + defaultFacilityId + '"]');

    // Display Dewan Besar by default
    if (defaultFacilityLink.length) {
        displayFacilityInfo(defaultFacilityLink);
    }

    // When a facility link is clicked
    $('.facility-link').click(function(e) {
        e.preventDefault();
        sessionStorage.setItem('facilitySelected', true); // Mark a facility as selected
        displayFacilityInfo($(this)); // Display selected facility's info
    });

    /**
     * Function to display facility information
     * @param {object} linkElement - The jQuery object for the clicked facility link
     */
    function displayFacilityInfo(linkElement) {
        var facilityName = linkElement.text();
        var facilityDesc = linkElement.data('desc').replace(/\n/g, '<br>'); // Line breaks
        var rentalRates = linkElement.data('rental-rate');
        var facilityId = linkElement.data('facility-id');

        $('#facility-name').text(facilityName);
        $('#facility-desc').html(facilityDesc); // Use .html() for <br> tags

        // Clear and populate rental rates content
        $('#facility-rental-rates').empty();

        if (facilityId == 13) { // If facility is Asrama
            var asramaHtml = `
                <table>
                    <tr>
                        <th>Jenis Bilik</th>
                        <th>Kadar Sewa (RM)</th>
                    </tr>
                    <?php foreach ($jenisAsrama as $room): ?>
                        <tr>
                            <td><?= Html::encode($room->jenis_bilik) ?></td>
                            <td>RM<?= Html::encode(number_format($room->kadar_sewa, 2)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            `;
            $('#facility-rental-rates').html(asramaHtml);
        } else {
            try {
                if (rentalRates && typeof rentalRates === 'object') {
                    var table = '<table><tr><th>Masa</th><th>Kadar Sewa (RM)</th></tr>';

                    // Render rates if available
                    if (rentalRates.perHour && rentalRates.perHour !== 'null') {
                        table += '<tr><td>1 Jam</td><td>RM' + parseFloat(rentalRates.perHour).toFixed(2) + '</td></tr>';
                    }
                    if (rentalRates.perDay && rentalRates.perDay !== 'null') {
                        table += '<tr><td>1 Hari</td><td>RM' + parseFloat(rentalRates.perDay).toFixed(2) + '</td></tr>';
                    }
                    if (rentalRates.perHourSiang && rentalRates.perHourSiang !== 'null') {
                        table += '<tr><td>1 Jam (Siang)</td><td>RM' + parseFloat(rentalRates.perHourSiang).toFixed(2) + '</td></tr>';
                    }
                    if (rentalRates.perHourMalam && rentalRates.perHourMalam !== 'null') {
                        table += '<tr><td>1 Jam (Malam)</td><td>RM' + parseFloat(rentalRates.perHourMalam).toFixed(2) + '</td></tr>';
                    }

                    table += '</table>';
                    $('#facility-rental-rates').html(table);
                } else {
                    $('#facility-rental-rates').html('Kadar sewa tidak tersedia.');
                }
            } catch (err) {
                console.error('Error parsing rental rates: ', err);
                $('#facility-rental-rates').html('Kadar sewa tidak tersedia.');
            }
        }
    }
});
</script>
