<?php

namespace app\controllers;

use app\models\Fasiliti;
use app\models\StatusFasiliti;
use app\models\TempahAsrama;
use app\models\TempahFasiliti;
use app\models\FasilitiSearch;
use app\models\JenisAsrama;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\UploadedFile;
use app\models\FasilitiStatusLog;
use Yii;

/**
 * FasilitiController implements the CRUD actions for Fasiliti model.
 */
class FasilitiController extends Controller
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
                    'only' => ['index', 'create', 'update', 'delete', 'user-view', 'asrama/senarai-bilik', 'senarai-fasiliti'], // Specify restricted actions
                    'rules' => [
                        // Allow all users (authenticated and guest) to access user-view
                        [
                            'allow' => true,
                            'actions' => ['user-view'],
                            'roles' => ['@', '?'], // Allow both authenticated users and guests
                        ],
                        // Allow roles 0 and 1 to access all actions
                        [
                            'allow' => true,
                            'actions' => ['index', 'create', 'update', 'delete', 'user-view', 'asrama/senarai-bilik', 'senarai-fasiliti'],
                            'roles' => ['@'], // Authenticated users only
                            'matchCallback' => function ($rule, $action) {
                                return in_array(Yii::$app->user->identity->role, [0, 1, 2, 3, 4, 5, 6, 7, 8]);
                            },
                        ],
                        // [
                        //     'allow' => true,
                        //     'actions' => ['senarai-fasiliti'],
                        //     'roles' => ['@'], // Authenticated users only
                        //     'matchCallback' => function ($rule, $action) {
                        //         return in_array(Yii::$app->user->identity->role, [3, 4, 5, 7]);
                        //     },
                        // ],
                        // Deny all other actions for guests (non-authenticated users)
                        [
                            'allow' => false,
                            'roles' => ['?'], // Deny access for guests to actions other than user-view
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
     * Lists all Fasiliti models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new FasilitiSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Fasiliti model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $Fasiliti = Fasiliti::find()->all();
        $jenisAsrama = JenisAsrama::find()->all();

        return $this->render('view', [
            'Fasiliti' => $Fasiliti,
            'jenisAsrama' => $jenisAsrama,
            
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Fasiliti model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Fasiliti();
    
        if ($model->load($this->request->post())) {
            $uploadedImage = UploadedFile::getInstance($model, 'imej');
    
            if ($uploadedImage) {
                $imageName = uniqid() . '.' . $uploadedImage->extension;
                $imageUploadPath = 'images/' . $imageName;
    
                if ($uploadedImage->saveAs($imageUploadPath)) {
                    $model->gambar = $imageName; // Simpan nama fail sahaja, bukan path penuh
                } else {
                    Yii::error("Failed to upload image.");
                    return $this->render('create', ['model' => $model]);
                }
            }
    
            if ($model->save(false)) { // `false` untuk skip validation kalau tiada error lain
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::error('Model validation failed: ' . json_encode($model->errors));
            }
        }
    
        return $this->render('create', ['model' => $model]);
    }
    
    /**
     * Updates an existing Fasiliti model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($this->request->isPost && $model->load($this->request->post())) {
    //         $model->imej = UploadedFile::getInstance($model, 'imej'); 

    //         $imageName = uniqid() . '.' . $model->imej->extension; // Generate a unique name for the image
    //         $model->gambar = $imageName;

    //         $imageUploadPath = 'images/' . $imageName;
    //         // return $imageUploadPath;
    //         if ($model->imej->saveAs($imageUploadPath)) {
    //            if(!$model->save(false))
    //                 return print_r($model->getErrors());
    //             // else 
    //             //     return $model->gambar;
    //         }
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }

    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // print_r($_POST);
        // exit;


        if ($this->request->isPost && $model->load($this->request->post())) {
            // if ($model->kadar_sewa_perJam === '') {
            //     $model->kadar_sewa_perJam = null;
            // }
            // if ($model->kadar_sewa_perHari === '') {
            //     $model->kadar_sewa_perHari = null;
            // }
            // if ($model->kadar_sewa_perJamSiang === '') {
            //     $model->kadar_sewa_perJamSiang = null;
            // }
            // if ($model->kadar_sewa_perJamMalam === '') {
            //     $model->kadar_sewa_perJamMalam = null;
            // }
                        
            $uploadedImage = UploadedFile::getInstance($model, 'imej');

            if ($uploadedImage) {
                $oldImage = $model->gambar; // Save the old image name
                $imageName = uniqid() . '.' . $uploadedImage->extension;
                $imageUploadPath = 'images/' . $imageName;
            
                if ($uploadedImage->saveAs($imageUploadPath)) {
                    $model->gambar = $imageName;
                    // Delete the old image file
                    if (!empty($oldImage) && file_exists('images/' . $oldImage)) {
                        unlink('images/' . $oldImage);
                    }
                } else {
                    return print_r($model->getErrors());
                }
            }
            // echo "<pre>";
            // print_r($model->attributes);
            // echo "</pre>";
            // exit;

                
            // Save the model (will retain the old image if no new image was uploaded)
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return print_r($model->getErrors());
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing Fasiliti model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }
    
    public function actionDelete($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            $model = $this->findModel($id);
            $model->delete();

            return ['success' => true];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Gagal padam data: ' . $e->getMessage()];
        }
    }


    /**
     * Finds the Fasiliti model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Fasiliti the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fasiliti::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    /**
     * Fetches and displays facility types and jenis asrama.
     *
     * @return string
     */
    public function actionUserView()//refer to jenisfasiliti
    {
        $Fasiliti = Fasiliti::find()->all();
        $jenisAsrama = JenisAsrama::find()->all();

        return $this->render('user_view', [
            'Fasiliti' => $Fasiliti,
            'jenisAsrama' => $jenisAsrama,
            // 'bookedDates' => json_encode($bookedDates),

        ]);
    
    }
    public function actionSenaraiFasiliti()
    {
        $Fasiliti = Fasiliti::find()->all(); // Fetch all facilities
        return $this->render('senarai-fasiliti', [
            'Fasiliti' => $Fasiliti,
        ]);
    }

    // Dalam controller contohnya SiteController / AsramaController

//     public function actionPopulateFasilitiLog()
// {
//     $fasilitis = \app\models\Fasiliti::find()->all();
//     $count = 0;
//     $debug = [];

//     foreach ($fasilitis as $fasiliti) {
//         $existingLog = \app\models\FasilitiStatusLog::find()
//             ->where(['fasiliti_id' => $fasiliti->id])
//             ->exists();

//         if (!$existingLog) {
//             $log = new \app\models\FasilitiStatusLog();
//             $log->fasiliti_id = $fasiliti->id;
//             $log->fasiliti_status = $fasiliti->fasiliti_status;
//             $log->tarikh_mula = date('Y-m-d');

//             if (!$log->save()) {
//                 $debug[$fasiliti->id] = $log->errors;
//             } else {
//                 $count++;
//             }
//         }
//     }

//     return $this->renderContent("<pre>Inserted: $count\nErrors:\n" . print_r($debug, true) . "</pre>");
// }


}
