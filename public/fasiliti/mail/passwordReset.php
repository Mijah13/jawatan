<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User $user */

// Create the reset link dynamically
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>

Hello <?= Html::encode($user->email) ?>,

We received a request to reset your password. Follow the link below to create a new password:

<?= Html::a(Html::encode($resetLink), $resetLink) ?>

If you did not request this, please ignore this email or contact support.

Thank you!
