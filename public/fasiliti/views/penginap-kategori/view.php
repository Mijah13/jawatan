<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\PenginapKategori $model */

// $this->title = $model->id_penginap;
// $this->params['breadcrumbs'][] = ['label' => 'Penginap Kategoris', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="penginap-kategori-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <title><?= Html::encode($this->title ?? 'MyFasiliti') ?></title>

    <p>
        <?= Html::a('Update', ['update', 'id_penginap' => $model->id_penginap], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_penginap' => $model->id_penginap], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_penginap',
            'jenis_penginap',
        ],
    ]) ?>

</div>
