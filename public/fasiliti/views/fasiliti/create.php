<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Fasiliti $model */

$this->title = 'MyFasiliti';
// $this->params['breadcrumbs'][] = ['label' => 'Fasiliti', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="fasiliti-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
