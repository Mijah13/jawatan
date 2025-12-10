<?php

use app\models\AsramaStatusLog;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\AsramaStatusLogSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Asrama Status Logs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asrama-status-log-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Asrama Status Log', ['create'], ['class' => 'btn btn-success']) ?>
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
            'id_asrama',
            [
                'label' => 'Status Log',
                'attribute' => 'status_log',
                'filterInputOptions' => [
                    'class' => 'form-select', 
                ],
                'value' => function($model) {
                    $statusList = \app\models\Asrama::getStatusAsramaList();
                    return $statusList[$model->status_log] ?? 'Unknown';
                },
                'filter' => \app\models\Asrama::getStatusAsramaList(),
            ],
            
            
            
            
            'tarikh_mula',
            'tarikh_tamat',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, AsramaStatusLog $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
