<?php

use app\models\Asrama;
use app\models\JenisAsrama;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\AsramaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'MyFasiliti';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asrama-user-view">

    <h1 class="text-center">Senarai Bilik Asrama</h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-hover table-bordered'],
        'summary' => false, // Disable the "Showing X-Y of Z items" text
        'options' => ['class' => 'table-responsive'], // Make the table scrollable
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'], // Serial numbers
            
            [
                'label' => 'Blok',
                'attribute' => 'blok',
                'filterInputOptions' => [
                    'class' => 'form-select', 
                ],
                'filter' => ArrayHelper::map(Asrama::find()->select(['blok'])
                ->distinct()
                ->all(), 'blok', 'blok'),
            ],
            [
                'label' => 'Aras',
                'attribute' => 'aras',
                'filterInputOptions' => [
                    'class' => 'form-select', 
                ],
                'filter' => ArrayHelper::map(Asrama::find()->select(['aras'])
                ->distinct()
                ->all(), 'aras', 'aras'),
            ],
            'no_asrama',
            // [
            //     'label' => 'Status Asrama',
            //     'attribute' => 'status_asrama',
            //     'filterInputOptions' => [
            //         'class' => 'form-select', 
            //     ],
            //     'value' => function($model) {
            //         $status = ['Kosong', 'Sedang dibersihkan', 'Simpanan', 'Rosak', 'Risiko', 'Sedang dibaiki'];
            //         return $status[$model->status_asrama];
            //     },
            //     'filter' => ['Kosong','Sedang dibersihkan', 'Simpanan', 'Rosak', 'Risiko', 'Sedang dibaiki'],
            // ],
            [
                'label' => 'Kategori Bilik',
                'attribute' => 'jenis_bilik',
                'filterInputOptions' => [
                    'class' => 'form-select', 
                ],
                'value' => function($model) {
                    $jenisBilikMapping = ArrayHelper::map(
                        \app\models\JenisAsrama::find()->all(),
                        'id', // Primary key in JenisAsrama table
                        'jenis_bilik' // Room type name
                    );

                    return $jenisBilikMapping[$model->jenis_bilik] ?? 'Unknown';
                },
                'filter' => ArrayHelper::map(
                    \app\models\JenisAsrama::find()->all(),
                    'id',
                    'jenis_bilik'
                ),
            ],
            [
                'label' => 'Bilik',
                'attribute' => 'kelamin',
                'filterInputOptions' => [
                    'class' => 'form-select', 
                ],
                'value' => function($model) {
                    $kelamin = ['Lelaki', 'Perempuan', 'Lelaki/Perempuan'];
                    return $kelamin[$model->kelamin];
                },
                'filter' => ['Lelaki', 'Perempuan', 'Lelaki/Perempuan'], 
            ],
            [
                'label' => 'Tempah',
                'format' => 'raw',
                'value' => function($model) {
                    // $status = ['Kosong', 'Sedang dibersihkan', 'Simpanan', 'Rosak', 'Risiko', 'Sedang dibaiki'];
                    
                    // // Get the current room status
                    // $roomStatus = $status[$model->status_asrama] ?? 'Unknown';
                    
                    // // Resolve the Jenis Bilik name
                    // $jenisBilik = \app\models\JenisAsrama::findOne($model->jenis_bilik);
                    // $jenisBilik = $jenisBilik ? $jenisBilik->jenis_bilik : 'Unknown';

                    // Get the 'type' query parameter from the URL
                    $type = Yii::$app->request->get('type');

                    // Conditionally set the redirection URL based on the 'type' query parameter
                    $redirectUrl = ($type == 'pelajar') 
                        ? '/tempah-asrama-pelajar/create' 
                        : '/tempah-asrama/create';

            
                        return Html::a('Tempah', [
                            $redirectUrl, // Use the conditional URL
                            'room_id' => $model->id,
                            'blok' => $model->blok,
                            'aras' => $model->aras,
                            'no_asrama' => $model->no_asrama,
                            'jenis_bilik' => $model->jenis_bilik,
                        ], [
                            'class' => 'btn btn-success btn-sm btn-room', // Green button for available rooms with additional class for size consistency
                            'title' => 'Tempah bilik ini'
                    
                        ]);
                    }
            ],
        ],
    ]); ?>

</div>
<?php
$this->registerCss('

     h1 {
        font-size: 2.5rem; /* Adjust size */
        font-weight: bold; /* Make it bold */
        color: #ffffff; /* Text color */
        background: linear-gradient(90deg, #4CAF50, #2196F3); /* Gradient background */
        -webkit-background-clip: text; /* Clip background to text */
        -webkit-text-fill-color: transparent; /* Make the rest transparent */
        text-align: center; /* Center align the title */
        margin-bottom: 20px; /* Add space below */
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3); /* Add shadow for a 3D effect */
        letter-spacing: 1px; /* Slightly increase letter spacing */
    }

    .btn-room {
    width: 150px; /* Set a fixed width */
    height: 40px; /* Set a fixed height */
    text-align: center; /* Center the text inside */
    display: inline-flex; /* Flexbox for perfect alignment */
    align-items: center; /* Center vertically */
    justify-content: center; /* Center horizontally */
    padding: 0; /* Remove padding to maintain size consistency */
    font-size: 14px; /* Set a consistent font size */
}

    .btn-circle {
        // background-color: rgb(72, 99, 180) !important;
        width: 30px !important;
        height: 30px !important;
        border-radius: 50% !important;
        text-align: center !important;
        
        display: inline-flex; /* Flexbox for perfect alignment */
        align-items: center; /* Center items vertically */
        justify-content: center; /* Center items horizontally */
        transition: all 0.3s ease; /* Smooth hover effect */
    }
    .btn-circle:hover {
        background-color:rgb(23, 42, 98); /* Hover color */
        color: #ffffff; /* Change icon color on hover */
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); /* Add a shadow on hover */
    }

    .table-responsive {
        border-radius: 10px; /* Rounded corners */
        overflow: hidden; /* Prevent overflow */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    }

    table {
        border-collapse: collapse; /* Change to collapse to remove extra spacing */
        width: 100%;
    }

    .table thead th {
        background-color:rgb(82, 139, 185); /* Replace with your desired color */
        color: white; /* Text color */
        text-decoration: none; /* Remove underline */
        text-align: center;
    }

    .table thead th a {
        color: white; /* Make header links white */
        text-decoration: none; /* Remove underline */
    }

    .table thead th a:hover {
        color:rgb(0, 0, 0); /* Optional: Add hover effect */
    }

    .table tbody tr:nth-child(odd) {
        background-color: #f9f9f9; /* Light background for striped rows */
    }

    .table tbody tr:nth-child(even) {
        background-color: #ffffff; /* White background */
    }

    .table tbody tr:hover {
        background-color: #e9ecef; /* Highlight row on hover */
        cursor: pointer;
    }

    .table tbody td {
        padding: 10px;
        text-align: center;
        vertical-align: middle;
        border-bottom: 1px solid #dee2e6; /* Light border between rows */
    }

    .table tfoot {
        font-weight: bold;
        background-color: #f1f3f5; /* Footer background */
        color: #333; /* Footer text color */
    }
    
    .table tfoot td {
    padding: 10px; /* Add padding directly to footer cells */
    text-align: right; /* Align footer content (e.g., total) to the right */
    border-top: 1px solid #dee2e6; /* Add a border to separate footer from body */
    }

    /* Remove any unintended margin or padding */
    .table, .table-container {
        margin: 0;
        padding: 0;
    }
');
?>
