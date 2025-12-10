<?php
namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

class PasswordReset extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist', 'targetClass' => User::class,  'targetAttribute' => 'email', 'message' => 'Tiada akaun dengan alamat e-mel ini.'],
        ];
    }

    public function sendEmail()
    {
        $user = User::findOne([
            'status' => 1,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }

        $user->generatePasswordResetToken();

        if (!$user->save()) {
            return false;
        }

        return Yii::$app
            ->mailer
            ->compose('passwordReset', ['user' => $user])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($this->email)
            ->setSubject('Set Semula Kata Laluan untuk ' . Yii::$app->name)
            ->send();
    }
}
