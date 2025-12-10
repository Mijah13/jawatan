<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Asrama $model */

$this->title = 'MyFasiliti';
// $this->params['breadcrumbs'][] = ['label' => 'Asrama', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="asrama-create">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
