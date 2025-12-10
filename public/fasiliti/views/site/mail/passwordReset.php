<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User $user */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>

<p>Assalamualaikum & Salam Sejahtera <?= Html::encode($user->email) ?>,</p>

<p>Kami telah menerima permintaan untuk menetapkan semula kata laluan bagi akaun anda.  
Sila klik pautan di bawah untuk menetapkan kata laluan baru:</p>

<p><?= Html::a('Klik pautan ini untuk set semula kata laluan anda', $resetLink) ?></p>

<p>Jika anda tidak membuat permintaan ini, sila abaikan emel ini atau hubungi pasukan sokongan kami.</p>

<p>Terima kasih,</p>
<p><b>Unit Pentadbiran Sistem MyFasiliti</b></p>
