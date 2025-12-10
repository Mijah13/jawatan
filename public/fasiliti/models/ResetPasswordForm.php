<?php
namespace app\models;

use yii\base\Model;

class ResetPasswordForm extends Model
{
    public $password;
    private $_user;

    public function __construct($user, $config = [])
    {
        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function resetPassword()
    {
        $this->_user->setPassword($this->password);
        $this->_user->password_reset_token = null;
        return $this->_user->save(false);
    }
}
