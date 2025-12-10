<?php

use yii\helpers\Html;
use yii\helpers\Json;

/** @var yii\web\View $this */
/** @var app\models\TempahFasiliti $model */

// $this->title = '';
// $this->params['breadcrumbs'][] = ['label' => 'Tempah Fasilitis', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="tempah-fasiliti-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <title><?= Html::encode($this->title ?? 'MyFasiliti') ?></title>

    <?= $this->render('_form', [
        'model' => $model,
        'events' => Json::encode($events), // Pass events for calendar
        'fasiliti_id' => $fasiliti_id,
    ]) ?>

</div>
