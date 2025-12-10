<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PenginapKategori $model */

// $this->title = 'Update Penginap Kategori: ' . $model->id_penginap;
// $this->params['breadcrumbs'][] = ['label' => 'Penginap Kategoris', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->id_penginap, 'url' => ['view', 'id_penginap' => $model->id_penginap]];
// $this->params['breadcrumbs'][] = 'Update';
?>
<div class="penginap-kategori-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <title><?= Html::encode($this->title ?? 'MyFasiliti') ?></title>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
