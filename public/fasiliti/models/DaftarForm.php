<?php
namespace app\models;

use yii\base\Model;
use yii\captcha\Captcha;

class DaftarForm extends Model
{
    public $nama;
    public $email;
    public $kata_laluan;
    public $sah_kata_laluan;
    public $captcha;
    public $role;

    public function rules()
    {
        return [
            [['nama', 'email', 'kata_laluan', 'sah_kata_laluan'], 'required', 'message' => 'Sila masukkan {attribute}.'],
            ['email', 'email'],
            ['kata_laluan', 'string', 'min' => 6],
            ['sah_kata_laluan', 'compare', 'compareAttribute' => 'kata_laluan', 'message' => 'Kata laluan yang salah tidak sepadan.'],
            ['captcha', 'required', 'message' => 'Sila masukkan {attribute}.'],
            ['captcha', 'captcha', 'message' => 'Sila masukkan kod yang betul.'], // Sahkan captcha
              // ---------- baru ----------
            // 1) Jadikan role *conditionally* required
            ['role', 'required',
                'when'  => function ($model) {
                    // true = peranan WAJIB bila bukan kerajaan
                    return !preg_match('/@(ciast\.gov\.my|mohr\.gov\.my)$/i', $model->email);
                },
                // kita tak perlukan client-side check sbb JS dah urus
                'enableClientValidation' => false,
                'message' => 'Sila pilih peranan.'
            ],

            // 2) Pastikan cuma 3,4,5 sahaja valid
            ['role', 'in', 'range' => [3,4,5],
                'message' => 'Peranan tidak sah.'],
        ];
    }
}
