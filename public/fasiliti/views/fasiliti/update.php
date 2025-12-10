<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Fasiliti $model */

$this->title = 'MyFasiliti';
// $this->params['breadcrumbs'][] = ['label' => 'Fasiliti', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
// $this->params['breadcrumbs'][] = 'Update';
?>
<div class="fasiliti-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
