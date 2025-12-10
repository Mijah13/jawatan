<?php

use app\models\PenginapKategori;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PenginapKategoriSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'MyFasiliti';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penginap-kategori-index">

    <h1>Penginap Kategori</h1>

    <p>
        <?= Html::a('Create Penginap Kategori', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_penginap',
            'jenis_penginap',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, PenginapKategori $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_penginap' => $model->id_penginap]);
                 }
            ],
        ],
    ]); ?>


</div>
