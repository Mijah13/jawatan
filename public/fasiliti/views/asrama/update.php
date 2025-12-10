<?php

use yii\helpers\Html;
use app\controllers\StatusAsramaLog;

/** @var yii\web\View $this */
/** @var app\models\Asrama $model */

$this->title = 'MyFasiliti';
// $this->params['breadcrumbs'][] = ['label' => 'Asrama', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
// $this->params['breadcrumbs'][] = 'Update';
?>
<div class="asrama-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
