<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PenginapKategori $model */

// $this->title = 'Create Penginap Kategori';
// $this->params['breadcrumbs'][] = ['label' => 'Penginap Kategoris', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="penginap-kategori-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <title><?= Html::encode($this->title ?? 'MyFasiliti') ?></title>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
