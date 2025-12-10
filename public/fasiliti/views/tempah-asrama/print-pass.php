<style>
    body {
        font-family: Helvetica, sans-serif;
        margin: 40px;
        display: block; /* tukar daripada flex ke block */
    }

    .pass-card {
        border: 1px solid #333;
        padding: 20px;
        width: 100%; /* ambik full width */
        max-width: 600px;
        text-align: center;
        font-size: 18px;
        position: relative;
        box-sizing: border-box;
        margin: 0 auto 40px auto; /* auto center + jarak bawah */
        page-break-inside: avoid; /* elak terbelah masa print */
    }

    .pass-card h2 {
        margin-bottom: 20px;
        font-size: 24px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .pass-info {
        text-align: left;
        margin: 20px auto;
        display: inline-block;
        font-size: 18px;
    }

    .pass-info p {
        margin: 8px 0;
        /* margin-left: 0;  */
    }

    .unique-id {
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 14px;
        color: #999;
    }

    .footer-note {
        font-size: 13px;
        margin-top: 30px;
        color: #555;
    }
</style>

<?php
$plates = json_decode($model->no_plate, true);
if (!is_array($plates)) {
    $plates = [$model->no_plate]; // fallback kalau bukan JSON
}

foreach ($plates as $index => $plate):
?>
    <div class="pass-card">
        <!-- Header: Logo kiri + ID kanan -->
    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
        <img src="<?= Yii::$app->request->baseUrl ?>/images/logoM.png" 
             alt="Logo CIAST" 
             style="max-height: 60px;">
        <div class="unique-id" style="position: static;">
            ID: <?= 'ASR' . str_pad($model->id, 6, '0', STR_PAD_LEFT) ?>-<?= $index+1 ?>
        </div>
    </div>
            <h3>PAS KENDERAAN PENGHUNI</h3>

        <div class="pass-info">
            <p><strong>No. Plat Kenderaan:</strong> <?= htmlspecialchars($plate) ?></p>
            <p><strong>Nama:</strong> <?= $user->nama ?></p>
            <p><strong>No. Telefon:</strong> <?= $model->no_tel ?></p>
        </div>

        <div class="footer-note">
            Sila <strong>papar pass</strong> ini pada dashboard kereta anda semasa berada di dalam kawasan CIAST.
        </div>
    </div>
<?php endforeach; ?>

<script>
    window.onload = function () {
        window.print();
    };
</script>
