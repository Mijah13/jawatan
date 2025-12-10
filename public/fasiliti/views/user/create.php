<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User $model */

// $this->title = 'Tambah Pengguna';
// $this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <title><?= Html::encode($this->title ?? 'MyFasiliti') ?></title>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
