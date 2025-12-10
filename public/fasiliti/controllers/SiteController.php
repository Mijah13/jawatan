<?php namespace app\controllers;

use app\models\User;
use app\models\LoginForm;
use app\models\DaftarForm;
use app\models\JenisAsrama;
use Yii;
use yii\helpers\Html;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\controllers\Fasiliti;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\BadRequestHttpException;


class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'fasiliti/senarai-fasiliti', 'asrama/bilik', 'laporan/index'], // specify actions here that require login
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['logout', 'laporan/index', 'fasiliti/senarai-fasiliti', 'asrama/bilik'],
                        'roles' => ['@'], // only allow authenticated users
                        'matchCallback' => function ($rule, $action) {
                            return in_array(Yii::$app->user->identity->role, [0, 1, 2, 3, 4, 5, 6, 7, 8]);
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['login', 'daftar', 'index', 'fasiliti/user-view'],
                        'roles' => ['?', '@'], // allow both guests and authenticated users
                    ],
                   
                ],
                'denyCallback' => function ($rule, $action) {
                    if (Yii::$app->user->isGuest) {
                        // Guest → redirect ke login
                        return Yii::$app->response->redirect(['site/login']);
                    } else {
                        // Dah login tapi takde access → tunjuk 403 je
                        throw new \yii\web\ForbiddenHttpException('Anda tidak dibenarkan mengakses halaman ini.');
                    }
                },
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->redirect(['login']);
        return $this->render('index');
    }

    public function actionDaftar()
    {
        $model = new DaftarForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = new User();
            $user->nama = $model->nama;
            $user->email = $model->email;
            $user->setPassword($model->kata_laluan);  // Hash the plain password
            $user->generateAuthKey();
            $user->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
            $user->status = 0; // Unverified
                      
            if (preg_match('/@(ciast\.gov\.my|mohr\.gov\.my)$/', $model->email)) {
                $user->role = 4; // Internal user
            } else {
                // Hanya terima 3 atau 5 dari form, default fallback = 3
                $user->role = in_array($model->role, [3, 5]) ? $model->role : 3;
            }
                
            try {
                if ($user->save()) {
                    // Generate the verification link
                    $verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
                
                    // Email content
                    $body = "
                        <p>Assalamualaikum wbt dan Salam Sejahtera,</p>
                        <p><b>Tuan/Puan,</b></p>
                        <p><b>Akses Log Masuk Pengguna Baru</b><br>
                        Dengan segala hormatnya merujuk perkara di atas.</p>
                        
                        <p><b>Maklumat Pengguna Baru:</b></p>
                        <ul>
                            <li><b>Nama Pengguna:</b> {$user->nama}</li>
                            <li><b>Emel:</b> {$user->email}</li>
                        </ul>
                
                        <p>Pengguna perlu membuat pengesahan pengguna sistem melalui pautan di bawah:</p>
                        <p><a href='{$verifyLink}' target='_blank'><b>Klik di sini</b></a> untuk mengesahkan pengguna.</p>
                
                        <p><i>Email ini dijana dari Sistem Tempahan MyFasiliti CIAST.</i></p>
                    ";
                
                    // Send verification email
                    Yii::$app->mailer2->compose()
                        ->setFrom('amira.sistemfasiliti@ciast.edu.my')
                        ->setTo([$user->email])
                        ->setSubject('Makluman Akses Log Masuk MyFasiliti')
                        ->setHtmlBody($body)
                        ->send();

                    Yii::$app->session->setFlash('success', 'Pendaftaran anda telah diproses, sila semak email bagi pengesahan pengguna.');
                    return $this->redirect(['site/login']);
                }
            } catch (\yii\db\IntegrityException $e) {
                Yii::$app->session->setFlash('error', 'Akaun dengan email ini telah wujud. Sila semak emel (termasuk Spam/Junk) untuk pengesahan, atau guna email lain untuk pendaftaran baru.');
            }
        }

        return $this->render('daftar', [
            'model' => $model,
        ]);
    }

    public function actionVerifyEmail($token)
    {

        $user = User::findOne(['verification_token' => $token]);

        if (!$user) {
            // Cuba detect kalau user dah pernah verify based on pattern token (prefix sama)
            $prefix = explode('_', $token)[0];
            $existingUser = User::find()
                ->where(['status' => 1])
                ->andWhere(['like', 'verification_token', $prefix]) // just in case prefix still stored
                ->one();

            if (!$existingUser) {
                $existingUser = User::find()
                    ->where(['status' => 1])
                    ->andWhere(['verification_token' => null])
                    ->one(); // fallback kalau token dah null
            }

            if ($existingUser) {
                Yii::$app->session->setFlash('info', 'Akaun anda telah disahkan sebelum ini. Sila login seperti biasa.');
                return $this->redirect(['site/login']);
            }

            // Kalau memang sah-sah token tak pernah wujud
            Yii::$app->session->setFlash('error', 'Token pengesahan tidak sah atau telah tamat tempoh.');
            return $this->redirect(['site/daftar']);
        }
        // $user = User::findOne(['verification_token' => $token]);

        // if (!$user) {
        //     Yii::$app->session->setFlash('error', 'Token pengesahan tidak sah.');
        //     return $this->redirect(['site/daftar']);
        // }

        // Set the user's status to 1 (verified) and clear the verification token
        $user->verification_token = null;
        $user->status = 1; // Update user status to verified
        $user->save();

        // Log in the user automatically (optional but useful)
        Yii::$app->user->login($user);

        // ✳️ Redirect ikut role
        if ($user->role == 5) {
            return $this->redirect(['pelajar-asrama/create']);
        }

        Yii::$app->session->setFlash('success', 'Sila Login.');
        return $this->redirect(['site/login']);
    }

    public function actionLogin()
    {
    
        $model = new LoginForm();
        // return print_r(Yii::$app->request->post());
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // return print_r(Yii::$app->user->identity);
            $identity = $model->getIdentity();
            if($identity->role == 0){ // 0 is admin sistem
                return $this->redirect(['laporan/index']);
            }
            elseif ($identity->role == 1){ // 1 is admin kemudahan
                return $this->redirect(['laporan/index']);
            }
            elseif ($identity->role == 2){ // 2 is pelulus
                return $this->redirect(['laporan/index']);
            }
            elseif ($identity->role == 5){ // 5 is student
                return $this->redirect(['pelajar-asrama/create']);
            }
            elseif ($identity->role == 6){ // 6 is ketua admin
                return $this->redirect(['laporan/index']);
            }
             elseif ($identity->role == 7){ // 6 is ketua admin
                return $this->redirect(['laporan/index']);
            }
             elseif ($identity->role == 8){ // 6 is ketua admin
                return $this->redirect(['laporan/index']);
            }
            else { // 3 / 4 is pengguna (biasa)
                return $this->redirect(['fasiliti/senarai-fasiliti']);
            }
            
        }
        // return ($model->login());
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLupaKataLaluan()
    {
        $model = new \app\models\PasswordReset();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = \app\models\User::findOne(['email' => $model->email, 'status' => 1]);

            if (!$user) {
                Yii::$app->session->setFlash('error', 'Pengguna dengan emel ini tidak wujud atau tidak aktif.');
                return $this->refresh();
            }

            $user->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
            if ($user->save()) {
                $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);

                Yii::$app->mailer2->compose()
                    ->setFrom(Yii::$app->params['adminEmail'])
                    ->setTo($user->email)
                    ->setSubject('Tetapan Semula Kata Laluan')
                    ->setHtmlBody("
                        <p>Hai <strong>" . Html::encode($user->email) . "</strong>,</p>
                        <p>Kami menerima permintaan untuk menetapkan semula kata laluan anda.</p>
                        <p>Sila klik pautan di bawah untuk menetapkan semula kata laluan anda:</p>
                        <p><a href='" . Html::encode($resetLink) . "'>Klik pautan ini untuk menetapkan semula kata laluan</a></p>
                        <br>
                        <p>Jika anda tidak membuat permintaan ini, sila abaikan emel ini atau hubungi pentadbir sistem.</p>
                        <p>Terima kasih.</p>
                    ")
                    ->send();

                Yii::$app->session->setFlash('success', 'Sila semak emel anda untuk arahan set semula kata laluan.');
                return $this->redirect(['site/login']);
            }

            Yii::$app->session->setFlash('error', 'Ralat berlaku ketika menghantar arahan set semula.');
        }

        return $this->render('lupa-kata-laluan', ['model' => $model]);
    }

    
    
    // public function actionResetPassword($token)
    // {
    //     $user = User::findByPasswordResetToken($token);

    //     if (!$user || !$user->validatePasswordResetToken($token)) {
    //         throw new BadRequestHttpException('Invalid or expired token.');
    //     }

    //     $model = new \app\models\ResetPasswordForm($user);

    //     if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
    //         Yii::$app->session->setFlash('success', 'Kata laluan anda telah berjaya ditetapkan semula.');
    //         return $this->redirect(['site/login']);
    //     }

    //     return $this->render('reset-password', [
    //         'model' => $model,
    //     ]);
    // }

    public function actionResetPassword($token)
    {
        $user = User::findByPasswordResetToken($token);

        if (!$user) {
            throw new BadRequestHttpException('Invalid or expired token.');
        }

        $model = new \app\models\ResetPasswordForm($user);

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Kata laluan anda telah berjaya ditetapkan semula.');
            return $this->redirect(['site/login']);
        }

        return $this->render('reset-password', [
            'model' => $model,
        ]);
    }



    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['site/login']); 
    }
    
}
