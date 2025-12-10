<?php

use yii\helpers\Html;
use yii\helpers\Json;

/** @var yii\web\View $this */
/** @var app\models\TempahAsrama $model */

// $this->title = '';
// $this->params['breadcrumbs'][] = ['label' => 'Tempah Asramas', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('@web/css/tempah.css');
?>
<div class="tempah-asrama-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <title><?= Html::encode($this->title ?? 'MyFasiliti') ?></title>

    <?= $this->render('_form', [
        'model' => $model,
        // 'id_asrama' => $id_asrama, 
        // 'events' => Json::encode($events), // Pass events for calendar
    ]) ?>

</div>
