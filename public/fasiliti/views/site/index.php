<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'eFasiliti';
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

    <!-- Link to the external CSS file -->
    <?= Html::cssFile('@web/css/index.css') ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="site-index text-center">

    <!-- Gradient Box Container -->
    <div class="gradient-box mt-5 mb-5">
        <div class="jumbotron">
            <div class="header-logo">
                <img src="<?= Yii::getAlias('@web') ?>/images/LogoCiast.png" alt="Logo">
                <h1 class="display-1 text-uppercase">E-Tempah Fasiliti CIAST</h1>
            </div>
            <p class="lead mt-3">Unit Pengurusan Aset dan Kemudahan Ciast</p>
        </div>
    </div>
  
</div>

<?php $this->endBody() ?>

</body>
</html>
