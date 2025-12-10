<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'MyFasiliti';

$userRole = Yii::$app->user->identity->role;

$filteredFasiliti = array_filter($Fasiliti, function ($facility) use ($userRole) {
    // if (!in_array($userRole, [0, 1, 6, 8]) && $facility->fasiliti_status != 0) {
    //     return false;
    // }
    // Role biasa (bukan 0,1,6,8) hanya boleh nampak fasiliti status = 0 atau 4
    if (!in_array($userRole, [0, 1, 6, 8]) && !in_array($facility->fasiliti_status, [0, 4])) {
        return false;
    }

    if ($userRole == 3 && $facility->akses_pengguna != 0) {
        return false;
    }
    return true;
});
?>

<div class="all-facilities-container">
    <div class="facilities-grid">
        <?php foreach ($filteredFasiliti as $facility): ?>
            <div class="facility-card">
                <div class="facility-image">
                    <?php if (!empty($facility->gambar)): ?>
                        <img src="<?= Yii::$app->request->baseUrl . '/images/' . $facility->gambar ?>" alt="<?= Html::encode($facility->nama_fasiliti) ?>" />
                    <?php else: ?>
                        <p>No image uploaded.</p>
                    <?php endif; ?>
                </div>

                <h3 class="facility-name"><?= Html::encode($facility->nama_fasiliti) ?></h3>

                <div class="facility-action">
                    <?= Html::a('Maklumat Penuh', 'javascript:void(0);', [
                        'class' => 'info-link',
                        'onclick' => "openModal(" . Html::encode($facility->id) . ")",
                        'aria-label' => 'Maklumat penuh fasiliti ' . Html::encode($facility->nama_fasiliti),
                        'title' => 'Lihat maklumat penuh fasiliti ini'
                    ]); ?>
                </div>

                <?php
                    $redirectUrl = ($facility->id == 13) ? '/asrama/bilik' : '/tempah-fasiliti/create';
                    echo Html::a('Tempah', [
                        $redirectUrl,
                        'fasiliti_id' => $facility->id,
                        'nama_fasiliti' => $facility->nama_fasiliti,
                    ], [
                        'class' => 'btn btn-primary bayar-button',
                        'aria-label' => 'Tempah fasiliti ' . Html::encode($facility->nama_fasiliti),
                        'title' => 'Tempah fasiliti ini'
                    ]);
                ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal GLOBAL di luar loop -->
<div id="globalModal" class="custom-modal">
    <div class="modal-content" id="modalContent">
        <span class="close-btn" onclick="closeGlobalModal()">&times;</span>
        <div class="modal-body" id="modalBody"></div>
        <button class="btn btn-secondary" onclick="closeGlobalModal()">Tutup</button>
    </div>
</div>

<?php
$fasilitiArray = array_values(array_map(function ($f) {
    return [
        'id' => $f->id,
        'nama_fasiliti' => $f->nama_fasiliti,
        'deskripsi' => $f->deskripsi,
        'kadar_sewa_perJam' => $f->kadar_sewa_perJam,
        'kadar_sewa_perHari' => $f->kadar_sewa_perHari,
        'kadar_sewa_perJamSiang' => $f->kadar_sewa_perJamSiang,
        'kadar_sewa_perJamMalam' => $f->kadar_sewa_perJamMalam,
    ];
}, (array) $filteredFasiliti));

$this->registerJs("const fasilitiData = " . json_encode($fasilitiArray, JSON_UNESCAPED_UNICODE) . ";", \yii\web\View::POS_HEAD);



$this->registerCss('
.all-facilities-container {
    display: flex;
    justify-content: center;
    padding: 40px 20px;
}

.facilities-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    max-width: 1200px;
    width: 100%;
}

@media (max-width: 791px) {
    .facilities-grid {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }
    .all-facilities-container {
        transform: translateY(-40px);
    }
}

.facility-card {
    display: flex;
    flex-direction: column;
    padding: 10px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    background: #fff;
}

.facility-name {
    font-size: 1.2rem;
    font-weight: bold;
    color: #333;
    padding: 10px;
    text-align: left;
    margin-left: 10px;
}

.info-link {
    text-decoration: underline;
    color: black;
    font-size: 0.95rem;
    display: inline-block;
    text-align: left;
    margin-left: 20px;
    margin-bottom: 10px;
}

.facility-action {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    width: 100%;
}

.bayar-button {
    background-color: black;
    color: white;
    font-size: 1rem;
    padding: 8px 15px;
    border-radius: 5px;
    width: 100%;
    text-align: center;
}

.info-link:hover {
    color: #0056b3;
}

.facility-image {
    width: 100%;
    height: 230px;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    border-radius: 8px;
}

.facility-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Global modal CSS */
.custom-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: auto;
    padding: 20px; /* So content tak melekat ke tepi kalau screen kecik */
}

.modal-content {
    background-color: #fff;
    padding: 25px;
    border-radius: 12px;
    width: 100%;
    max-width: 550px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

#globalModal {
    display: none;
    position: fixed;
    z-index: 9999;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0,0,0,0.5);
    justify-content: center;
    align-items: center;
    padding: 20px;
    overflow: auto;
}

#globalModal .modal-content {
    background: #fff;
    max-width: 550px;
    width: 100%;
    padding: 25px;
    border-radius: 12px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
}

.close-btn {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 28px;
    cursor: pointer;
    color: #888;
}

.close-btn:hover {
    color: #333;
}

h5 {
    font-size: 1.1rem;
    font-weight: bold;
    text-decoration: underline;
}

// .info-link {
//     background-color: rgba(255, 0, 0, 0.2); /* TEST only */
//     z-index: 99;
//     position: relative;
// }

');
?>

<script>
function openModal(facilityId) {
    const f = fasilitiData.find(f => f.id === facilityId);
    if (!f) return;

    let html = `<h3>${f.nama_fasiliti}</h3><p>${f.deskripsi.replace(/\n/g, '<br>')}</p>`;

    const kadar = [];
    if (f.kadar_sewa_perJam) kadar.push(`<li><strong>Satu Jam:</strong> RM ${parseFloat(f.kadar_sewa_perJam).toFixed(2)}</li>`);
    if (f.kadar_sewa_perHari) kadar.push(`<li><strong>Satu Hari:</strong> RM ${parseFloat(f.kadar_sewa_perHari).toFixed(2)}</li>`);
    if (f.kadar_sewa_perJamSiang) kadar.push(`<li><strong>Satu Jam Siang:</strong> RM ${parseFloat(f.kadar_sewa_perJamSiang).toFixed(2)}</li>`);
    if (f.kadar_sewa_perJamMalam) kadar.push(`<li><strong>Satu Jam Malam:</strong> RM ${parseFloat(f.kadar_sewa_perJamMalam).toFixed(2)}</li>`);

    if (kadar.length > 0) {
        html += `<h5>Kadar Sewa</h5><ul>${kadar.join('')}</ul>`;
    }

    const modal = document.getElementById('globalModal');
    const modalBody = document.getElementById('modalBody');

    modalBody.innerHTML = html;
    modal.style.display = 'flex';
    modal.scrollTop = 0; // reset scroll dalam modal kalau panjang
    document.body.style.overflow = 'hidden';
}

function closeGlobalModal() {
    const modal = document.getElementById('globalModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

window.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("globalModal");
    if (modal) {
        modal.style.display = "none";
        document.body.style.overflow = "auto";
    }
});

</script>
