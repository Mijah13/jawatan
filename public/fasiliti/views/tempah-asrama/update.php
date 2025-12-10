<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TempahAsrama $model */

// $this->title = ''; // Exclude the ID here
// $this->params['breadcrumbs'][] = ['label' => 'Tempah Asramas', 'url' => ['index']];
// $this->params['breadcrumbs'][] = 'Update';
?>
<div class="tempah-asrama-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <title><?= Html::encode($this->title ?? 'MyFasiliti') ?></title>

    <?= $this->render('_form', [
        'model' => $model,
        
    ]) ?>

</div>
