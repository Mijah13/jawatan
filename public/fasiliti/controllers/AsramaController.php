<?php

namespace app\controllers;

use Yii;
use app\models\Asrama;
use app\models\JenisAsrama;
use app\models\TempahAsrama;
use app\models\AsramaStatusLog;
use app\models\AsramaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\db\Query;


/**
 * AsramaController implements the CRUD actions for Asrama model.
 */
class AsramaController extends Controller
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
                    'only' => ['bilik', 'index', 'create', 'update', 'view'], // senarai action yang perlu login
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'], // hanya user login
                        ],
                    ],
                    'denyCallback' => function () {
                        return Yii::$app->response->redirect(['site/login']);
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
     * Lists all Asrama models.
     *
     * @return string
     */
    public function actionIndex()
    {

    // Auto trigger untuk update bilik yang dah habis tempoh tempahan
    // $this->updateStatusAsramaSelepasTempahan();

        $searchModel = new AsramaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Asrama model.
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

    public function actionSenaraiBilik()
    {
        $searchModel = new \app\models\AsramaSearch();
        $type = \Yii::$app->request->get('type'); 
        $queryParams = $this->request->queryParams;

        // Dapatkan 'id' jenis bilik dari URL (dihantar dari asrama/bilik)
        $jenisBilikId = \Yii::$app->request->get('id'); 

        if (\Yii::$app->user->identity->role < 2) {
            $dataProvider = $searchModel->search($queryParams);
        } else {
            $queryParams = array_merge($queryParams, ['type' => $type]); 
            $dataProvider = $searchModel->search($queryParams);
        }

        // **FILTER SUPAYA HANYA BILIK DENGAN JENIS BILIK SAMA DIPAPARKAN**
        if ($jenisBilikId) {
            $dataProvider->query->andWhere(['jenis_bilik' => $jenisBilikId]);
        }

        // Hanya tunjuk bilik kosong
        $dataProvider->query->andWhere(['status_asrama' => 0]);

        return $this->render('senarai-bilik', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'jenisBilik' => \app\models\JenisAsrama::findOne($jenisBilikId),
        ]);
    }

    public function actionBilik()
    {

        $query = JenisAsrama::find();
        // Jika role = 3 atau 4, exclude jenis_bilik dengan id 5
        if (!Yii::$app->user->isGuest && in_array(Yii::$app->user->identity->role, [3, 4])) {
            $query->andWhere(['!=', 'id', 5]);
        }

        $jenisAsrama = $query->all();

        // Render the view dan pass data bilik
        return $this->render('bilik', [
            'jenisAsrama' => $jenisAsrama,
        ]);
    }

    /**
     * Creates a new Asrama model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    // public function actionCreate()
    // {
    //     $model = new Asrama();

    //     if ($this->request->isPost) {
    //         if ($model->load($this->request->post()) && $model->save()) {
    //             return $this->redirect(['view', 'id' => $model->id]);
    //         }
    //     } else {
    //         $model->loadDefaultValues();
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

    public function actionCreate()
    {
        $model = new Asrama();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {

                // Ambil nilai dari POST, sebab status_hari_ini bukan field dalam table asrama
                $status = Yii::$app->request->post('Asrama')['status_hari_ini'] ?? null;

                if ($status !== null && $status !== '') {
                    $log = new AsramaStatusLog();
                    $log->id_asrama = $model->id;
                    $log->status_log = $status;
                    $log->tarikh_mula = date('Y-m-d');
                    $log->save(false);
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing Asrama model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
    
        // // Only set current status if it's GET (i.e. masa buka form)
        // if (Yii::$app->request->isGet) {
        //     $latestStatus = AsramaStatusLog::find()
        //         ->where(['id_asrama' => $model->id])
        //         ->orderBy(['tarikh_mula' => SORT_DESC])
        //         ->one();
    
        //     $model->status_hari_ini = $latestStatus->status_log;
        // }
    
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
    
            // if (!empty($model->status_hari_ini)) {
            //     $log = new AsramaStatusLog();
            //     $log->id_asrama = $model->id;
            //     $log->status_log = $model->status_hari_ini;
            //     $log->tarikh_mula = date('Y-m-d');
            //     $log->save(false);
            // }
    
            return $this->redirect(['view', 'id' => $model->id]);
        }
    
        return $this->render('update', [
            'model' => $model,
        ]);
    }
    
    /**
     * Deletes an existing Asrama model.
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
     * Finds the Asrama model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Asrama the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Asrama::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionGetAvailableRooms($tarikh_masuk, $tarikh_keluar)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Ambil tarikh dari request
        $tarikh_masuk = Yii::$app->request->get('tarikh_masuk');
        $tarikh_keluar = Yii::$app->request->get('tarikh_keluar');

        // Debug Step 1: Check kalau tarikh masuk betul-betul sampai
        if (!$tarikh_masuk || !$tarikh_keluar) {
            Yii::error("Tarikh tidak diterima! tarikh_masuk: $tarikh_masuk, tarikh_keluar: $tarikh_keluar", 'application');
            return ['error' => 'Tarikh tidak diterima'];
        }

        // Debug Step 2: Log data masuk
        Yii::info("Tarikh Masuk: $tarikh_masuk, Tarikh Keluar: $tarikh_keluar", 'application');

        $tarikh_keluar_with_buffer = date('Y-m-d', strtotime($tarikh_keluar . ' +5 days'));

        // Buat subquery untuk bilik yang sedang ditempah
        $subquery = (new Query())
        ->select('id_asrama')
        ->from('tempah_asrama') // Pastikan nama table betul
        ->where([
            'and',
            ['<', 'tarikh_masuk', $tarikh_keluar_with_buffer], // Booking masuk sebelum tarikh_keluar yang user pilih
            ['>', 'tarikh_pembersihan', $tarikh_masuk], // Pembersihan tamat selepas tarikh_masuk yang user pilih
        ])

        ->andWhere(['!=', 'status_tempahan_adminKemudahan', 4]); // Kecuali tempahan batal
        

    // Query bilik kosong ikut tarikh
    $asramaCounts = (new Query())
        ->select(['jenis_asrama_id', 'COUNT(*) AS total'])
        ->from('asrama')
        // ->where(['status_asrama' => 0]) // Confirm bilik sekarang kosong
        ->where(['not in', 'id', $subquery]) // Tak bertindih dengan tempahan aktif
        ->groupBy('jenis_asrama_id')
        ->indexBy('jenis_asrama_id')
        ->all();

        // Debug Step 3: Log hasil query
        Yii::info("Result Query: " . print_r($asramaCounts, true), 'application');

        // Debug Step 4: Stop execution untuk tengok data dalam response (buka Network Tab di browser)
        return $this->asJson([
            'success' => true,
            'bilikKosong' => $asramaCounts
        ]);
    }

// Dalam controller contohnya SiteController / AsramaController

// public function actionPopulateAsramaLog()
// {
//     $asramas = \app\models\Asrama::find()->all();

//     $count = 0;
//     foreach ($asramas as $asrama) {
//         $existingLog = \app\models\AsramaStatusLog::find()
//             ->where(['id_asrama' => $asrama->id])
//             ->exists();

//         if (!$existingLog) {
//             $log = new \app\models\AsramaStatusLog();
//             $log->id_asrama = $asrama->id;
//             $log->status_asrama = $asrama->status_asrama;
//             $log->tarikh_mula = date('Y-m-d'); // atau '2025-01-01'
//             if ($log->save(false)) {
//                 $count++;
//             }
//         }
//     }

//     return "Log berjaya dimasukkan untuk $count bilik.";
// }
    //cronjob
    // public function actionKemaskiniStatusAsrama()
    // {
    //     $today = date('Y-m-d');
    //     Yii::info("Kemaskini status asrama untuk tarikh: $today", 'asrama');

    //     $tempahan = TempahAsrama::find()
    //         ->where(['status_tempahan_pelulus' => 2]) // Hanya tempahan diluluskan
    //         ->all();

    //     foreach ($tempahan as $item) {
    //         $asrama = Asrama::findOne($item->id_asrama);
    //         if (!$asrama) continue;

    //         if ($today == $item->tarikh_masuk) {
    //             $asrama->status_asrama = 6; // Diisi
    //         } elseif ($today > $item->tarikh_keluar && $today <= $item->tarikh_pembersihan) {
    //             $asrama->status_asrama = 1; // Sedang Dibersihkan
    //         } elseif ($today > $item->tarikh_pembersihan) {
    //             // Check jika tiada tempahan aktif lain untuk bilik tu
    //             $masihDiguna = TempahAsrama::find()
    //                 ->where(['id_asrama' => $asrama->id])
    //                 ->andWhere(['status_tempahan_pelulus' => 2])
    //                 ->andWhere(['>', 'tarikh_pembersihan' => $today])
    //                 ->exists();

    //             if (!$masihDiguna) {
    //                 $asrama->status_asrama = 0; // Kosong
    //             }
    //         }

    //         $asrama->save(false);
    //     }

    //     echo "Kemaskini status bilik siap.\n";
    // }



}
