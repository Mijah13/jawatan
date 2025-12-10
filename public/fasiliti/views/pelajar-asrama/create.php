<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\PelajarAsrama $model */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => 'Pelajar Asramas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pelajar-asrama-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>


<?php if ($showModal): ?>
    <?php
    $tqUrl = Url::to(['pelajar-asrama/terima-kasih']);
    $this->registerJs("
        $(document).ready(function() {
            $('#modalTempahanBerjaya').modal('show');


            $('#confirmRedirect').on('click', function() {
                window.location.href = '$tqUrl';
            });
        });
    ");

    // $portalUrl = 'https://www.ciast.gov.my/?page_id=549&lang=en';

    // $this->registerJs("
    //     $(document).ready(function() {
    //         $('#modalTempahanBerjaya').modal('show');

    //         $('#confirmRedirect').on('click', function() {
    //             window.location.href = '$portalUrl';
    //         });
    //     });
    // ");

    ?>
    <?php endif; ?>

</div>
