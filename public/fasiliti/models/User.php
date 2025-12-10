<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $nama
 * @property string $email
 * @property string $password_hash
 * @property string|null $authKey
 * @property string|null $accessToken
 * @property int $role
 * @property int $is_verified
 * @property string|null $verification_token
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $kata_laluan;  // Plain password for form input
    public $sah_kata_laluan;  // Confirm password for form input

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const ROLE_ADMIN_SYSTEM = 0;
    const ROLE_ADMIN_KEMUDAHAN = 1;
    const ROLE_PELULUS = 2;
    const ROLE_PENGGUNA = 3;
    const ROLE_PENGGUNA_DALAMAN = 4; // @ciast/@mohr
    const ROLE_PELAJAR = 5; 
    const ROLE_KETUA_ADMIN = 6; 
    const ROLE_ADMIN_KEWANGAN = 7;
    const ROLE_ADMIN_PEM = 8;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama', 'email', 'role'], 'required'],  // Required fields
            [['kata_laluan', 'sah_kata_laluan'], 'required', 'on' => ['create']],  // Required fields for create action
            ['email', 'email'],  // Validate email format
            // Password validation: minimum length, must contain uppercase, lowercase, number, and special character
            ['kata_laluan', 'string', 'min' => 6],  // Minimum length
            ['kata_laluan', 'match', 'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{6,}$/', 
                'message' => 'Kata laluan mesti mengandungi sekurang-kurangnya satu huruf besar, satu huruf kecil, satu nombor, dan satu aksara khas.'],

            ['sah_kata_laluan', 'compare', 'compareAttribute' => 'kata_laluan', 'message' => 'Kata laluan tidak sepadan.'],
            // ['role', 'default', 'value' => 3],
            // ['role', 'integer'],

            [['authKey', 'password_hash', 'accessToken', 'verification_token', 'password_reset_token', 'role'], 'safe'],
            ['status', 'default', 'value' => 0],  // Default value for is_verified
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
            'email' => 'Emel',
            'password_hash' => 'Password Hash',
            'authKey' => 'Auth Key',
            'accessToken' => 'Access Token',
            'role' => 'Peranan',
            'status' => 'status',
        ];
    }


    /**
     * Save hashed password and security key when registering
     */
    public function register()
    {
        if ($this->validate()) {
            $this->setPassword($this->kata_laluan);  // Hash the password and store it in password_hash
            $this->generateAuthKey();  // Generate authKey
            $this->generateVerificationToken();  // Generate verification token
            
            if ($this->save()) {
                $this->sendVerificationEmail();  // Send verification email
                return true;
            }
        }

        return false;
    }

    /**
     * Hash and set password in the password_hash field
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    
    /**
     * Validate the given password
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates authentication key
     */
    public function generateAuthKey()
    {
        $this->authKey = Yii::$app->security->generateRandomString();
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert && empty($this->authKey)) {
                $this->generateAuthKey();  // Automatically generate authKey for new users
            }

           // --- Standardize huruf besar untuk teks tertentu ---
            $this->nama = strtoupper($this->nama);

            return true;
        }
        return false;
    }

    /**
     * Generates a verification token
     */
    public function generateVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString();
    }

    /**
     * Sends verification email
     */
    public function sendVerificationEmail()
    {
        $verificationLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $this->verification_token]);

        return Yii::$app->mailer->compose()
            ->setTo($this->email)
            ->setSubject('Sahkan Alamat E-mel Anda')
            ->setHtmlBody("Hello, {$this->nama}. Sila klik pada pautan di bawah untuk mengesahkan alamat e-mel anda:<br><a href='{$verificationLink}'>{$verificationLink}</a>")
            ->send();
    }

    /**
     * Marks the user as verified based on the token
     */
    public static function verifyEmail($token)
    {
        $user = self::findOne(['verification_token' => $token, 'status' => 0]);

        if ($user) {
            $user->status = 1;
            $user->verification_token = null;  // Clear the token after verification
            return $user->save(false);  // Save without validation
        }

        return false;  // Verification failed
    }

    /**
     * IdentityInterface methods
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['accessToken' => $token]);
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    public static function findByUsername($username)
    {
        return static::find()->where(['email' => $username])->one();
        
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
    
    
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
        $this->save();
    }


    public function validatePasswordResetToken($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    public static function isPasswordResetTokenValid($token)
{
    if (empty($token)) {
        return false;
    }

    $parts = explode('_', $token);
    $timestamp = (int)end($parts);
    $expire = Yii::$app->params['passwordResetTokenExpire'];
    return $timestamp + $expire >= time();
}

/**
 * Finds user by password reset token
 *
 * @param string $token
 * @return static|null
 */
public static function findByPasswordResetToken($token)
{
    if (!static::isPasswordResetTokenValid($token)) {
        return null;
    }

    return static::findOne(['password_reset_token' => $token, 'status' => self::STATUS_ACTIVE]);
}



}
