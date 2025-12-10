<?php

namespace app\controllers;
use app\models\User;

use app\models\TempahFasiliti;
use app\models\TempahFasilitiSearch;
use app\models\Fasiliti;
use yii\web\Controller;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\base\ErrorException;
use DateTime;
use Yii;

/**
 * TempahFasilitiController implements the CRUD actions for TempahFasiliti model.
 */
class TempahFasilitiController extends Controller
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
                    'only' => ['index', 'create', 'update', 'delete', 'view','get-events', 'print', 'pelulus', 'menunggu-bayaran'], 
                    'rules' => [
                        // Rule for admin roles (0 and 1) - allow access to all actions
                        [
                            'allow' => true,
                            'actions' => ['index', 'create', 'update', 'delete', 'view', 'get-events'],
                            'roles' => ['@'], // Authenticated users
                            'matchCallback' => function ($rule, $action) {
                                return in_array(Yii::$app->user->identity->role, [0, 1, 6, 8]);
                            },
                        ],
                        // Rule for pelulus 
                        [
                            'allow' => true,
                            'actions' => ['index', 'create', 'update', 'delete', 'view', 'get-events', 'send-email', 'print', 'pelulus'],
                            'roles' => ['@'], // Authenticated users
                            'matchCallback' => function ($rule, $action) {
                                return Yii::$app->user->identity->role == 2;
                            },
                        ],
                        // Rule for role 3 (User) - allow access to create, view, update, and cancel bookings
                        [
                            'allow' => true,
                            'actions' => ['create', 'view', 'update','get-events', 'view','delete', 'send-email', 'print'],
                            'roles' => ['@'], // Authenticated users only
                            'matchCallback' => function ($rule, $action) {
                                return in_array(Yii::$app->user->identity->role, [3, 4, 5]);
                            },
                        ],
                        [
                            'allow' => true,
                            'actions' => ['menunggu-bayaran'],
                            'roles' => ['@'], // Authenticated users only
                            'matchCallback' => function ($rule, $action) {
                                return in_array(Yii::$app->user->identity->role, [0, 1, 6, 7]); //adminsss
                            },
                        ],
                        // Deny all actions for guests (non-authenticated users)
                        [
                            'allow' => false,
                            'roles' => ['?'], // Deny access for guests
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
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            $this->redirect(['site/login']);
            return false; // hentikan action sekarang
        }
        return parent::beforeAction($action);
    }


    /**
     * Lists all TempahFasiliti models.
     *
     * @return string
     */
    // public function actionIndex()
    // {
    //     // Panggil function untuk tukar status fasiliti yang dah tamat tempoh simpanan
    //     TempahFasiliti::releaseExpiredReservations();
    //     $searchModel = new TempahFasilitiSearch();
    //     $dataProvider = new ActiveDataProvider([
    //         'query' => TempahFasiliti::find()
    //         ->where(['IN', 'status_tempahan_adminKemudahan', [1, 2, 3]])
    //         ->andWhere([
    //             'OR',
    //             ['status_tempahan_adminKemudahan' => 1], // Kalau status masih 1, tetap muncul
    //             ['diluluskan_oleh' => null] // Kalau tiada pelulus, tetap muncul
    //         ])
    //     ]);

    //     return $this->render('index', [
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }

    public function actionIndex()
    {
        TempahFasiliti::releaseExpiredReservations();

        $searchModel = new TempahFasilitiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $query = $dataProvider->query;

        // Role sekarang
       $role = Yii::$app->user->identity->role ?? null;

        if ($role == 0) {
            // Admin Sistem → semua condition asal + extra kuasa pelulus kosong
            $query->andWhere([
                'OR',
                // condition asal
                [
                    'AND',
                    ['IN', 'status_tempahan_adminKemudahan', [1, 2, 3]],
                    [
                        'OR',
                        ['status_tempahan_adminKemudahan' => 1],
                        ['diluluskan_oleh' => null]
                    ]
                ],
                // condition extra untuk rekod ada pelulus tapi belum decide
                [
                    'AND',
                    ['IS NOT', 'diluluskan_oleh', null],
                    ['status_tempahan_pelulus' => null],
                ]
            ]);
        } else {
            // Role lain ikut condition asal je
            $query->andWhere(['IN', 'status_tempahan_adminKemudahan', [1, 2, 3]])
                ->andWhere([
                    'OR',
                    ['status_tempahan_adminKemudahan' => 1],
                    ['diluluskan_oleh' => null]
                ]);
        }


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Displays a single TempahFasiliti model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        // Fetch the Tempah record by ID
        $model = TempahFasiliti::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Tempahan tidak ditemui.');
        }

        // $Fasiliti = Fasiliti::find()->all();
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            // 'Fasiliti' => $Fasiliti,
        ]);
    }

    /**
     * Creates a new TempahFasiliti model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($fasiliti_id)
    {
        $model = new TempahFasiliti();
        $model->status_tempahan_adminKemudahan = 0; //pending
    
        // Set the fasiliti_id if provided
        if ($fasiliti_id) {
            $model->fasiliti_id = $fasiliti_id;
        }
    
    
        // Handle form submission
        if ($model->load(Yii::$app->request->post())) {
            // Set the logged-in user's ID and email
            $model->user_id = Yii::$app->user->id;
            $model->email = Yii::$app->user->identity->email;
            $model->is_simpanan = Yii::$app->request->post('TempahFasiliti')['is_simpanan'] ?? 0;

            // $selectedPeralatan = Yii::$app->request->post('peralatan');
            // $model->peralatan = $selectedPeralatan ? implode(',', $selectedPeralatan) : null;
    
            // Handle file upload
            $file = UploadedFile::getInstance($model, 'surat_sokongan');
            if ($file) {
                $fileName = uniqid() . '.' . $file->extension;
                $file->saveAs('uploads/' . $fileName);
                $model->surat_sokongan = $fileName;
            }
    
            // Format date fields
            $tarikh_masuk = Yii::$app->request->post('TempahFasiliti')['tarikh_masuk'];
            $tarikh_keluar = Yii::$app->request->post('TempahFasiliti')['tarikh_keluar'];
            $model->tarikh_masuk = Yii::$app->formatter->asDate($tarikh_masuk, 'php:Y-m-d');
            $model->tarikh_keluar = Yii::$app->formatter->asDate($tarikh_keluar, 'php:Y-m-d');
    
            // Save the model after validation
            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Tempahan anda telah berjaya disimpan.');
                    return $this->redirect(['senarai-tempahan']);
                } else {
                    Yii::$app->session->setFlash('error', 'Ralat berlaku semasa menyimpan tempahan anda. Sila cuba lagi.');
                }
            } else {
                // Collect error messages
                $errorMessages = '';
                foreach ($model->errors as $attribute => $errors) {
                    $errorMessages .= Html::encode(implode(', ', $errors)) . "<br>";
                }
                Yii::$app->session->setFlash('error', "Ralat pengesahan:<br>" . $errorMessages);
            }
        }
    
        // Fetch existing bookings for the calendar
        $bookings = TempahFasiliti::find()
            ->where(['fasiliti_id' => $fasiliti_id])
            ->all();
    
        // Format bookings for the calendar
        $events = [];
        foreach ($bookings as $booking) {
            $events[] = [
                'id' => $booking->id,
                'tujuan' => $booking->tujuan,
                'tarikh_masuk' => $booking->tarikh_masuk,
                'tarikh_keluar' => $booking->tarikh_keluar,
            ];
        }
    
        return $this->render('create', [
            'model' => $model,
            'events' => Json::encode($events), // Pass events for calendar
            'fasiliti_id' => $fasiliti_id,
        ]);
    }
   
    public function actionSenaraiTempahan()
    {
        $searchModel = new TempahFasilitiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Filter ikut user login
        $dataProvider->query->andWhere(['user_id' => Yii::$app->user->identity->id]);

        $hasBookings = $dataProvider->query->exists();

        return $this->render('senarai-tempahan', [
            'dataProvider' => $dataProvider,
            'hasBookings' => $hasBookings,
            'searchModel' => $searchModel, // optional, kalau kau nak guna form carian nanti
        ]);
    }

       

    /**
     * Updates an existing TempahFasiliti model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            // Format the tarikh_masuk and tarikh_keluar fields
            $tarikh_masuk = Yii::$app->request->post('TempahFasiliti')['tarikh_masuk'];
            $tarikh_keluar = Yii::$app->request->post('TempahFasiliti')['tarikh_keluar'];
            
            $model->tarikh_masuk = DateTime::createFromFormat('d-m-Y', $tarikh_masuk)?->format('Y-m-d');
            $model->tarikh_keluar = DateTime::createFromFormat('d-m-Y', $tarikh_keluar)?->format('Y-m-d');
            // $model->tarikh_masuk = Yii::$app->formatter->asDate($tarikh_masuk, 'php:Y-m-d');
            // $model->tarikh_keluar = Yii::$app->formatter->asDate($tarikh_keluar, 'php:Y-m-d');
    
            // Save the model and handle success or error
            if ($model->save()) {
                // Yii::$app->session->setFlash('success', 'Tempahan telah berjaya dikemaskini.');
                return $this->redirect(['senarai-tempahan']);
            } else {
                Yii::$app->session->setFlash('error', 'Ralat berlaku semasa mengemaskini tempahan. Sila cuba lagi.');
            }
        }
    
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TempahFasiliti model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();
    //     return 'ok';

    //     // return $this->redirect(['senarai-tempahan']); 
    // }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            $model->delete();
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['success' => true];
    }


    /**
     * Finds the TempahFasiliti model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return TempahFasiliti the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TempahFasiliti::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetEvents($start, $end, $fasiliti_id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        try {
            $bookings = TempahFasiliti::find()
                ->where(['fasiliti_id' => $fasiliti_id])
                ->andWhere([
                    'and',
                    ['fasiliti_id' => $fasiliti_id],
                    ['<=', 'tarikh_masuk', $end],
                    ['>=', 'tarikh_keluar', $start],
                ])
                ->andWhere(['!=', 'status_tempahan_adminKemudahan', 4]) // exclude status 4
                ->all();

            $events = [];
            foreach ($bookings as $booking) {
                // Convert dates for display
                $tarikh_masuk = Yii::$app->formatter->asDate($booking->tarikh_masuk, 'php:d-m-Y');
                $tarikh_keluar = Yii::$app->formatter->asDate($booking->tarikh_keluar, 'php:d-m-Y');
                
                // Paparkan sesi/slot masa
                $sesiLabel = '';
                switch ($booking->tempoh) {
                    case 1: $sesiLabel = 'Sesi Pagi'; break;
                    case 2: $sesiLabel = 'Sesi Petang'; break;
                    case 3: $sesiLabel = 'Sesi Malam'; break;
                    case 4: $sesiLabel = 'Sesi Pagi - Petang'; break;
                    case 5: $sesiLabel = 'Satu Hari'; break;
                    default: $sesiLabel = 'Tidak Diketahui';
                }
            
                $events[] = [
                    'id' => $booking->id,
                    'title' => $booking->tujuan . "\n" . $tarikh_masuk . ' - ' . $tarikh_keluar,
                    'start' => Yii::$app->formatter->asDate($booking->tarikh_masuk, 'php:Y-m-d'),
                    'end' => date('Y-m-d', strtotime(Yii::$app->formatter->asDate($booking->tarikh_keluar, 'php:Y-m-d') . ' +1 day')),
                    'color' => '#28a745',
                    'extendedProps' => [
                        'tempoh' => (int) $booking->tempoh,
                    ],
                ];

            }

            return $events;
        } catch (\Exception $e) {
            \Yii::error('Calendar error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    //check date/session availability
    

    //pengesahan dari user > admin
    public function actionSendEmail()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
    
        if (empty($id)) {
            return [
                'success' => false,
                'message' => 'ID tempahan tidak diberikan.',
            ];
        }
    
        $bookings = TempahFasiliti::findOne($id);
    
        if (!$bookings || $bookings->status_tempahan_adminKemudahan != 0) {
            return [
                'success' => false,
                'message' => 'Tempahan tidak sah atau telah diproses.',
            ];
        }
    
        $bookings->status_tempahan_adminKemudahan = 1;
        if (!$bookings->save(false)) {
            Yii::error('Failed to update booking status: ' . print_r($bookings->errors, true));
            return [
                'success' => false,
                'message' => 'Tempahan tidak sah atau telah diproses.',
            ];
        }
        
        // Pilih admin penerima ikut fasiliti_id
        if ($bookings->fasiliti_id == 20) {
            // Fasiliti khas, hantar ke role 8 sahaja
            $adminEmails = User::find()
                ->select('email')
                ->where(['role' => 8])
                ->column();
        } else {
            // Fasiliti lain, hantar ke role 1 & 6
            $adminEmails = User::find()
                ->select('email')
                ->where(['role' => [1, 6]])
                ->column();
        }


        if (empty($adminEmails)) {
            return [
                'success' => false,
                'message' => 'Tiada admin kemudahan dijumpai.',
            ];
        }

        $namaPemohon = $bookings->user->nama ?? 'Tidak diketahui';
    
        // **Email untuk Admin**
        $emailContentAdmin = "
        <div style='font-family: Arial, sans-serif; color: #333; font-size: 14px; line-height: 1.6;'>
            <p>Assalamualaikum dan Salam Sejahtera,</p>

            <p><strong>Tuan/Puan,</strong></p>

            <p>Sukacita dimaklumkan terdapat tempahan fasiliti baharu melalui sistem <strong>MyFasiliti</strong> yang memerlukan tindakan pengesahan pihak tuan/puan. Maklumat tempahan adalah seperti berikut:</p>

            <table style='border-collapse: collapse; margin-top: 10px;'>
                <tr>
                    <td style='padding: 4px 8px; font-weight: bold;'>ID Tempahan</td>
                    <td style='padding: 4px 8px;'>:</td>
                    <td style='padding: 4px 8px;'>{$bookings->id}</td>
                </tr>
                <tr>
                    <td style='padding: 4px 8px; font-weight: bold;'>Nama Pemohon</td>
                    <td style='padding: 4px 8px;'>:</td>
                    <td style='padding: 4px 8px;'>{$namaPemohon}</td>
                </tr>
                <tr>
                    <td style='padding: 4px 8px; font-weight: bold;'>Jenis Fasiliti</td>
                    <td style='padding: 4px 8px;'>:</td>
                    <td style='padding: 4px 8px;'>{$bookings->fasiliti->nama_fasiliti}</td>
                </tr>
                <tr>
                    <td style='padding: 4px 8px; font-weight: bold;'>Tarikh Masuk</td>
                    <td style='padding: 4px 8px;'>:</td>
                    <td style='padding: 4px 8px;'>" . Yii::$app->formatter->asDate($bookings->tarikh_masuk, 'php:j/n/Y') . "</td>
                </tr>
                <tr>
                    <td style='padding: 4px 8px; font-weight: bold;'>Tarikh Keluar</td>
                    <td style='padding: 4px 8px;'>:</td>
                    <td style='padding: 4px 8px;'>" . Yii::$app->formatter->asDate($bookings->tarikh_keluar, 'php:j/n/Y') . "</td>
                </tr>
                <tr>
                    <td style='padding: 4px 8px; font-weight: bold;'>Sesi</td>
                    <td style='padding: 4px 8px;'>:</td>
                    <td style='padding: 4px 8px;'>";
                    switch ($bookings->tempoh) {
                        case 1:
                            $emailContentAdmin .= "Sesi Pagi : 9am - 12pm";
                            break;
                        case 2:
                            $emailContentAdmin .= "Sesi Petang : 2pm - 5pm";
                            break;
                        case 3:
                            $emailContentAdmin .= "Sesi Malam : 8pm - 11pm";
                            break;
                        case 4:
                            $emailContentAdmin .= "Sesi Pagi - Petang : 9am - 5pm";
                            break;
                        case 5:
                            $emailContentAdmin .= "Satu Hari";
                            break;
                        default:
                            $emailContentAdmin .= "Sesi tidak diketahui";
                            break;
                    }
                    $emailContentAdmin .= "</td>
                </tr>
            </table>

            <p>Kerjasama dan perhatian pihak tuan/puan amat dihargai. Sekian, terima kasih.</p>

            <p>Yang menjalankan tugas,<br>
            <em>Sistem Tempahan MyFasiliti</em></p>
        </div>
    ";

        
        // **Hantar Email ke Admin**
        try {
            $successAdmin = Yii::$app->mailer2->compose()
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                ->setTo($adminEmails) // Hantar ke semua admin
                ->setSubject('Pengesahan Tempahan Fasiliti')
                ->setHtmlBody($emailContentAdmin)
                ->send();
        } catch (\Exception $e) {
            Yii::error('Email error (Admin): ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terdapat ralat semasa menghantar emel kepada admin. ' . $e->getMessage(),
            ];
        }

        // **Ambil email user yang buat tempahan**
        $userEmail = $bookings->user->email ?? null; // Pastikan hubungan user ada

        if (!$userEmail) {
            Yii::error('Email pengguna tidak wujud untuk tempahan ID: ' . $bookings->id);
            return [
                'success' => false,
                'message' => 'Terdapat ralat: Email pengguna tidak ditemui.',
            ];
        }

        // **Email untuk User**
        $emailContentUser = "
        <div style='font-family: Arial, sans-serif; color: #333; font-size: 14px; line-height: 1.6;'>
        <p>Assalamualaikum dan Salam Sejahtera,</p>

        <p>Tempahan anda telah dihantar dan sedang diproses oleh pihak pentadbiran melalui sistem <strong>MyFasiliti</strong>. Maklumat tempahan adalah seperti berikut:</p>

        <table style='border-collapse: collapse; margin-top: 10px;'>
            <tr>
                <td style='padding: 4px 8px; font-weight: bold;'>ID Tempahan</td>
                <td style='padding: 4px 8px;'>:</td>
                <td style='padding: 4px 8px;'>{$bookings->id}</td>
            </tr>
            <tr>
                <td style='padding: 4px 8px; font-weight: bold;'>Nama Pemohon</td>
                <td style='padding: 4px 8px;'>:</td>
                <td style='padding: 4px 8px;'>{$namaPemohon}</td>
            </tr>
            <tr>
                <td style='padding: 4px 8px; font-weight: bold;'>Jenis Fasiliti</td>
                <td style='padding: 4px 8px;'>:</td>
                <td style='padding: 4px 8px;'>{$bookings->fasiliti->nama_fasiliti}</td>
            </tr>
            <tr>
                <td style='padding: 4px 8px; font-weight: bold;'>Tarikh Masuk</td>
                <td style='padding: 4px 8px;'>:</td>
                <td style='padding: 4px 8px;'>" . Yii::$app->formatter->asDate($bookings->tarikh_masuk, 'php:j/n/Y') . "</td>
            </tr>
            <tr>
                <td style='padding: 4px 8px; font-weight: bold;'>Tarikh Keluar</td>
                <td style='padding: 4px 8px;'>:</td>
                <td style='padding: 4px 8px;'>" . Yii::$app->formatter->asDate($bookings->tarikh_keluar, 'php:j/n/Y') . "</td>
            </tr>
            <tr>
                <td style='padding: 4px 8px; font-weight: bold;'>Sesi</td>
                <td style='padding: 4px 8px;'>:</td>
                <td style='padding: 4px 8px;'>";
                    switch ($bookings->tempoh) {
                        case 1:
                            $emailContentUser .= "Sesi Pagi : 9am - 12pm";
                            break;
                        case 2:
                            $emailContentUser .= "Sesi Petang : 2pm - 5pm";
                            break;
                        case 3:
                            $emailContentUser .= "Sesi Malam : 8pm - 11pm";
                            break;
                        case 4:
                            $emailContentUser .= "Sesi Pagi - Petang : 9am - 5pm";
                            break;
                        case 5:
                            $emailContentUser .= "Satu Hari";
                            break;
                        default:
                            $emailContentUser .= "Sesi tidak diketahui";
                            break;
                    }
                    $emailContentUser .= "</td>
                </tr>
            </table>

        <p>Anda akan menerima kemas kini status setelah tempahan disahkan oleh pihak pentadbiran.</p>

        <p>Terima kasih atas kerjasama anda.</p>

        <p><em>Sistem Tempahan MyFasiliti</em></p>
    </div>
";

       
        // **Hantar Email ke User**
        try {
            $successUser = Yii::$app->mailer2->compose()
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                ->setTo($userEmail)
                ->setSubject('Tempahan Sedang Diproses')
                ->setHtmlBody($emailContentUser)
                ->send();
        } catch (\Exception $e) {
            Yii::error('Email error (User): ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terdapat ralat semasa menghantar emel kepada pengguna. ' . $e->getMessage(),
            ];
        }

        // **Final Response**
        if ($successAdmin && $successUser) {
            return [
                'success' => true,
                'message' => 'Tempahan anda telah berjaya dihantar.',
            ];
        } else {
            Yii::error('Email failed to send for either admin or user.');
            return [
                'success' => false,
                'message' => 'Terdapat ralat semasa menghantar emel. Sila cuba lagi.',
            ];
        }
    }
    
    //action admin kemudahan
    public function actionChangeStatus($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; 

        $booking = TempahFasiliti::findOne($id);
    
        if (!$booking) {
            return ['success' => false, 'message' => 'Tempahan tidak dijumpai.'];
        }

        $adminEmail = Yii::$app->user->identity->email ?? Yii::$app->params['adminEmails'];
    
        if (Yii::$app->request->isPost) {
            // Retrieve posted values
            $newStatusTempahan = Yii::$app->request->post('status_tempahan_adminKemudahan');
            $newStatusPembayaran = Yii::$app->request->post('status_pembayaran');
            $alasan = Yii::$app->request->post('alasan_batal');

            $booking->disokong_oleh = Yii::$app->user->id; 
            $booking->status_tempahan_adminKemudahan = $newStatusTempahan;
            $booking->status_pembayaran = $newStatusPembayaran; // Add this to save pembayaran status
    
            // Jika status tempahan = 1, set Pelulus status ke "Sedang Diproses"
            if ($newStatusTempahan == 1) {
                $booking->status_tempahan_pelulus = 1;
            }
            
           // Jika status tempahan = 4 (Dibatalkan), reset status berkaitan pembayaran
           if ($newStatusTempahan == 4) { 
                $booking->status_pembayaran = 0; // Atau set ke 0 jika nak indicate "Belum set"
                $booking->alasan_batal = $alasan; // Simpan alasan
                $booking->dibatalkan_oleh = Yii::$app->user->id;

                if (in_array(Yii::$app->user->identity->role, [0,1,6])) {
                    try {
                        Yii::$app->mailer2->compose()
                            ->setTo($booking->email) // User's email from booking
                            // ->setFrom([Yii::$app->params[$adminEmail] => 'Sistem Tempahan MyFasiliti'])
                            ->setFrom([$adminEmail => 'Sistem Tempahan MyFasiliti']) // **Guna email admin yang ubah pelulus**
                            ->setSubject('Tempahan Ditolak')
                            ->setHtmlBody("
                                    <p>Assalamualaikum / Salam Sejahtera,</p>
                                    <p>Dukacita dimaklumkan bahawa tempahan anda dengan ID: {$booking->id} telah dibatalkan.</p>
                                    <p><strong>Alasan Pembatalan:</strong> {$booking->alasan_batal}</p>
                                    <p>Sila hubungi pihak pentadbiran jika ada sebarang pertanyaan.</p>
                                    <br>
                                    <p>Terima kasih.</p>
                                ")
                            ->send();

                            Yii::error("Debug: Status penghantaran email: " . ($result ? 'BERJAYA' : 'GAGAL'));

                        } catch (\Exception $e) {
                            Yii::error("Email gagal dihantar: " . $e->getMessage());
                        }
                    }
                } else {
                Yii::error("Debug: Email tidak dihantar kerana admin tiada email.");
            }
       
          // Save the updated booking and handle errors
          if ($booking->save()) {
              return ['success' => true, 'message' => 'Status updated successfully.'];
          } else {
              Yii::error('Error updating Admin Kemudahan status: ' . json_encode($booking->getErrors()));
              Yii::error('INI TEST LOG dari manual');
                Yii::getLogger()->flush(true);

              return [
                  'success' => false,
                  'message' => 'Failed to update status.',
                  'errors' => $booking->getErrors(),
                ];
            }
        }

        return ['success' => false, 'message' => 'Kaedah permintaan tidak sah.'];
    }

    public function actionPelulus()
    {
        $searchModel = new TempahFasilitiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->andWhere([
            'or',
            ['in', 'status_tempahan_adminKemudahan', [2, 3, 5]], // Admin Kemudahan Approved
            ['status_tempahan_pelulus' => 3] // Pelulus yang Reject
        ]);

        // Filter supaya hanya tempahan yang assigned kepada pelulus yang login sahaja
        $dataProvider->query->andWhere(['diluluskan_oleh' => Yii::$app->user->id]);
        

        return $this->render('pelulus', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }


    public function actionUpdatePelulus()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $id = Yii::$app->request->post('id');
        $diluluskan_oleh = Yii::$app->request->post('diluluskan_oleh');

        
        Yii::error("Debug: Terima request untuk update pelulus. ID Tempahan: $id, ID Pelulus: $diluluskan_oleh");

        $bookings = TempahFasiliti::findOne($id);
        if (!$bookings) {
            Yii::error("Debug: Tempahan dengan ID $id tidak dijumpai.");
            return ['success' => false, 'message' => 'Tempahan tidak dijumpai.'];
        }

        $bookings->diluluskan_oleh = $diluluskan_oleh;
        
        if ($bookings->save()) {

            Yii::error("Debug: Pelulus berjaya dikemaskini. ID: $diluluskan_oleh");

            // Ambil email pelulus berdasarkan ID dalam 'diluluskan_oleh'
            $pelulus = \app\models\User::findOne($bookings->diluluskan_oleh);
            $pelulusEmail = $pelulus ? $pelulus->email : null;
            $namaPemohon = $bookings->user->nama ?? 'Tidak diketahui';

            // Ambil email admin yang buat perubahan
            $adminEmail = Yii::$app->user->identity->email ?? Yii::$app->params['adminEmails'];

            Yii::error("Debug: Email pelulus -> " . $pelulusEmail);
            Yii::error("Debug: Email pengirim -> " . $adminEmail);

               // Hantar email kepada pelulus yang baru dilantik
            if ($pelulusEmail) {
                try {
                 $result = Yii::$app->mailer2->compose()
                    ->setTo($pelulusEmail)
                    ->setFrom([$adminEmail => 'Sistem Tempahan MyFasiliti']) // Emel dihantar atas nama admin yg sahkan
                    ->setSubject('Tempahan untuk Diluluskan')
                    ->setHtmlBody("
                        <div style='font-family: Arial, sans-serif; color: #333; font-size: 14px; line-height: 1.6;'>
                            <p>Assalamualaikum dan Salam Sejahtera,</p>

                            <p><strong>Tuan/Puan,</strong></p>

                            <p>Sukacita dimaklumkan bahawa tuan/puan telah dipilih sebagai pelulus bagi tempahan fasiliti berikut yang dibuat melalui sistem <strong>MyFasiliti</strong>. Maklumat tempahan adalah seperti berikut:</p>

                            <table style='border-collapse: collapse; margin-top: 10px;'>
                                <tr>
                                    <td style='padding: 4px 8px; font-weight: bold;'>ID Tempahan</td>
                                    <td style='padding: 4px 8px;'>:</td>
                                    <td style='padding: 4px 8px;'>{$bookings->id}</td>
                                </tr>
                                <tr>
                                    <td style='padding: 4px 8px; font-weight: bold;'>Nama Pemohon</td>
                                    <td style='padding: 4px 8px;'>:</td>
                                    <td style='padding: 4px 8px;'>{$namaPemohon}</td>
                                </tr>
                                <tr>
                                    <td style='padding: 4px 8px; font-weight: bold;'>Jenis Fasiliti</td>
                                    <td style='padding: 4px 8px;'>:</td>
                                    <td style='padding: 4px 8px;'>{$bookings->fasiliti->nama_fasiliti}</td>
                                </tr>
                                <tr>
                                    <td style='padding: 4px 8px; font-weight: bold;'>Tarikh Masuk</td>
                                    <td style='padding: 4px 8px;'>:</td>
                                    <td style='padding: 4px 8px;'>" . Yii::$app->formatter->asDate($bookings->tarikh_masuk, 'php:j/n/Y') . "</td>
                                </tr>
                                <tr>
                                    <td style='padding: 4px 8px; font-weight: bold;'>Tarikh Keluar</td>
                                    <td style='padding: 4px 8px;'>:</td>
                                    <td style='padding: 4px 8px;'>" . Yii::$app->formatter->asDate($bookings->tarikh_keluar, 'php:j/n/Y') . "</td>
                                </tr>
                            </table>

                            <p>Mohon tuan/puan untuk menyemak dan meluluskan permohonan ini melalui sistem MyFasiliti.</p>

                            <p>Segala kerjasama dan tindakan pihak tuan/puan amat dihargai.</p>

                            <p>Yang menjalankan tugas,<br>
                            <em>Sistem Tempahan MyFasiliti</em></p>
                        </div>
                    ")
                    ->send();


                    Yii::error("Debug: Status penghantaran email: " . ($result ? 'BERJAYA' : 'GAGAL'));

                } catch (\Exception $e) {
                    Yii::error("Email gagal dihantar: " . $e->getMessage());
                }
             } else {
            Yii::error("Debug: Email tidak dihantar kerana pelulus tiada email.");
        }

            return ['success' => true, 'message' => 'Pelulus berjaya dikemaskini.'];
        } else {
            return ['success' => false, 'message' => 'Gagal mengemaskini pelulus.'];
        }
    }

        public function actionChangeStatusPelulus($id)
        {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $status = Yii::$app->request->post('status_tempahan_pelulus');
            $alasan = Yii::$app->request->post('alasan_batal');

            if (!$id || !$status) {
                return ['success' => false, 'message' => 'ID tempahan atau status tidak lengkap.'];
            }

            $booking = TempahFasiliti::findOne($id);

            if (!$booking) {
                return ['success' => false, 'message' => 'Tempahan tidak dijumpai.'];
            }

            $booking->status_tempahan_pelulus = $status;
            $adminEmail = Yii::$app->user->identity->email ?? Yii::$app->params['adminEmails'];
            $fasiliti_id = $booking->fasiliti_id;

           if ($status == 2) {
                try {
                    if ($booking->status_pembayaran == 2) {
                        $booking->status_tempahan_adminKemudahan = 2;
                        $this->sendApprovalEmailBerbayarToUser($booking, $adminEmail);
                        $this->sendEmailBerbayarToKewangan($booking, $adminEmail);
                    } else {
                        $booking->status_tempahan_adminKemudahan = 3;
                        $this->sendApprovalEmailToUser($booking, $adminEmail);
                        $this->sendApprovalEmailToAdmin($booking, $adminEmail);
                    }

                    if ($booking->save(false)) {
                        return ['success' => true, 'message' => 'Status berjaya dikemaskini.'];
                    } else {
                        return ['success' => false, 'message' => 'Gagal menyimpan perubahan.'];
                    }
                } catch (\Throwable $e) {
                    Yii::error("Gagal dalam proses status 2: " . $e->getMessage(), 'application');
                    return ['success' => false, 'message' => 'Gagal proses status 2'];
                }
                return ['success' => false, 'message' => 'Proses status 2 gagal tanpa exception.'];

            }

            if ($status == 3) {
                $booking->status_tempahan_adminKemudahan = 4;
                $booking->alasan_batal = $alasan;

                try {
                    $result = Yii::$app->mailer2->compose()
                        ->setTo($booking->email)
                        ->setFrom([$adminEmail => 'Sistem Tempahan MyFasiliti'])
                        ->setSubject('Tempahan Ditolak')
                        ->setHtmlBody("
                            <p>Assalamualaikum / Salam Sejahtera,</p>
                            <p>Dukacita dimaklumkan bahawa tempahan anda dengan ID: {$booking->id} telah dibatalkan.</p>
                            <p><strong>Alasan Pembatalan:</strong> {$booking->alasan_batal}</p>
                            <p>Sila hubungi pihak pentadbiran jika ada sebarang pertanyaan.</p>
                            <br>
                            <p>Terima kasih.</p>
                        ")
                        ->send();

                    Yii::info("Debug: Status penghantaran email: " . ($result ? 'BERJAYA' : 'GAGAL'));

                } catch (\Exception $e) {
                    Yii::error("Email gagal dihantar: " . $e->getMessage());
                }

                if ($booking->save()) {
                    return ['success' => true, 'message' => 'Status berjaya dikemaskini.'];
                } else {
                    Yii::error('Gagal menyimpan: ' . json_encode($booking->getErrors()));
                    return [
                        'success' => false,
                        'message' => 'Gagal mengemaskini status.',
                        'errors' => $booking->getErrors(),
                    ];
                }
                    // 🔥 Tambah lagi satu fallback return
                return ['success' => false, 'message' => 'Proses status 3 gagal secara senyap.'];
            }

        // Fallback return kalau status pelik
        return ['success' => true, 'message' => 'OK.'];
    
    }

    private function sendApprovalEmailToUser($booking, $adminEmail)
    {
        $namaPemohon = $booking->user->nama ?? 'Tidak diketahui';

        // Condition ikut fasiliti_id
        if ($booking->fasiliti_id === 20) {
           $pickupInfo = "<li>Untuk pengambilan kunci auditorium, sila berjumpa dengan <strong>Encik Maznizam</strong> pada waktu bekerja: <em>Isnin hingga Jumaat, jam 9:00 pagi hingga 4:00 petang</em>.</li>";

        } else {
            $pickupInfo = "<li>Pengambilan kunci fasiliti boleh dibuat di <strong>Unit Kemudahan</strong> pada waktu bekerja sahaja: <em>Isnin hingga Jumaat, jam 8:00 pagi hingga 5:00 petang</em>.</li>";
        }

        Yii::$app->mailer2->compose()
            ->setTo($booking->email)
            ->setFrom([$adminEmail => 'Sistem Tempahan MyFasiliti'])
            ->setSubject('Makluman Kelulusan Tempahan Fasiliti')
            ->setHtmlBody("
                <p>Assalamualaikum dan Salam Sejahtera,</p>

                <p><strong>Tuan/Puan,</strong></p>

                <p>Sukacita dimaklumkan bahawa tempahan fasiliti melalui sistem <strong>MyFasiliti</strong> dengan maklumat berikut telah <strong>diluluskan tanpa sebarang bayaran</strong>:</p>

                <table style='border-collapse: collapse; margin: 10px 0;'>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>ID Tempahan</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'><strong>{$booking->id}</strong></td>
                    </tr>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>Nama Pemohon</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>{$namaPemohon}</td>
                    </tr>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>Jenis Fasiliti</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>{$booking->fasiliti->nama_fasiliti}</td>
                    </tr>
                     <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>Tarikh Masuk</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>" . Yii::$app->formatter->asDate($booking->tarikh_masuk, 'php:j/n/Y') . "</td>
                    </tr>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>Tarikh Keluar</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>" . Yii::$app->formatter->asDate($booking->tarikh_keluar, 'php:j/n/Y') . "</td>
                    </tr>
                    
                </table>

                <p><strong>Tindakan Seterusnya:</strong></p>
                <ul>
                    {$pickupInfo}
                    <li>Kunci hendaklah dipulangkan selepas penggunaan, juga dalam waktu yang sama.</li>
                </ul>

                <p>Untuk sebarang pertanyaan lanjut, sila hubungi terus pihak Unit Kemudahan melalui emel rasmi: 
                <a href='mailto:kemudahan@ciast.gov.my'>kemudahan@ciast.gov.my</a></p>

                <p>Sekian, terima kasih.</p>

                <p>Yang menjalankan tugas,<br/>
                <em>Sistem Tempahan MyFasiliti</em></p>
            ")
            ->send();
    }

    private function sendApprovalEmailToAdmin($booking, $adminEmail)
    {
        $namaPemohon = $booking->user->nama ?? 'Tidak diketahui';
        // ✅ Hantar emel kepada semua admin kemudahan (role 1 & 6)

        // Pilih admin penerima ikut fasiliti_id
        if ($booking->fasiliti_id == 20) {
            // Fasiliti khas, hantar ke role 8 sahaja
            $adminEmails = User::find()
                ->select('email')
                ->where(['role' => 8])
                ->column();
        } else {
            // Fasiliti lain, hantar ke role 1 & 6
            $adminEmails = User::find()
                ->select('email')
                ->where(['role' => [1, 6]])
                ->column();
        }

        Yii::$app->mailer2->compose()
            ->setTo($adminEmails)
            ->setFrom([$adminEmail => 'Sistem Tempahan MyFasiliti'])
            ->setSubject('Makluman Kelulusan Tempahan Fasiliti')
            ->setHtmlBody("
                <p>Assalamualaikum dan Salam Sejahtera,</p>

                <p><strong>Tuan/Puan,</strong></p>

                <p>Sukacita dimaklumkan bahawa tempahan fasiliti melalui sistem <strong>MyFasiliti</strong> dengan maklumat berikut telah <strong>diluluskan tanpa sebarang bayaran</strong>:</p>

                <table style='border-collapse: collapse; margin: 10px 0;'>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>ID Tempahan</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'><strong>{$booking->id}</strong></td>
                    </tr>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>Nama Pemohon</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>{$namaPemohon}</td>
                    </tr>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>Jenis Fasiliti</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>{$booking->fasiliti->nama_fasiliti}</td>
                    </tr>
                   <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>Tarikh Masuk</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>" . Yii::$app->formatter->asDate($booking->tarikh_masuk, 'php:j/n/Y') . "</td>
                    </tr>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>Tarikh Keluar</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>" . Yii::$app->formatter->asDate($booking->tarikh_keluar, 'php:j/n/Y') . "</td>
                    </tr>
                </table>

                <p>Sekian, terima kasih.</p>

                <p>Yang menjalankan tugas,<br/>
                <em>Sistem Tempahan MyFasiliti</em></p>
            ")
            ->send();
    }

    private function sendEmailBerbayarToKewangan($booking, $adminEmail)
    {
        // Ambil semua admin kewangan (user dengan role == 7)
        $adminKewanganEmails = User::find()
            ->select('email')
            ->where(['role' => 7])
            ->column();

        if (empty($adminKewanganEmails)) {
            return [
                'success' => false,
                'message' => 'Tiada admin kewangan dijumpai.',
            ];
        }

        // Kandungan emel
        $subject = '[Tindakan Diperlukan] Penyediaan Bil untuk Tempahan Baharu';
        $namaPemohon = $booking->user->nama ?? 'Tidak diketahui';
        $jenisFasiliti = $booking->fasiliti->nama_fasiliti ?? 'Tidak diketahui';

        $content = "
            <p>Assalamualaikum / Salam Sejahtera,</p>

            <p>Satu tempahan fasiliti telah diluluskan dan memerlukan penyediaan bil oleh pihak Unit Kewangan:</p>

            <table style='border-collapse: collapse; margin-top: 10px;'>
                <tr>
                    <td style='padding: 4px 8px; font-weight: bold;'>ID Tempahan</td>
                    <td style='padding: 4px 8px;'>:</td>
                    <td style='padding: 4px 8px;'>{$booking->id}</td>
                </tr>
                <tr>
                    <td style='padding: 4px 8px; font-weight: bold;'>Nama Pemohon</td>
                    <td style='padding: 4px 8px;'>:</td>
                    <td style='padding: 4px 8px;'>{$namaPemohon}</td>
                </tr>
                <tr>
                    <td style='padding: 4px 8px; font-weight: bold;'>Jenis fasiliti</td>
                    <td style='padding: 4px 8px;'>:</td>
                    <td style='padding: 4px 8px;'>{$booking->fasiliti->nama_fasiliti}</td>
                </tr>
                <tr>
                    <td style='padding: 4px 8px; font-weight: bold;'>Tarikh Masuk</td>
                    <td style='padding: 4px 8px;'>:</td>
                    <td style='padding: 4px 8px;'>" . Yii::$app->formatter->asDate($booking->tarikh_masuk, 'php:j/n/Y') . "</td>
                </tr>
                <tr>
                    <td style='padding: 4px 8px; font-weight: bold;'>Tarikh Keluar</td>
                    <td style='padding: 4px 8px;'>:</td>
                    <td style='padding: 4px 8px;'>" . Yii::$app->formatter->asDate($booking->tarikh_keluar, 'php:j/n/Y') . "</td>
                </tr>
            </table>

            <p>Kerjasama dan perhatian pihak tuan/puan amat dihargai. Sekian, terima kasih.</p>
            <p>Sekian, terima kasih.</p>

            <p>Yang menjalankan tugas,<br/>
            <em>Sistem Tempahan MyFasiliti</em></p>
        ";

        // Hantar emel
        try {
            $success = Yii::$app->mailer2->compose()
                ->setFrom([$adminEmail => 'Sistem Tempahan MyFasiliti'])
                ->setTo($adminKewanganEmails)
                ->setSubject($subject)
                ->setHtmlBody($content)
                ->send();

            return $success;
        } catch (\Exception $e) {
            Yii::error('Email error (Kewangan): ' . $e->getMessage(), 'emel-kewangan');
            return false;
        }
    }

    private function sendApprovalEmailBerbayarToUser($booking, $adminEmail)
    {
        $imageUrl = "https://internal-ipayment.anm.gov.my/storage/images/4f98cf8b920f49699140915e9baf9227.png";

        $emailBody = "
            <div style='font-family: Arial, sans-serif; color: #333; font-size: 14px; line-height: 1.6;'>
                <p>Assalamualaikum dan Salam Sejahtera,</p>

                <p><strong>Tuan/Puan,</strong></p>

                <p>
                    Dimaklumkan bahawa tempahan fasiliti anda melalui sistem <strong>MyFasiliti</strong> telah 
                    <strong style='color: red;'>disahkan</strong>. Butiran tempahan adalah seperti berikut:
                </p>

                <table style='border-collapse: collapse; margin-top: 10px;'>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>ID Tempahan</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'><strong>" . htmlspecialchars($booking->id) . "</strong></td>
                    </tr>
                </table>

                <p>
                    Sila buat pembayaran melalui portal 
                    <a href='https://ipayment.anm.gov.my/' target='_blank' style='font-weight: bold; color: #0000EE; text-decoration: underline;'>
                        iPayment
                    </a>.
                </p>

                <ul style='margin-left: 20px; padding-left: 20px;'>
                    <li><strong>Sila jelaskan bayaran dalam tempoh 5 hari selepas bil dijana di iPayment.</strong></li>
                    <li>Jika tiada bayaran diterima dalam tempoh tersebut, permohonan akan dibatalkan secara automatik.</li>
                    <li><strong>Pastikan pemohon dan akaun iPayment didaftarkan menggunakan nombor Kad Pengenalan yang sama.</strong></li>
                    <li>Bil akan tersedia dalam tempoh 24 jam dari tarikh pengesahan tempahan, tertakluk kepada hari dan waktu bekerja sahaja.</li>
                    <li>Sila semak jumlah bayaran dengan teliti sebelum membuat pembayaran.</li>
                    <li><strong>Sebarang tuntutan bayaran balik adalah tidak dibenarkan.</strong></li>
                    <li><strong>Sekiranya terdapat kesulitan untuk mengakses iPayment, sila hubungi kakitangan <a href='mailto:kemudahan@ciast.gov.my'>kemudahan@ciast.gov.my</a> atau <a href='mailto:kewangan@ciast.gov.my'>kewangan@ciast.gov.my</a></strong></li>
                </ul>

                <p>
                    Sekian, terima kasih atas kerjasama tuan/puan.
                </p>

                <p><em>Sistem Tempahan MyFasiliti</em></p>

                <img src='" . $imageUrl . "' alt='Peringatan iPayment' style='max-width:65%; display:block; margin-top:10px;'>
            </div>
        ";
        
        Yii::$app->mailer2->compose()
            ->setTo($booking->email)
            ->setFrom([$adminEmail => 'Sistem Tempahan MyFasiliti'])
            ->setSubject('Notis: Tempahan Anda Disahkan & Menunggu Bayaran')
            ->setHtmlBody($emailBody) // Email dengan format HTML
            ->send();
    }
    

    public function actionPrintSingle($id)
    {
        // $currentUserId = Yii::$app->user->id; // ID pengguna log masuk

        // Cari tempahan spesifik yang dimiliki oleh pengguna & sudah disahkan
        $approvedBooking = \app\models\TempahFasiliti::find()
        ->where([
            'id' => $id,
            'status_tempahan_pelulus' => 2, // Disahkan
        ])
        ->andWhere(['in', 'status_pembayaran', [1, 2, 3]]) // Bayaran disahkan / belum disahkan
        ->one(); // Ambil satu tempahan sahaja

        if (!$approvedBooking) {
            throw new NotFoundHttpException('Tempahan tidak dijumpai atau tidak disahkan.');
        }

        return $this->renderPartial('print-single', [
            'approvedBooking' => $approvedBooking,
        ]);
    }


    public function actionTempahanBerjaya() 
    {
        $searchModel = new TempahFasilitiSearch();

        // Start dengan query default search()
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Apply condition tambahan ikut role
        $dataProvider->query
            ->andWhere(['status_tempahan_pelulus' => 2])
            ->andWhere(['status_tempahan_adminKemudahan' => [3, 5]]);

        $role = Yii::$app->user->identity->role;

        if ($role == 8) {
            $dataProvider->query->andWhere(['fasiliti_id' => 20]);
        } elseif (!in_array($role, [0, 8])) {
            $dataProvider->query->andWhere(['<>', 'fasiliti_id', 20]);
        }

        return $this->render('tempahan-berjaya', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTempahanGagal() 
    {
        $searchModel = new TempahFasilitiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Tambah filter status gagal
        $dataProvider->query->andWhere([
            'or',
            ['in', 'status_tempahan_adminKemudahan', [4]], // Ditolak oleh admin kemudahan
            ['status_tempahan_pelulus' => 3] // Ditolak oleh pelulus
        ]);

        // Tapis ikut role
        $role = Yii::$app->user->identity->role;

        if ($role == 8) {
            // Role 8 hanya fasiliti_id = 23
            $dataProvider->query->andWhere(['fasiliti_id' => 20]);
        } elseif (!in_array($role, [0, 8])) {
            // Role lain tak boleh nampak fasiliti_id = 23
            $dataProvider->query->andWhere(['<>', 'fasiliti_id', 20]);
        }

        return $this->render('tempahan-gagal', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionMenungguBayaran() 
    {
        $searchModel = new TempahFasilitiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query
        ->andWhere(['status_tempahan_pelulus' => 2]) 
        ->andWhere(['status_pembayaran' => 2])
        ->andWhere(['status_tempahan_adminKemudahan' => 2]); 

        return $this->render('menunggu-bayaran', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }

    public function actionTempahanDisahkanBayaran() 
    {
        $searchModel = new TempahFasilitiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query
        ->andWhere(['status_tempahan_pelulus' => 2])
        ->andWhere(['status_pembayaran' => 3])
        ->andWhere(['status_tempahan_adminKemudahan' => 5]); 

        return $this->render('tempahan-disahkan-bayaran', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }

    public function actionTukarStatusBayaran($id)
    {
        $booking = $this->findModel($id);

        $allowedRoles = [0, 7];
        if (!in_array(Yii::$app->user->identity->role, $allowedRoles)) {
            throw new ForbiddenHttpException('Anda tidak dibenarkan untuk akses fungsi ini.');
        }

        $request = Yii::$app->request;

        // Ambil dan validate no_resit
        $noResit = $request->post('no_resit');
        if (!$noResit || !preg_match('/^\d{6}$/', $noResit)) {
            Yii::$app->session->setFlash('error', 'Sila masukkan nombor resit yang sah (6 digit).');
            return $this->redirect($request->referrer ?: ['index']);
        }

        // 2 = Menunggu Bayaran, 3 = Bayaran Selesai
        if ($booking->status_pembayaran == 2) {
            $booking->status_pembayaran = 3;
            $booking->status_tempahan_adminKemudahan = 5;
            $booking->no_resit = $noResit; // Simpan resit

            $booking->disahkanBayaran_oleh = Yii::$app->user->id;

            if ($booking->save(false)) {
                Yii::$app->session->setFlash('success', 'Status bayaran berjaya dikemaskini.');

                // ✅ Call function emel di sini
                $adminEmail = Yii::$app->user->identity->email;
                $this->sendBayaranDisahkanEmailToUser($booking, $adminEmail);

                // ✅ Hantar emel kepada semua admin kemudahan (role 1 & 6)
                $adminEmails = User::find()
                    ->select('email')
                    ->where(['role' => [1, 6]])
                    ->column();

                if (!empty($adminEmails)) {
                    foreach ($adminEmails as $email) {
                        $this->sendBayaranDisahkanEmailToAdmin($booking, $email);
                    }
                }

            } else {
                Yii::$app->session->setFlash('error', 'Gagal menyimpan perubahan status bayaran.');
            }
        } else {
            Yii::$app->session->setFlash('info', 'Status bayaran tidak perlu ditukar.');
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    private function sendBayaranDisahkanEmailToUser($booking, $adminEmail)
    {
        Yii::$app->mailer2->compose()
            ->setTo($booking->email)
            ->setFrom([$adminEmail => 'Sistem Tempahan MyFasiliti'])
            ->setSubject('Makluman Kelulusan Tempahan & Bayaran Disahkan')
            ->setHtmlBody("
                <p>Assalamualaikum dan Salam Sejahtera,</p>

                <p><strong>Tuan/Puan,</strong></p>

                <p>Sukacita dimaklumkan bahawa tempahan fasiliti melalui sistem <strong>MyFasiliti</strong> dengan maklumat berikut telah <strong>diluluskan dan bayaran telah disahkan</strong>:</p>

                <table style='border-collapse: collapse; margin: 10px 0;'>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>ID Tempahan</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'><strong>{$booking->id}</strong></td>
                    </tr>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>Jenis fasiliti</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>{$booking->fasiliti->nama_fasiliti}</td>
                    </tr>
                     <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>Tarikh Masuk</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>" . Yii::$app->formatter->asDate($booking->tarikh_masuk, 'php:j/n/Y') . "</td>
                    </tr>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>Tarikh Keluar</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>" . Yii::$app->formatter->asDate($booking->tarikh_keluar, 'php:j/n/Y') . "</td>
                    </tr>
                </table>

                <p><strong>Tindakan Seterusnya:</strong></p>
                <ul>
                    <li>Pengambilan kunci fasiliti boleh dibuat di <strong>Unit Kemudahan</strong> pada waktu bekerja sahaja: <em>Isnin hingga Jumaat, jam 8:00 pagi hingga 5:00 petang</em>.</li>
                    <li>Kunci hendaklah dipulangkan selepas penggunaan, juga dalam waktu yang sama.</li>
                </ul>

                <p>Untuk sebarang pertanyaan lanjut, sila hubungi terus pihak Unit Kemudahan melalui emel rasmi: 
                <a href='mailto:kemudahan@ciast.gov.my'>kemudahan@ciast.gov.my</a></p>

                <p>Sekian, terima kasih.</p>

                <p>Yang menjalankan tugas,<br/>
                <em>Sistem Tempahan MyFasiliti</em></p>
            ")
            ->send();
    }

    private function sendBayaranDisahkanEmailToAdmin($booking, $adminEmail)
    {
        // ✅ Hantar emel kepada semua admin kemudahan (role 1 & 6)
        $adminEmails = User::find()
            ->select('email')
            ->where(['role' => [1, 6]])
            ->column();

        $namaPemohon = $booking->user->nama ?? 'Tidak diketahui';


        Yii::$app->mailer2->compose()
            ->setTo($adminEmails)
            ->setFrom([$adminEmail => 'Sistem Tempahan MyFasiliti'])
            ->setSubject('Makluman Kelulusan Tempahan & Bayaran Disahkan')
            ->setHtmlBody("
                <p>Assalamualaikum dan Salam Sejahtera,</p>

                <p><strong>Tuan/Puan,</strong></p>

                <p>Sukacita dimaklumkan bahawa tempahan fasiliti melalui sistem <strong>MyFasiliti</strong> dengan maklumat berikut telah <strong>diluluskan dan bayaran telah disahkan</strong>:</p>

                <table style='border-collapse: collapse; margin: 10px 0;'>
                   <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>ID Tempahan</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'><strong>{$booking->id}</strong></td>
                    </tr>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>Nama Pemohon</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>{$namaPemohon}</td>
                    </tr>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>Jenis fasiliti</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>{$booking->fasiliti->nama_fasiliti}</td>
                    </tr>
                     <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>Tarikh Masuk</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>" . Yii::$app->formatter->asDate($booking->tarikh_masuk, 'php:j/n/Y') . "</td>
                    </tr>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>Tarikh Keluar</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>" . Yii::$app->formatter->asDate($booking->tarikh_keluar, 'php:j/n/Y') . "</td>
                    </tr>
                </table>

            ")
            ->send();
    }

    public function actionInvoisDijana($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = TempahFasiliti::findOne($id);
        if ($model && Yii::$app->request->isPost) {
            $model->invois_dijana = Yii::$app->request->post('invois_dijana') == 1 ? 1 : 0;
            $model->tarikh_invois_dijana = date('Y-m-d H:i:s'); // column ni perlu ada dalam DB
            $model->save(false);
            return ['success' => true];
        }

        return ['success' => false];
    }

    public function actionUploadSlip($id)
    {
        $booking = $this->findModel($id); 

        $adminEmail = Yii::$app->user->identity->email ?? Yii::$app->params['adminEmails'];

        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstance($booking, 'slip_pembayaran');
            if ($file) {
                $fileName = uniqid() . '.' . $file->extension;
                if ($file->saveAs('uploads/' . $fileName)) {
                    $booking->slip_pembayaran = $fileName;
                    $booking->tarikh_upload_slip = date('Y-m-d H:i:s'); 
                    if ($booking->save(false)) {
                        $this->sendEmailDahBayarKewanganKemudahan($booking, $adminEmail);
                        Yii::$app->session->setFlash('success', 'Slip berjaya dimuat naik.');
                    } else {
                        Yii::$app->session->setFlash('error', 'Gagal simpan rekod slip.');
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal upload fail.');
                }
            }
        }

        // Semak tempahan yang telah diluluskan oleh admin dan status pembayaran masih aktif
        $confirmedBooking = \app\models\TempahFasiliti::find()
            ->where([
                'id' => $id,
                'status_tempahan_pelulus' => 2, // Disahkan
            ])
            //->andWhere(['in', 'status_pembayaran', [1, 3]]) // Bayaran disahkan / belum disahkan
            ->one();

        if (!$confirmedBooking) {
            throw new NotFoundHttpException('Tempahan tidak dijumpai atau tidak disahkan.');
        }

        return $this->redirect(['senarai-tempahan']);
    }

    //
    private function sendEmailDahBayarKewanganKemudahan($booking, $adminEmail)
    {
        // Ambil semua admin kewangan (user dengan role == 7)
        $adminKewanganKemudahanEmails = User::find()
            ->select('email')
            ->where(['in', 'role', [1, 6, 7]])
            ->column();

        if (empty($adminKewanganKemudahanEmails)) {
            return [
                'success' => false,
                'message' => 'Tiada admin kewangan dijumpai.',
            ];
        }

        // Kandungan emel
        $subject = 'Pemberitahuan: Resit Pembayaran Telah Dimuat Naik';
        $namaPemohon = $booking->user->nama ?? 'Tidak diketahui';

        $content = "
        <div style='font-family: Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.6;'>
            <p>Assalamualaikum dan Salam Sejahtera,</p>

            <p>Dimaklumkan bahawa resit bayaran bagi tempahan fasiliti telah dimuat naik oleh pengguna seperti berikut:</p>

            <table style='border-collapse: collapse; margin-top: 10px;'>
                <tr>
                    <td style='padding: 4px 8px; font-weight: bold;'>ID Tempahan</td>
                    <td style='padding: 4px 8px;'>:</td>
                    <td style='padding: 4px 8px;'>{$booking->id}</td>
                </tr>
                <tr>
                    <td style='padding: 4px 8px; font-weight: bold;'>Nama Pemohon</td>
                    <td style='padding: 4px 8px;'>:</td>
                    <td style='padding: 4px 8px;'>{$namaPemohon}</td>
                </tr>
                 <tr>
                    <td style='padding: 4px 8px; font-weight: bold;'>Jenis fasiliti</td>
                    <td style='padding: 4px 8px;'>:</td>
                    <td style='padding: 4px 8px;'>{$booking->fasiliti->nama_fasiliti}</td>
                </tr>
                 <tr>
                    <td style='padding: 4px 8px; font-weight: bold;'>Tarikh Masuk</td>
                    <td style='padding: 4px 8px;'>:</td>
                    <td style='padding: 4px 8px;'>" . Yii::$app->formatter->asDate($booking->tarikh_masuk, 'php:j/n/Y') . "</td>
                </tr>
                <tr>
                    <td style='padding: 4px 8px; font-weight: bold;'>Tarikh Keluar</td>
                    <td style='padding: 4px 8px;'>:</td>
                    <td style='padding: 4px 8px;'>" . Yii::$app->formatter->asDate($booking->tarikh_keluar, 'php:j/n/Y') . "</td>
                </tr>
            </table>

            <p>Mohon semakan dan tindakan lanjut oleh pihak tuan/puan.</p>

            <p>Sekian, terima kasih.</p>

            <p><em>Sistem Tempahan MyFasiliti</em></p>
        </div>
    ";


        // Hantar emel
        try {
            $success = Yii::$app->mailer2->compose()
                ->setFrom([$adminEmail => 'Sistem Tempahan MyFasiliti'])
                ->setTo($adminKewanganKemudahanEmails)
                ->setSubject($subject)
                ->setHtmlBody($content)
                ->send();

            return $success;
        } catch (\Exception $e) {
            Yii::error('Email error (Kewangan): ' . $e->getMessage(), 'emel-kewangan');
            return false;
        }
    }

    public function actionPrintPass($id)
    {
        $model = TempahFasiliti::findOne($id);
        $user = $model->user; // pindah lepas check model

        if (!$model) {
            throw new NotFoundHttpException("Booking tidak dijumpai.");
        }

         // Disable layout untuk view ni
        // $this->layout = false;

        return $this->renderPartial('print-pass', [
            'model' => $model,
            'user' => $user,
        ]);
    }

}
