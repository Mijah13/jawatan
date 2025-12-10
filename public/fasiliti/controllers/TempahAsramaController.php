<?php

namespace app\controllers;

use app\models\TempahAsrama;
use app\models\TempahAsramaSearch;
use app\models\JenisAsrama;
use app\models\Asrama;
use app\models\User;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use app\models\PenginapKategori;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\base\ErrorException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// require 'vendor/autoload.php';



use DateTime;
use Yii;

/**
 * TempahAsramaController implements the CRUD actions for TempahAsrama model.
 */
class TempahAsramaController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view','get-events', 'print', 'pelulus', 'print-senarai-pelajar', 'pelajar', 'set-bilik', 'export-excel', 'menunggu-bayaran'], 
                'rules' => [
                    // Rule for admin roles (0 and 1) - allow access to all actions
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'update', 'delete', 'view', 'get-events', 'send-email', 'print', 'print-senarai-pelajar', 'pelajar', 'set-bilik', 'export-excel'],
                        'roles' => ['@'], // Authenticated users
                        'matchCallback' => function ($rule, $action) {
                            return in_array(Yii::$app->user->identity->role, [0, 1, 6]);
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
                        'actions' => ['create', 'view', 'update', 'get-events', 'view', 'delete', 'send-email', 'print', 'asrama/bilik', 'senarai-tempahan'],
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

    /**
     * Lists all TempahAsrama models.
     *
     * @return string
     */
    // public function actionIndex()
    // {
    //     $searchModel = new TempahAsramaSearch();
    //     $dataProvider = $searchModel->search($this->request->queryParams);
        

    //     return $this->render('index', [
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }

    // public function beforeAction($action)
    // {
    //     if (Yii::$app->user->isGuest) {
    //         Yii::$app->response->redirect(['site/login'])->send();
    //         return false;
    //     }
    //     return parent::beforeAction($action);
    // }

    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            $this->redirect(['site/login']);
            return false; // hentikan action sekarang
        }
        return parent::beforeAction($action);
    }


    
    // public function actionIndex()
    // {
    //     $searchModel = new TempahAsramaSearch();
    //     $query = TempahAsrama::find()
    //         ->where(['IN', 'status_tempahan_adminKemudahan', [1, 2, 3]])
    //         ->andWhere([
    //             'OR',
    //             ['id_asrama' => null],
    //             ['diluluskan_oleh' => null],
    //             ['status_tempahan_adminKemudahan' => 1]
    //         ]);

    //     $dataProvider = new ActiveDataProvider([
    //         'query' => $query,
    //     ]);

    //     return $this->render('index', [
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }


    public function actionIndex()
    {
        TempahAsrama::releaseExpiredReservations();

        $searchModel = new TempahAsramaSearch();
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
     * Displays a single TempahAsrama model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        // Fetch the Tempah record by ID
        $model = TempahAsrama::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Tempahan tidak ditemui.');
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TempahAsrama model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */

    public function actionCreate()
    {
        $model = new TempahAsrama();
        $model->status_tempahan_adminKemudahan = 0; // Pending
        
        // **Cuba dapatkan tarikh dari POST dulu, kalau tak ada, baru ambil dari GET**
        $tarikh_masuk = Yii::$app->request->get('tarikh_masuk');
            
        $tarikh_keluar =  Yii::$app->request->get('tarikh_keluar');
        $model->jenis_bilik = \Yii::$app->request->get('jenis_bilik');
        

            $tarikhMasukDate = DateTime::createFromFormat('d-m-Y', $tarikh_masuk);
            $tarikhKeluarDate = DateTime::createFromFormat('d-m-Y', $tarikh_keluar);
            
            $model->tarikh_masuk = $tarikhMasukDate ? $tarikhMasukDate->format('Y-m-d') : null;
            $model->tarikh_keluar = $tarikhKeluarDate ? $tarikhKeluarDate->format('Y-m-d') : null;
            
            // var_dump($tarikh_masuk, $model->tarikh_keluar);
            // die();

        // **Check sama ada form di-submit**
        if ($model->load(Yii::$app->request->post())) {
            // **Set user info**
            $model->user_id = Yii::$app->user->id;
            $model->email = Yii::$app->user->identity->email;
            $model->is_simpanan = Yii::$app->request->post('TempahAsrama')['is_simpanan'] ?? 0;

            // **Upload surat_sokongan jika ada**
            $file = UploadedFile::getInstance($model, 'surat_sokongan');
            if ($file) {
                $fileName = uniqid() . '.' . $file->extension;
                $file->saveAs('/var/www/uploads/' . $fileName);
                //  $file->saveAs('uploads/' . $fileName);
                $model->surat_sokongan = $fileName;
            }

            // **Cek validation sebelum save**
            if (!$model->validate()) {
                return print_r($model->errors); // Debug validation errors
            }
            $model->tarikh_masuk = date('Y-m-d', strtotime($tarikh_masuk));
            $model->tarikh_keluar = date('Y-m-d', strtotime($tarikh_keluar));


            // **Simpan data ke dalam database**
            if ($model->save()) {

                // $asrama = Asrama::findOne($model->id);
                // if ($asrama) {
                //     $asrama->status_asrama = 6; // diisi
                //     $asrama->save();
                // }

                // Kalau pelajar, return JSON
            if (Yii::$app->user->identity->role == 5) {
                Yii::$app->session->setFlash('success', 'Tempahan anda telah berjaya disimpan.');
                return $this->redirect(['site/daftar']);
            }
            
                Yii::$app->session->setFlash('success', 'Tempahan anda telah berjaya disimpan.');
                return $this->redirect(['senarai-tempahan']);
            } else {
                Yii::$app->session->setFlash('error', 'Ralat berlaku semasa menyimpan tempahan anda. Sila cuba lagi.');
            }
        }

        // **Render view create jika tidak submit**
        return $this->render('create', ['model' => $model]);
    }

    // public function actionSenaraiTempahan()
    // {
    //     $dataProvider = new ActiveDataProvider([
    //         'query' =>  TempahAsrama::find()->where(['user_id' => Yii::$app->user->identity->id])
    //     ]);

    //     $hasBookings = $dataProvider->query->exists(); // Check if there are any bookings

    //     return $this->render('senarai-tempahan', [
    //         'dataProvider' => $dataProvider,
    //         'hasBookings' => $hasBookings,
    //     ]);
    // }

    public function actionSenaraiTempahan()
    {
        $searchModel = new TempahAsramaSearch();
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
     * Updates an existing TempahAsrama model.
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
            $tarikh_masuk = Yii::$app->request->post('TempahAsrama')['tarikh_masuk'];
            $tarikh_keluar = Yii::$app->request->post('TempahAsrama')['tarikh_keluar'];
            
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
     * Deletes an existing TempahAsrama model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     // Yii::$app->session->setFlash('success', 'Tempahan telah berjaya dipadam.');
    //     return $this->redirect(['senarai-tempahan']); // Redirect to the same page
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
     * Finds the TempahAsrama model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return TempahAsrama the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TempahAsrama::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetEvents($start, $end, $room_id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        try {
            $bookings = TempahAsrama::find()
                ->where(['id_asrama' => $room_id])
                ->andWhere(['or',
                    ['between', 'tarikh_masuk', $start, $end],
                    ['between', 'tarikh_keluar', $start, $end]
                ])
                ->all();

            $events = [];
            foreach ($bookings as $booking) {
                // Convert dates for display
                $tarikh_masuk = Yii::$app->formatter->asDate($booking->tarikh_masuk, 'php:d-m-Y');
                $tarikh_keluar = Yii::$app->formatter->asDate($booking->tarikh_keluar, 'php:d-m-Y');
                
                $events[] = [
                    'id' => $booking->id,
            
                    'title' => $booking->tujuan . "\n" . $tarikh_masuk . ' - ' . $tarikh_keluar,
                    'start' => Yii::$app->formatter->asDate($booking->tarikh_masuk, 'php:Y-m-d'),
                    'end' => date('Y-m-d', strtotime(Yii::$app->formatter->asDate($booking->tarikh_keluar, 'php:Y-m-d') . ' +1 day')),
                    'color' => '#28a745',
                ];
            }

            return $events;
        } catch (\Exception $e) {
            \Yii::error('Calendar error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }


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

        $bookings = TempahAsrama::findOne($id);

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
                'message' => 'Terdapat ralat semasa mengemas kini status tempahan.',
            ];
        }

        // **Ambil semua admin kemudahan (user dengan role == 1)**
        $adminEmails = User::find()
            ->select('email')
            // ->where(['role' => 1])
            ->where(['role' => [1, 6]]) // Ambil user dengan role 1 atau 6
            ->column(); // Dapatkan array email sahaja

        if (empty($adminEmails)) {
            return [
                'success' => false,
                'message' => 'Tiada admin kemudahan dijumpai.',
            ];
        }

        $namaPemohon = $bookings->user->nama ?? 'Tidak diketahui';

        $emailContentAdmin = "
        <div style='font-family: Arial, sans-serif; color: #333; font-size: 14px; line-height: 1.6;'>
        <p>Assalamualaikum dan Salam Sejahtera,</p>

        <p><strong>Tuan/Puan,</strong></p>

        <p>Sukacita dimaklumkan terdapat tempahan asrama baharu melalui sistem <strong>MyFasiliti</strong> yang memerlukan tindakan pengesahan pihak tuan/puan. Maklumat tempahan adalah seperti berikut:</p>

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
                <td style='padding: 4px 8px; font-weight: bold;'>Jenis Bilik</td>
                <td style='padding: 4px 8px;'>:</td>
                <td style='padding: 4px 8px;'>{$bookings->jenisBilik->jenis_bilik}</td>
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
                ->setSubject('Pengesahan Tempahan Asrama')
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

            <p>Tempahan anda untuk penginapan asrama telah dihantar dan sedang diproses oleh pihak pentadbiran melalui sistem <strong>MyFasiliti</strong>. Maklumat tempahan adalah seperti berikut:</p>

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
                    <td style='padding: 4px 8px; font-weight: bold;'>Jenis Bilik</td>
                    <td style='padding: 4px 8px;'>:</td>
                    <td style='padding: 4px 8px;'>{$bookings->jenisBilik->jenis_bilik}</td>
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

            <p>Anda akan menerima kemas kini setelah tempahan disahkan oleh pihak pentadbiran.</p>

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

    public function actionChangeStatus($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; 

        $booking = TempahAsrama::findOne($id);

        if (!$booking) {
            return ['success' => false, 'message' => 'Tempahan tidak dijumpai.'];
        }

        $adminEmail = Yii::$app->user->identity->email ?? Yii::$app->params['adminEmails'];

        if (Yii::$app->request->isPost) {
            $newStatusTempahan = Yii::$app->request->post('status_tempahan_adminKemudahan');
            $newStatusPembayaran = Yii::$app->request->post('status_pembayaran');
            $alasan = Yii::$app->request->post('alasan_batal');

            $booking->disokong_oleh = Yii::$app->user->id; 
            $booking->status_tempahan_adminKemudahan = $newStatusTempahan;
            $booking->status_pembayaran = $newStatusPembayaran;

            // Jika status tempahan = 1, set Pelulus status ke "Sedang Diproses"
            if ($newStatusTempahan == 1) {
                $booking->status_tempahan_pelulus = 1;
            }

            // if ($newStatusTempahan == 5) { //bayaran selesai
            //     $booking->status_pembayaran = 3; //telah dibayar
            // }

            // Jika status tempahan = 4 (Dibatalkan), reset status berkaitan pembayaran
            if ($newStatusTempahan == 4) { 
                $booking->status_pembayaran = 0; // Atau set ke 0 jika nak indicate "Belum Bayar"
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

            // Simpan ke database
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
        }

        return ['success' => false, 'message' => 'Kaedah permintaan tidak sah.'];
    }

    public function actionPelulus()
    {
        $searchModel = new TempahAsramaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->andWhere([
            'or',
            ['in', 'status_tempahan_adminKemudahan', [2, 3, 5]], // Admin Kemudahan Approved 
            ['status_tempahan_pelulus' => 3] // Pelulus yang Reject (remain vsible)
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

        $bookings = TempahAsrama::findOne($id);
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

                                <p>Sukacita dimaklumkan bahawa tuan/puan telah dipilih sebagai pelulus bagi tempahan asrama berikut yang dibuat melalui sistem <strong>MyFasiliti</strong>. Maklumat tempahan adalah seperti berikut:</p>

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
                                        <td style='padding: 4px 8px; font-weight: bold;'>Jenis Bilik</td>
                                        <td style='padding: 4px 8px;'>:</td>
                                        <td style='padding: 4px 8px;'>{$bookings->jenisBilik->jenis_bilik}</td>
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
        // Yii::$app->response->statusCode = 200;
        // Yii::$app->response->headers->set('Content-Type', 'application/json');

        $status = (int) Yii::$app->request->post('status_tempahan_pelulus');
        $alasan = Yii::$app->request->post('alasan_batal');

        if (!$id || !$status) {
            return ['success' => false, 'message' => 'ID tempahan atau status tidak lengkap.'];
        }

        $booking = TempahAsrama::findOne($id);
        if (!$booking) {
            return ['success' => false, 'message' => 'Tempahan tidak dijumpai.'];
        }

        $booking->status_tempahan_pelulus = $status;
        $adminEmail = Yii::$app->user->identity->email ?? Yii::$app->params['adminEmails'];

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
        $noBilik = ($booking->asrama)
        ? $booking->asrama->blok . $booking->asrama->aras . $booking->asrama->no_asrama
        : '-';

        Yii::$app->mailer2->compose()
            ->setTo($booking->email)
            ->setFrom([$adminEmail => 'Sistem Tempahan MyFasiliti'])
            ->setSubject('Makluman Kelulusan Tempahan Asrama')
            ->setHtmlBody("
                <p>Assalamualaikum dan Salam Sejahtera,</p>

                <p><strong>Tuan/Puan,</strong></p>

                <p>Sukacita dimaklumkan bahawa tempahan asrama melalui sistem <strong>MyFasiliti</strong> dengan maklumat berikut telah <strong>diluluskan tanpa sebarang bayaran</strong>:</p>

                <table style='border-collapse: collapse; margin: 10px 0;'>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>ID Tempahan</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'><strong>{$booking->id}</strong></td>
                    </tr>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>Jenis Bilik</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>{$booking->jenisBilik->jenis_bilik}</td>
                    </tr>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>No. Bilik</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>{$noBilik}</td>
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

    private function sendApprovalEmailToAdmin($booking, $adminEmail)
    {
        // ✅ Hantar emel kepada semua admin kemudahan (role 1 & 6)
        $adminEmails = User::find()
            ->select('email')
            ->where(['role' => [1, 6]])
            ->column();

        $noBilik = ($booking->asrama)
        ? $booking->asrama->blok . $booking->asrama->aras . $booking->asrama->no_asrama
        : '-';

        Yii::$app->mailer2->compose()
            ->setTo($adminEmails)
            ->setFrom([$adminEmail => 'Sistem Tempahan MyFasiliti'])
            ->setSubject('Makluman Kelulusan Tempahan Asrama')
            ->setHtmlBody("
                <p>Assalamualaikum dan Salam Sejahtera,</p>

                <p><strong>Tuan/Puan,</strong></p>

                <p>Sukacita dimaklumkan bahawa tempahan asrama melalui sistem <strong>MyFasiliti</strong> dengan maklumat berikut telah <strong>diluluskan tanpa sebarang bayaran</strong>:</p>

                <table style='border-collapse: collapse; margin: 10px 0;'>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>ID Tempahan</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'><strong>{$booking->id}</strong></td>
                    </tr>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>Jenis Bilik</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>{$booking->jenisBilik->jenis_bilik}</td>
                    </tr>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>No. Bilik</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>{$noBilik}</td>
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

        $subject = '[Tindakan Diperlukan] Penyediaan Bil untuk Tempahan Baharu';
        $namaPemohon = $booking->user->nama ?? 'Tidak diketahui';
        $noBilik = ($booking->asrama)
        ? $booking->asrama->blok . $booking->asrama->aras . $booking->asrama->no_asrama
        : '-';


        $content = "
            <p>Assalamualaikum / Salam Sejahtera,</p>

            <p>Satu tempahan penginapan asrama telah diluluskan dan memerlukan penyediaan bil oleh pihak Unit Kewangan:</p>

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
                    <td style='padding: 4px 8px; font-weight: bold;'>Jenis Bilik</td>
                    <td style='padding: 4px 8px;'>:</td>
                    <td style='padding: 4px 8px;'>{$booking->jenisBilik->jenis_bilik}</td>
                </tr>
                    <tr>
                    <td style='padding: 4px 8px; font-weight: bold;'>No. Bilik</td>
                    <td style='padding: 4px 8px;'>:</td>
                    <td style='padding: 4px 8px;'>{$noBilik}</td>
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
                Dimaklumkan bahawa tempahan asrama anda melalui sistem <strong>MyFasiliti</strong> telah 
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

        // Cari tempahan spesifik yang dimiliki oleh pengguna & sudah disahkan
        $approvedBooking = \app\models\TempahAsrama::find()
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

        
    public function actionPelajar() 
    {
        $searchModel = new TempahAsramaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

       // Filter hanya tempahan yang mempunyai 'no_matrik_pemohon' tidak kosong
        $dataProvider->query->andWhere(['IS NOT', 'no_matrik_pemohon', null])
        ->andWhere(['<>', 'no_matrik_pemohon', '']);

        return $this->render('pelajar', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }

    public function actionTempahanBerjaya() 
    {
        $searchModel = new TempahAsramaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Apply condition tambahan ikut role
        $dataProvider->query
                ->andWhere(['status_tempahan_pelulus' => 2]) // Mesti pelulus setuju dulu
                ->andWhere(['status_tempahan_adminKemudahan' => [3, 5]]); // AdminKemudahan = 2 atau 3
        
    
        return $this->render('tempahan-berjaya', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    

    public function actionTempahanGagal() 
    {
        $searchModel = new TempahAsramaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->andWhere([
            'or',
            ['in', 'status_tempahan_adminKemudahan', [4]], // Admin Kemudahan Reject first
            ['status_tempahan_pelulus' => 3] // Pelulus Reject
        ]);

        return $this->render('tempahan-gagal', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }

    public function actionSetBilik($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $model = TempahAsrama::findOne($id);
        if (!$model) {
            return ['success' => false, 'message' => 'Tempahan tidak dijumpai.'];
        }

        $model->id_asrama = Yii::$app->request->post('id_asrama');
        if ($model->save()) {
            return ['success' => true, 'message' => 'Bilik berjaya dikemaskini.'];
        } else {
            return ['success' => false, 'message' => 'Gagal kemaskini bilik.'];
        }
    }  

    public function actionMenungguBayaran() 
    {
        $searchModel = new TempahAsramaSearch();
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
        $searchModel = new TempahAsramaSearch();
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
        $model = $this->findModel($id);

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

        if ($model->status_pembayaran == 2) {
            $model->status_pembayaran = 3; // Bayaran Selesai
            $model->status_tempahan_adminKemudahan = 5; // Contoh status: Selesai
            $model->no_resit = $noResit; // Simpan resit

            $model->disahkanBayaran_oleh = Yii::$app->user->id;

            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Status bayaran berjaya dikemaskini.');

                // ✅ Hantar emel makluman
                $adminEmail = Yii::$app->user->identity->email;
                $this->sendBayaranDisahkanEmailToUser($model, $adminEmail);

                 // ✅ Hantar emel kepada semua admin kemudahan (role 1 & 6)
                $adminEmails = User::find()
                    ->select('email')
                    ->where(['role' => [1, 6]])
                    ->column();

                if (!empty($adminEmails)) {
                    foreach ($adminEmails as $email) {
                        $this->sendBayaranDisahkanEmailToAdmin($model, $email);
                    }
                }

            } else {
                Yii::$app->session->setFlash('error', 'Gagal menyimpan perubahan status bayaran.');
            }
        } else {
            Yii::$app->session->setFlash('info', 'Status bayaran tidak perlu ditukar.');
        }

        return $this->redirect($request->referrer ?: ['index']);
    }


    private function sendBayaranDisahkanEmailToUser($booking, $adminEmail)
    {
        $noBilik = ($booking->asrama)
            ? $booking->asrama->blok . $booking->asrama->aras . $booking->asrama->no_asrama
            : '-';

        Yii::$app->mailer2->compose()
            ->setTo($booking->email)
            ->setFrom([$adminEmail => 'Sistem Tempahan MyFasiliti'])
            ->setSubject('Makluman Kelulusan Tempahan & Bayaran Disahkan')
            ->setHtmlBody("
                <p>Assalamualaikum dan Salam Sejahtera,</p>

                <p><strong>Tuan/Puan,</strong></p>

                <p>Sukacita dimaklumkan bahawa tempahan asrama melalui sistem <strong>MyFasiliti</strong> dengan maklumat berikut telah <strong>diluluskan dan bayaran telah disahkan</strong>:</p>

                <table style='border-collapse: collapse; margin: 10px 0;'>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>ID Tempahan</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'><strong>{$booking->id}</strong></td>
                    </tr>
                    <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>Jenis Bilik</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>{$booking->jenisBilik->jenis_bilik}</td>
                    </tr>
                     <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>No. Bilik</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>{$noBilik}</td>
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
                    <li>Pengambilan kunci bilik boleh dibuat di <strong>Unit Kemudahan</strong> pada waktu bekerja sahaja: <em>Isnin hingga Jumaat, jam 8:00 pagi hingga 5:00 petang</em>.</li>
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

        $noBilik = ($booking->asrama)
            ? $booking->asrama->blok . $booking->asrama->aras . $booking->asrama->no_asrama
            : '-';

        $namaPemohon = $booking->user->nama ?? 'Tidak diketahui';


        Yii::$app->mailer2->compose()
            ->setTo($adminEmails)
            ->setFrom([$adminEmail => 'Sistem Tempahan MyFasiliti'])
            ->setSubject('Makluman Kelulusan Tempahan & Bayaran Disahkan')
            ->setHtmlBody("
                <p>Assalamualaikum dan Salam Sejahtera,</p>

                <p><strong>Tuan/Puan,</strong></p>

                <p>Sukacita dimaklumkan bahawa tempahan asrama melalui sistem <strong>MyFasiliti</strong> dengan maklumat berikut telah <strong>diluluskan dan bayaran telah disahkan</strong>:</p>

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
                        <td style='padding: 4px 8px; font-weight: bold;'>Jenis Bilik</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>{$booking->jenisBilik->jenis_bilik}</td>
                    </tr>
                     <tr>
                        <td style='padding: 4px 8px; font-weight: bold;'>No. Bilik</td>
                        <td style='padding: 4px 8px;'>:</td>
                        <td style='padding: 4px 8px;'>{$noBilik}</td>
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

    public function actionInvoisDijana($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = TempahAsrama::findOne($id);
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
                if ($file->saveAs('/var/www/uploads/' . $fileName)) {
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
        $confirmedBooking = \app\models\TempahAsrama::find()
            ->where([
                'id' => $id,
                'status_tempahan_pelulus' => 2, // Disahkan
            ])
            //->andWhere(['in', 'status_pembayaran', [1, 2, 3]]) // Bayaran disahkan / belum disahkan
            ->one();

        if (!$confirmedBooking) {
            throw new NotFoundHttpException('Tempahan tidak dijumpai atau tidak disahkan.');
        }

        return $this->redirect(['senarai-tempahan']);
    }

    private function sendEmailDahBayarKewanganKemudahan($booking, $adminEmail)
    {
        $noBilik = ($booking->asrama)
        ? $booking->asrama->blok . $booking->asrama->aras . $booking->asrama->no_asrama
        : '-';

        $adminKewanganKemudahanEmails = User::find()
            ->select('email')
            ->where(['in', 'role', [1, 6, 7]])
            ->column();

        if (empty( $adminKewanganKemudahanEmails)) {
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

            <p>Dimaklumkan bahawa resit bayaran bagi tempahan asrama telah dimuat naik oleh pengguna seperti berikut:</p>

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
                    <td style='padding: 4px 8px; font-weight: bold;'>Jenis Bilik</td>
                    <td style='padding: 4px 8px;'>:</td>
                    <td style='padding: 4px 8px;'>{$booking->jenisBilik->jenis_bilik}</td>
                </tr>
                    <tr>
                    <td style='padding: 4px 8px; font-weight: bold;'>No. Bilik</td>
                    <td style='padding: 4px 8px;'>:</td>
                    <td style='padding: 4px 8px;'>{$noBilik}</td>
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

    public function actionUpdateDiskaun($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = TempahAsrama::findOne($id);

        if ($model && Yii::$app->request->isPost) {
            $model->diskaun = Yii::$app->request->post('diskaun');

            if (!empty($model->diskaun)) {
                $model->pengiraan_bayaran = 1;
            }

            if ($model->save()) {
                return ['success' => true];
            } else {
                return [
                    'success' => false,
                    'message' => json_encode($model->getFirstErrors())
                ];
            }
        }

        return ['success' => false, 'message' => 'Invalid request.'];
    }

     public function actionPrintPass($id, $plate = null)
    {
        $model = TempahAsrama::findOne($id);
        $user = $model->user;

        if ($plate) {
            $model->no_plate = $plate;
            $model->save(false);
        }

        return $this->renderPartial('print-pass', [
            'model' => $model,
            'user' => $user,
        ]);
    }

    public function actionSavePlate()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $plateJson = Yii::$app->request->post('plate');

        $model = TempahAsrama::findOne($id);
        if (!$model) {
            return ['success' => false, 'message' => 'Tempahan tidak dijumpai'];
        }

        $model->no_plate = $plateJson; // column JSON / VARCHAR
        if ($model->save(false)) {
            return ['success' => true];
        }

        return ['success' => false, 'message' => 'Gagal simpan nombor plat'];
    }


}
