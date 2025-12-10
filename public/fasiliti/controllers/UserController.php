<?php

namespace app\controllers;

use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;


/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    // 'only' => ['logout', 'jenis-fasiliti', 'asrama-rooms', 'booking'],
                    'rules' => [
                        [
                            'allow' => true,
                            // 'actions' => ['logout', 'jenis-fasiliti', 'asrama-rooms', 'booking'],
                            'roles' => ['@'], // only allow authenticated users
                        ],
                        // [
                        //     'allow' => true,
                        //     'actions' => ['login', 'daftar', 'index'],
                        //     'roles' => ['?'], // allow guests to access login, register, and home
                        // ],
                    ],
                    'denyCallback' => function () {
                        return Yii::$app->response->redirect(['site/login']);
                    },
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],

            ]
        );
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new User();
        $model->scenario = 'create';
        // $model->role = 3; // Set default role first


        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $password = $this->request->post('User')['kata_laluan'];
                $model->password_hash = \Yii::$app->security->generatePasswordHash($password);
                $model->verification_token = Yii::$app->security->generateRandomString() . '_' . time();

            
                if (preg_match('/@(ciast\.gov\.my|mohr\.gov\.my)$/', $model->email)) {
                    $model->role = 4;
                } else {
                    $model->role = 3;
                }
                            
                if ($model->save()) {
                    // Generate the verification link
                    $verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $model->verification_token]);
    
                    // Email content
                    $body = "
                        <p>Assalamualaikum wbt dan Salam Sejahtera,</p>
                        <p><b>Tuan/Puan,</b></p>
                        <p><b>Akses Log Masuk Pengguna Baru</b><br>
                        Dengan segala hormatnya merujuk perkara di atas.</p>
                        
                        <p><b>Maklumat Pengguna Baru:</b></p>
                        <ul>
                            <li><b>Nama Pengguna:</b> {$model->nama}</li>
                            <li><b>Emel:</b> {$model->email}</li>
                        </ul>
                
                        <p>Pengguna sudah boleh menggunakan sistem dengan log masuk melalui pautan di bawah:</p>
                        <p><a href='{$verifyLink}' target='_blank'><b>Klik di sini</b></a> untuk ke laman eFasiliti.</p>
                
                        <p><i>Email ini dijana dari Sistem Tempahan Fasiliti CIAST.</i></p>
                    ";
                    
                    // Send verification email with the token
                    $mailer = Yii::$app->mailer2->compose()
                        ->setFrom('amira.sistemfasiliti@ciast.edu.my')
                        ->setTo([$model->email])
                        ->setSubject('Makluman Akses Log Masuk eFasiliti')
                        ->setHtmlBody($body)
                        ->send();
    
                    Yii::$app->session->setFlash('success', 'Pendaftaran berjaya! Sila sahkan e-mel anda.');
                    return $this->redirect(['site/login']);
                } else {
                    Yii::$app->session->setFlash('error', 'Tidak dapat menyimpan pengguna. Sila cuba lagi.');
                    Yii::error('Save failed: ' . json_encode($model->errors));
                    return print_r($model->errors);
                }
            }
        } else {
            $model->loadDefaultValues();
        }
    

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $password = $this->request->post('User')['kata_laluan'];
            if(!empty($password))
                $model->password_hash = \Yii::$app->security->generatePasswordHash($password);
            // return $model->password_hash;
            // return print_r($this->request->post());
            if(!$model->save())
                return print_r($model->errors);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
