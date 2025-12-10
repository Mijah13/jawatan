<?php
namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User; 

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user This is the user model that is returned after validating the credentials.
 */
class LoginForm extends Model
{
    public $email;      
    public $password;

    private $_identity = false;  // Internal user storage for lazy loading
    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // email and password are both required
            [['email', 'password'], 'required', 'message' => 'Sila masukkan {attribute}.'],
            // password is validated by the validatePassword() method
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Custom validation method for the password.
     * This method checks if the given password is valid.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params additional parameters for the validation
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $identity = $this->getIdentity();
            $user = $this->getUser();

            // Check if the user exists
            if (!$identity) {
                $this->addError('email', 'E-mel tidak berdaftar. Sila daftar dahulu.');
                return;
            }

            // Validate the password
            if (!$identity->validatePassword($this->password)) {
                $this->addError($attribute, 'Kata laluan salah.');
            }


                // Check if the email is verified
            if ($user && $user->status !== 1) {
                // Set a flash message to show confirmation dialog in the frontend
                Yii::$app->session->setFlash('emailNotVerified', 'Sila sahkan e-mel anda sebelum log masuk.');
                // Add a custom error message
                $this->addError($attribute, 'Sila sahkan e-mel anda sebelum log masuk.');
            }
        }
    }

    /**
     * Logs in a user using the provided email and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getIdentity());
            // return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);

        // // Redirect based on user type
        // if ($this->getIdentity() instanceof Admin) {
        //     return Yii::$app->response->redirect(['admin/index']);
        // }

        // Default redirection for regular users
            return Yii::$app->response->redirect(['jenis-fasiliti/user-view']);
        }
        return false;
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null The user object associated with the provided email, or null if not found
     */
    public function getIdentity ()
    {
        if ($this->_identity  === false) {
            $this->_identity  = User::findByEmail($this->email);
        }

        return $this->_identity ;
    }
    /**
     * @return array custom attribute labels (name=>label)
     */

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->email);
        }

        return $this->_user;
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mel',
            'password' => 'Kata Laluan',
        ];
    }
}
