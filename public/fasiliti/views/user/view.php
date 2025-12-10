<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

// $this->title = $model->id;
// $this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <title><?= Html::encode($this->title ?? 'MyFasiliti') ?></title>

    <p>
        <?= Html::a('Senarai', ['index'],  ['class' => 'btn btn-secondary']) ?>
        <?= Html::a('Kemaskini', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Padam', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nama',
            'email:email',
            'password_hash',
            'authKey',
            // 'accessToken',
            [
                'attribute' => 'role',
                'value' => function ($model) {
                    $roles = [
                        0 => 'Admin Sistem',
                        1 => 'Admin Kemudahan',
                        2 => 'Pelulus',
                        3 => 'Pengguna',
                        4 => 'Pengguna Dalaman',
                        5 => 'Pelajar',
                        6 => 'Ketua Admin',
                        7 => 'admin kewangan',
                        8 => 'admin PEM',
                    ];
                    return $roles[$model->role] ?? 'Tidak Diketahui';
                },
            ],
           
        ],
    ]) ?>

</div>
