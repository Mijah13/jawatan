<?php

use app\models\FasilitiStatusLog;
use app\models\Fasiliti;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\FasilitiStatusLogSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Fasiliti Status Logs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fasiliti-status-log-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Fasiliti Status Log', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager',
            'maxButtonCount' => 3, 
            'firstPageLabel' => '«',  // First page
            'lastPageLabel' => '»',   // Last page
            'prevPageLabel' => false,  // buang prev
            'nextPageLabel' => false,  // buang next
            'options' => ['class' => 'mt-3'] 
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'fasiliti_id',
            // 'fasiliti.nama_fasiliti',
            [
                'attribute' => 'fasiliti_id',
                'label' => 'Nama Fasiliti',
                'value' => function($model) {
                    return $model->fasiliti->nama_fasiliti ?? '-';
                },
                'filter' => ArrayHelper::map(
                    \app\models\Fasiliti::find()->where(['!=', 'id', 13])->all(),
                    'id',
                    'nama_fasiliti'
                ),
                'filterInputOptions' => [
                    'class' => 'form-select',
                    'prompt' => 'Pilih Fasiliti'
                ],
            ],

            [
                'label' => 'Status Fasiliti',
                'attribute' => 'fasiliti_status',
                'filterInputOptions' => [
                    'class' => 'form-select', 
                ],
                'value' => function($model) {
                    $status = ['Kosong', 'disimpan', 'Rosak', 'Sedang dibaiki', 'Diisi'];
                    return $status[$model->fasiliti_status] ?? 'Tidak diketahui';
                },

                'filter' => ['Kosong', 'disimpan', 'Rosak', 'Sedang dibaiki', 'Diisi'],
            ],
            'tarikh_mula',
            'tarikh_tamat',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, FasilitiStatusLog $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
