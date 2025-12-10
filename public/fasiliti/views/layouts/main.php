<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use app\components\NotifikasiHelper;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? 'Default description']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? 'default, keywords']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');

// Register Google Fonts (Poppins)
$this->registerLinkTag([
    'rel' => 'stylesheet',
    'href' => 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap'
]);
$this->registerCssFile('@web/css/main.css'); // CSS file

// Add Bootstrap JS bundle for dropdown functionality
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');

$role = Yii::$app->user->identity->role ?? null;
$notifikasi = NotifikasiHelper::getActionNeededCountByRole($role);
$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
// Check if the page is 'log-masuk' or 'daftar'
$isAuthPage = ($controller === 'site' && in_array($action, ['login', 'daftar']));

// Set the background conditionally
// $bgClass = $isAuthPage ? 'auth-bg' : 'default-bg';

// Set the background conditionally
$bgStyle = $isAuthPage 
    // ? "background: url('@web/images/bangunan-baru.jpeg') center/cover no-repeat; min-height: 100vh;" 
    ? "background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('@web/images/bangunan-baru.jpeg') center/cover no-repeat; min-height: 100vh;"
    : "background-color: #F7F7F7; min-height: calc(100vh - 100px);";
    // : "background-color:#EEF3F8; min-height: calc(100vh - 100px);";
?>

<?php
function renderBadge($key, $notifikasi, $class = 'ms-2')
{
    if (!empty($notifikasi[$key])) {
        return '<span class="badge bg-danger ' . $class . '">' . $notifikasi[$key] . '</span>';
    }
    return '';
}
function renderMultiBadge(array $keys, array $notifikasi, $class = 'ms-2')
{
    $total = 0;
    foreach ($keys as $key) {
        $total += $notifikasi[$key] ?? 0;
    }

    return $total > 0
        ? '<span class="badge bg-danger ' . $class . '">' . $total . '</span>'
        : '';
}


function renderAdminMenuItems($role, $notifikasi = [])
{
    $menuItems = [];

    // Full akses untuk role 0
    $isFullAccess = ($role == 0);
    $isAdminKetua = ($role == 6);
    $isAdminKemudahan = ($role == 1);
    $isAdminPEM = ($role == 8);
    $isPelulus = ($role == 2);

    // 1. Role 0 dan 6 boleh tengok senarai pengguna
    if ($isFullAccess || $isAdminKetua) {
        $menuItems[] = Html::a('Senarai Pengguna', ['/user/index'], ['class' => 'dropdown-item']);
    }

    // 2. Role 0, 6, 1 → akses semua jenis tempahan
    if ($isFullAccess || $isAdminKetua || $isAdminKemudahan || $isAdminPEM) {
        $menuItems[] = Html::a(
            'Senarai Tempahan Fasiliti' . renderBadge('tempahanBaruFasiliti', $notifikasi),
            ['/tempah-fasiliti/index'],
            ['class' => 'dropdown-item d-flex justify-content-between align-items-center', 'encode' => false]
        );

        $menuItems[] = Html::a(
            'Senarai Tempahan Asrama' . renderBadge('tempahanBaruAsrama', $notifikasi),
            ['/tempah-asrama/index'],
            ['class' => 'dropdown-item d-flex justify-content-between align-items-center', 'encode' => false]
        );

        $menuItems[] = Html::a('Tempahan Berjaya Asrama', ['/tempah-asrama/tempahan-berjaya'], ['class' => 'dropdown-item']);
        $menuItems[] = Html::a('Tempahan Gagal Asrama', ['/tempah-asrama/tempahan-gagal'], ['class' => 'dropdown-item']);
        $menuItems[] = Html::a('Tempahan Berjaya Fasiliti', ['/tempah-fasiliti/tempahan-berjaya'], ['class' => 'dropdown-item']);
        $menuItems[] = Html::a('Tempahan Gagal Fasiliti', ['/tempah-fasiliti/tempahan-gagal'], ['class' => 'dropdown-item']);
        $menuItems[] = Html::a('Informasi', ['/info/index'], ['class' => 'dropdown-item']);

        $menuItems[] = Html::a(
            'Tempahan Menunggu Bayaran Asrama' . renderBadge('asramaMenungguBayaran', $notifikasi),
            ['/tempah-asrama/menunggu-bayaran'],
            ['class' => 'dropdown-item d-flex justify-content-between align-items-center', 'encode' => false]
        );

        $menuItems[] = Html::a(
            'Tempahan Menunggu Bayaran Fasiliti' . renderBadge('fasilitiMenungguBayaran', $notifikasi),
            ['/tempah-fasiliti/menunggu-bayaran'],
            ['class' => 'dropdown-item d-flex justify-content-between align-items-center', 'encode' => false]
        );
    }

    // 3. Role 2 (Pelulus sahaja)
    if ($isPelulus) {
        $menuItems[] = Html::a(
            'Senarai Tempahan Asrama' . renderBadge('asramaPerluLulus', $notifikasi),
            ['/tempah-asrama/pelulus'],
            ['class' => 'dropdown-item d-flex justify-content-between align-items-center', 'encode' => false]
        );
        $menuItems[] = Html::a(
            'Senarai Tempahan Fasiliti' . renderBadge('fasilitiPerluLulus', $notifikasi),
            ['/tempah-fasiliti/pelulus'],
            ['class' => 'dropdown-item d-flex justify-content-between align-items-center', 'encode' => false]
        );
    }

    return implode("\n", $menuItems);
}


function renderPelulusMenuItems($role, $notifikasi) {
    $menuItems = [];
    if ($role == 2) {
       $menuItems[] = Html::a(
            'Senarai Tempahan Asrama' . renderBadge('asramaPerluLulus', $notifikasi),
            ['/tempah-asrama/pelulus'],
            [
                'class' => 'dropdown-item d-flex justify-content-between align-items-center',
                'encode' => false
            ]
        );

        $menuItems[] = Html::a(
            'Senarai Tempahan Fasiliti' . renderBadge('fasilitiPerluLulus', $notifikasi),
            ['/tempah-fasiliti/pelulus'],
            [
                'class' => 'dropdown-item d-flex justify-content-between align-items-center',
                'encode' => false
            ]
        );
    }
    return implode("\n", $menuItems);
}

function renderKewanganMenuItems($role, $notifikasi) {
    $menuItems = [];
    if ($role == 7) {
          $menuItems[] = Html::a(
            'Asrama - Menunggu Bayaran' . renderBadge('asramaMenungguBayaran', $notifikasi),
            ['/tempah-asrama/menunggu-bayaran'],
            [
                'class' => 'dropdown-item d-flex justify-content-between align-items-center',
                'encode' => false
            ]
        );

        $menuItems[] = Html::a(
            'Fasiliti - Menunggu Bayaran' . renderBadge('fasilitiMenungguBayaran', $notifikasi),
            ['/tempah-fasiliti/menunggu-bayaran'],
            [
                'class' => 'dropdown-item d-flex justify-content-between align-items-center',
                'encode' => false
            ]
        );

        $menuItems[] = Html::a('Asrama - Disahkan Bayaran', ['/tempah-asrama/tempahan-disahkan-bayaran'], ['class' => 'dropdown-item']);
        $menuItems[] = Html::a('Fasiliti - Disahkan Bayaran', ['/tempah-fasiliti/tempahan-disahkan-bayaran'], ['class' => 'dropdown-item']);
    }
    return implode("\n", $menuItems);
}


// Function to render Fasiliti & Penginapan sub-menu items for roles 0 and 1
function renderFasilitiPenginapanSubMenu($role) {
    $fasilitiItems = [];

    // Only visible for admin roles 0 and 1
    if (in_array($role, [0, 1])) {
        $fasilitiItems[] = Html::a('Senarai Bilik Asrama', ['/asrama/index'], ['class' => 'dropdown-item']);
        $fasilitiItems[] = Html::a('Senarai Fasiliti', ['/fasiliti/index'], ['class' => 'dropdown-item']);
        // $fasilitiItems[] = Html::a('Tambah Fasiliti', ['/jenis-fasiliti/create'], ['class' => 'dropdown-item']);
    }

    return implode("\n", $fasilitiItems);
}
?>


<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title ?? 'MyFasiliti') ?></title>

    <?php $this->head() ?>
    <meta name="google-site-verification" content="NsP2_Gq9rOrQDSItSvD-h7hNinT3tzRLAjgwqPoFxck" />
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>


<header class="header py-2 px-4" style="background-color: #0D3B66; color: white;">
<div class="container d-flex align-items-center">
    <div class="d-flex align-items-center">
        <!--button id="toggleSidebar" class="toggle-sidebar-btn btn btn-outline-light me-4">
            <i class="bi bi-list"></i>
        </button-->
        <div class="logo d-flex align-items-center">
            <img src="<?= Yii::getAlias('@web/favicon.ico') ?>" alt="CIAST Logo" style="height: 250px;">
            <!-- <h1 class="text-style mb-0 ms-2">MyFasiliti</h1> -->
        </div>
    </div>
         <!-- Desktop Menu -->
        <nav class="d-none d-lg-block">
            <ul class="navbar-nav flex-row">
                <?php if (!Yii::$app->user->isGuest): ?>
                    <!-- Dashboard Menu for roles 0,1,2 -->
                    <?php if (in_array(Yii::$app->user->identity->role, [0, 1, 2, 6, 7, 8])): ?>
                        <li class="nav-item mx-2 dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="fasilitiDropdownDesktop" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Dashboard
                            </a>
                            <ul class="dropdown-menu">
                                <?= Html::a('Dashboard', ['/laporan/index'], ['class' => 'dropdown-item']) ?>

                                <?php if (Yii::$app->user->identity->role == 0): ?>
                                    <?= Html::a('Log Asrama', ['/asrama-status-log/index'], ['class' => 'dropdown-item']) ?>
                                    <?= Html::a('Log Fasiliti', ['/fasiliti-status-log/index'], ['class' => 'dropdown-item']) ?>
                                <?php endif; ?>

                                <?= Html::a('Laporan Penghuni Asrama', ['/laporan/laporan-penghuni-asrama'], ['class' => 'dropdown-item']) ?>
                                <?= Html::a('Laporan Status Fasiliti', ['/laporan/laporan-status-fasiliti'], ['class' => 'dropdown-item']) ?>
                                <?= Html::a('Laporan Status Asrama', ['/laporan/laporan-status-asrama'], ['class' => 'dropdown-item']) ?>
                                <?= Html::a('Laporan Tempahan Bulanan', ['/laporan/laporan-tempahan-bulanan'], ['class' => 'dropdown-item']) ?>
                                <?= Html::a('Laporan Tempahan Tahunan', ['/laporan/laporan-tempahan-tahunan'], ['class' => 'dropdown-item']) ?>
                            </ul>
                        </li>
                    <?php endif; ?>


                    <!-- Fasiliti & Penginapan Menu -->
                    <?php if (in_array(Yii::$app->user->identity->role, [0, 1, 6])): ?>
                        <li class="nav-item mx-2 dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="fasilitiDropdownDesktop" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Fasiliti & Penginapan
                            </a>
                            <ul class="dropdown-menu">
                                <?= Html::a('Jenis Bilik', ['/jenis-asrama/index'], ['class' => 'dropdown-item']) ?>
                                <?= Html::a('Senarai Bilik Asrama', ['/asrama/index'], ['class' => 'dropdown-item']) ?>
                                <?= Html::a('Senarai Fasiliti', ['/fasiliti/index'], ['class' => 'dropdown-item']) ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <!-- Fasiliti & Penginapan Menu admin PEM -->
                    <?php if (Yii::$app->user->identity->role == 8): ?>
                        <li class="nav-item mx-2">
                            <?= Html::a('Fasiliti', ['/fasiliti/index'], ['class' => 'nav-link text-white']) ?>
                        </li>
                    <?php endif; ?>

                   <!-- Tempahan Dropdown -->
                    <?php if (in_array(Yii::$app->user->identity->role, [0, 1, 2, 3, 4, 6])): ?>
                        <li class="nav-item dropdown mx-2">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="tempahanDropdownDesktop" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                               <?= 'Tempahan ' . renderMultiBadge(['asramaPerluHantar', 'fasilitiPerluHantar'], $notifikasi) ?>
                            </a>
                            <ul class="dropdown-menu">
                                <?= Html::a('Tempah Fasiliti', ['/fasiliti/senarai-fasiliti'], ['class' => 'dropdown-item']) ?> 
                                <?= Html::a('Tempah Asrama', ['/asrama/bilik'], ['class' => 'dropdown-item']) ?>
                                
                                <?= Html::a(
                                    'Senarai Tempahan Asrama ' . renderBadge('asramaPerluHantar', $notifikasi),
                                    ['/tempah-asrama/senarai-tempahan'],
                                    ['class' => 'dropdown-item d-flex justify-content-between align-items-center']
                                ) ?> 
                                
                                <?= Html::a(
                                    'Senarai Tempahan Fasiliti ' . renderBadge('fasilitiPerluHantar', $notifikasi),
                                    ['/tempah-fasiliti/senarai-tempahan'],
                                    ['class' => 'dropdown-item d-flex justify-content-between align-items-center']
                                ) ?> 
                            </ul>
                        </li>
                    <?php endif; ?>

                    <!-- Pelajar Menu -->
                    <?php if (in_array(Yii::$app->user->identity->role, [0, 1, 6])): ?>
                        <li class="nav-item mx-2">
                        <?= Html::a('Pelajar ' . renderBadge('pelajarBaru', $notifikasi), ['/pelajar-asrama/index'], [
                                'class' => 'nav-link text-white',
                                'encode' => false
                            ]) ?>
                        </li>
                    <?php endif; ?>

                     <!-- Tempahan Menunggu Bayaran (Role 7) -->
                    <?php if (Yii::$app->user->identity->role == 7): ?>
                        <li class="nav-item dropdown mx-2">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="kewanganDropdownDesktop" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= 'Tempahan ' . renderMultiBadge(['asramaMenungguBayaran', 'fasilitiMenungguBayaran'], $notifikasi) ?>
                            </a>
                            <ul class="dropdown-menu">
                                <?= renderKewanganMenuItems(Yii::$app->user->identity->role, $notifikasi) ?> 
                            </ul>
                        </li>
                    <?php endif; ?>

                    <!-- Admin PEM Menu -->
                    <?php if (in_array(Yii::$app->user->identity->role, [0, 8])): ?>
                        <li class="nav-item dropdown mx-2">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="adminPEMDropdownDesktop" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                 <?= 'Admin PEM ' . renderBadge('tempahanBaruFasiliti', $notifikasi) ?>
                                
                            </a>
                            <ul class="dropdown-menu">
                                <?= renderAdminMenuItems(Yii::$app->user->identity->role, $notifikasi) ?>
                            </ul>
                        </li>
                    <?php endif ?>

                    <!-- Admin Menu -->
                    <?php if (in_array(Yii::$app->user->identity->role, [0, 1, 6])): ?>
                        <li class="nav-item dropdown mx-2">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="adminBKPDropdownDesktop" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= 'Admin BKP' . renderMultiBadge(['tempahanBaruAsrama', 'tempahanBaruFasiliti'], $notifikasi) ?>
                            </a>
                            <ul class="dropdown-menu">
                                <?= renderAdminMenuItems(Yii::$app->user->identity->role, $notifikasi) ?>
                            </ul>
                        </li>
                    <?php elseif (Yii::$app->user->identity->role == 2): ?>
                        <li class="nav-item dropdown mx-2">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="pelulusDropdownDesktop" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= 'Pelulus' . renderMultiBadge(['asramaPerluLulus', 'fasilitiPerluLulus'], $notifikasi) ?>
                            </a>
                            <ul class="dropdown-menu">
                                <?= renderPelulusMenuItems(Yii::$app->user->identity->role, $notifikasi) ?> 
                            </ul>
                        </li>
                    <?php endif; ?>


                    <!-- Logout -->
                    <li class="nav-item mx-2">
                        <?= Html::a('Logout (' . Yii::$app->user->identity->nama . ')', ['/site/logout'], ['data-method' => 'post', 'class' => 'nav-link text-white']) ?>
                    </li>

                <?php else: ?>
                    <!-- Jika Guest -->
                    <li class="nav-item mx-2">
                        <?php if (Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'login'): ?>
                            <?= Html::a('Daftar', ['/site/daftar', 'flash' => 'show'], ['class' => 'nav-link text-white']) ?>

                        <?php else: ?>
                            <?= Html::a('Login', ['/site/login'], ['class' => 'nav-link text-white']) ?>
                        <?php endif; ?>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>


        <!-- Toggle button for sidebar (mobile view) -->
        <button class="btn btn-outline-light d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
            <i class="fas fa-bars"></i>
        </button>
    </div>


</header>

<!-- Sidebar Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarLabel">
    <div class="offcanvas-header" style="background-color: #0D2B52; color: white;">
        <h5 class="offcanvas-title" id="sidebarLabel">MyFasiliti</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body" style="background-color: #0D2B52; color: white;">
        <nav>
            <ul class="navbar-nav">

            <!-- Fasiliti & Penginapan Menu with Submenu -->
            <li class="nav-item">

                <?php if (!Yii::$app->user->isGuest): ?>
                    <!-- Tempahan Dropdown for Mobile View -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="tempahanDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Tempahan
                        </a>
                        <ul class="dropdown-menu">
                            <?= Html::a('Tempahan Fasiliti', ['/fasiliti/senarai-fasiliti'], ['class' => 'dropdown-item']) ?> 
                            <?= Html::a('Tempahan Asrama', ['/asrama/bilik'], ['class' => 'dropdown-item']) ?>
                          
                            <?= Html::a('Senarai Tempahan Asrama', ['/tempah-asrama/senarai-tempahan'], ['class' => 'dropdown-item']) ?>
                            <?= Html::a('Senarai Tempahan Fasiliti', ['/tempah-fasiliti/senarai-tempahan'], ['class' => 'dropdown-item']) ?>
                            
                        </ul>
                    </li>

                     <!-- Pelajar Menu -->
                    <?php if (in_array(Yii::$app->user->identity->role, [0, 1, 6])): ?>
                        <li class="nav-item mx-2">
                            <?= Html::a('Pelajar', ['/pelajar-asrama/index'], ['class' => 'nav-link text-white']) ?>
                        </li>
                    <?php endif; ?>
                    
                    <?php if (in_array(Yii::$app->user->identity->role, [0, 1, 2])): ?>
                        <!-- Admin Dropdown for Mobile View -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Admin
                            </a>
                            <ul class="dropdown-menu">
                                <?= renderAdminMenuItems(Yii::$app->user->identity->role) ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <?= Html::a('Logout (' . Yii::$app->user->identity->nama . ')', ['/site/logout'], ['data-method' => 'post', 'class' => 'nav-link text-white']) ?>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <?= Html::a('Login', ['/site/login'], ['class' => 'nav-link']) ?>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</div>


<main id="main" class="flex-shrink-0" style="<?= $bgStyle ?>">
    <div class="container content-wide">
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3" style="background-color: #333; color: white;">
    <div class="container">
        <div class="row align-items-center">
            <!-- Column Kiri: Contact Info -->
            <div class="col-md-6 text-md-start text-center">
                <p class="mb-1"><i class="fas fa-envelope"></i> Email: <a href="mailto:kemudahan@ciast.gov.my" style="color: #f8f9fa;">kemudahan@ciast.gov.my</a></p>
                <p class="mb-0"><i class="fas fa-phone"></i> Telefon: <a href="tel:+60123456789" style="color: #f8f9fa;">+60 12-345 6789</a></p>
            </div>
            <!-- Column Kanan: Hak Cipta -->
            <div class="col-md-6 text-md-end text-center">
                <p>&copy; <?= date('Y') ?> Pusat Latihan Pengajar dan Kemahiran Lanjutan (CIAST)</p>
            </div>
        </div>
    </div>
</footer>



<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

<?php
$this->registerJs('

document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".dropdown-submenu a.dropdown-toggle").forEach(function(element) {
        element.addEventListener("click", function(e) {
            e.preventDefault();
            let nextEl = this.nextElementSibling;

            if (nextEl && nextEl.classList.contains("dropdown-menu")) {
                // Tutup semua submenu lain dulu
                document.querySelectorAll(".dropdown-submenu .dropdown-menu.show").forEach(function(menu) {
                    if (menu !== nextEl) {
                        menu.classList.remove("show");
                    }
                });

                // Toggle submenu yang diklik
                nextEl.classList.toggle("show");
            }
        });
    });

    // Tutup dropdown jika klik luar
    document.addEventListener("click", function(e) {
        if (!e.target.closest(".dropdown-menu")) {
            document.querySelectorAll(".dropdown-submenu .dropdown-menu.show").forEach(function(menu) {
                menu.classList.remove("show");
            });
        }
    });
});


');


$this->registerCss('

/* Pastikan semua menu, termasuk yang ada submenu, guna warna putih */
.navbar-nav .nav-link,
.navbar-nav .dropdown-toggle {
    color: #FFF !important;
}

html, body {
    height: 100%;margin: 0;
    padding: 0;
  overflow: hidden; /* Elak scroll semasa loading */
}

#pageLoader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(255, 255, 255, 0.9);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}


@media (min-width: 768px) {
    #sidebarMenu {
        max-width: 300px;
    }
}


/* Warna hover untuk semua menu */
.navbar-nav .nav-link:hover,
.navbar-nav .dropdown-toggle:hover {
    color: #FFF !important;
    background-color: rgba(255, 255, 255, 0.1); /* Optional: bagi efek hover */
}

/* Submenu inherit warna putih juga */
.dropdown-submenu .dropdown-menu .dropdown-item {
    color: #FFF !important;
}

/* Hover untuk submenu item */
.dropdown-submenu .dropdown-menu .dropdown-item:hover {
    background-color: rgba(138, 117, 117, 0.2);
    color: #FFF !important;
}


');


?>