<?php

namespace app\controllers;

use app\models\JenisAsrama;
use app\models\JenisAsramaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use Yii;    

/**
 * JenisAsramaController implements the CRUD actions for JenisAsrama model.
 */
class JenisAsramaController extends Controller
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
                    'only' => ['index', 'create', 'update', 'delete'], // Specify restricted actions
                    'rules' => [    
                        
                        // Allow roles 0 and 1 to access all actions
                        [
                            'allow' => true,
                            'actions' => ['index', 'create', 'update', 'delete'],
                            'roles' => ['@'], // Authenticated users only
                            'matchCallback' => function ($rule, $action) {
                                return in_array(Yii::$app->user->identity->role, [0, 1, 6]);
                            },
                        ],
                        // Rule for role 3 (User) - allow access to create, view, update, and cancel bookings
                        // [
                        //     'allow' => true,
                        //     'actions' => ['create', 'view', 'update', 'delete'],
                        //     'roles' => ['@'], // Authenticated users only
                        //     'matchCallback' => function ($rule, $action) {
                        //         return in_array(Yii::$app->user->identity->role, [3, 4]);
                        //     },
                        // ],
                        // Deny all actions for guests (non-authenticated users)
                        [
                            'allow' => false,
                            'roles' => ['?'], // Deny access for guests
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
     * Lists all JenisAsrama models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new JenisAsramaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single JenisAsrama model.
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
     * Creates a new JenisAsrama model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new JenisAsrama();

       if ($model->load($this->request->post())) {
            $uploadedImages = UploadedFile::getInstances($model, 'imej');
            $gambarArray = [];

            Yii::error("Uploaded Images: " . json_encode($uploadedImages));

            if (!empty($uploadedImages)) {
                foreach ($uploadedImages as $uploadedImage) { // ✅ Loop untuk setiap fail
                    $imageName = uniqid() . '.' . $uploadedImage->extension;
                    $imageUploadPath = 'images/' . $imageName;
    
                    if ($uploadedImage->saveAs($imageUploadPath)) {
                        $gambarArray[] = $imageName; // Simpan nama fail sahaja
                    } else {
                        Yii::error("Failed to upload image: " . $uploadedImage->name);
                    }
                }
            }
    
            if (!empty($gambarArray)) {
                $model->gambar = json_encode($gambarArray); // Simpan dalam bentuk JSON
                Yii::error('Gambar yang disimpan: ' . json_encode($gambarArray));

            }
    
            if ($model->save(false)) { // `false` untuk skip validation kalau tiada error lain
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::error('Model validation failed: ' . json_encode($model->errors));
            }
        }
    
        return $this->render('create', ['model' => $model]);
    }

    // public function actionCreate()
    // {
    //     $model = new JenisAsrama();
    
    //     if ($model->load($this->request->post())) {
    //         $uploadedImage = UploadedFile::getInstance($model, 'imej');
    
    //         if ($uploadedImage) {
    //             $imageName = uniqid() . '.' . $uploadedImage->extension;
    //             $imageUploadPath = 'images/' . $imageName;
    
    //             if ($uploadedImage->saveAs($imageUploadPath)) {
    //                 $model->gambar = $imageName; // Simpan nama fail sahaja, bukan path penuh
    //             } else {
    //                 Yii::error("Failed to upload image.");
    //                 return $this->render('create', ['model' => $model]);
    //             }
    //         }
    
    //         if ($model->save(false)) { // `false` untuk skip validation kalau tiada error lain
    //             return $this->redirect(['view', 'id' => $model->id]);
    //         } else {
    //             Yii::error('Model validation failed: ' . json_encode($model->errors));
    //         }
    //     }
    
    //     return $this->render('create', ['model' => $model]);
    // }
    /**
     * Updates an existing JenisAsrama model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($this->request->isPost && $model->load($this->request->post())) {
    //         $uploadedImage = UploadedFile::getInstance($model, 'imej');

    //         if ($uploadedImage) {
    //             $oldImage = $model->gambar; // Save the old image name
    //             $imageName = uniqid() . '.' . $uploadedImage->extension;
    //             $imageUploadPath = 'images/' . $imageName;
            
    //             if ($uploadedImage->saveAs($imageUploadPath)) {
    //                 $model->gambar = $imageName;
    //                 // Delete the old image file
    //                 if (!empty($oldImage) && file_exists('images/' . $oldImage)) {
    //                     unlink('images/' . $oldImage);
    //                 }
    //             } else {
    //                 return print_r($model->getErrors());
    //             }
    //         }
                
    //         // Save the model (will retain the old image if no new image was uploaded)
    //         if ($model->save(false)) {
    //             return $this->redirect(['view', 'id' => $model->id]);
    //         } else {
    //             return print_r($model->getErrors());
    //         }
    //     }

    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }

//     public function actionUpdate($id)
// {
//     $model = $this->findModel($id); // Retrieve the existing model by its ID

//     if ($model->load($this->request->post())) {
//         // Get the newly uploaded images, if any
//         $uploadedImages = UploadedFile::getInstances($model, 'imej');
//         $gambarArray = json_decode($model->gambar, true); // Decode existing images into an array

//         Yii::error("Uploaded Images: " . json_encode($uploadedImages));

//         // Handle uploaded images
//         if (!empty($uploadedImages)) {
//             foreach ($uploadedImages as $uploadedImage) {
//                 $imageName = uniqid() . '.' . $uploadedImage->extension;
//                 $imageUploadPath = 'images/' . $imageName;

//                 // Try saving the uploaded image
//                 if ($uploadedImage->saveAs($imageUploadPath)) {
//                     $gambarArray[] = $imageName; // Add new image name to the array
//                 } else {
//                     Yii::error("Failed to upload image: " . $uploadedImage->name);
//                 }
//             }
//         }

//         // If there are any new images, update the gambar field in the model
//         if (!empty($gambarArray)) {
//             $model->gambar = json_encode($gambarArray); // Store images in JSON format
//             Yii::error('Updated images saved: ' . json_encode($gambarArray));
//         }

//         // Save the model, skipping validation (set to `false` if you want to skip validation)
//         if ($model->save(false)) {
//             return $this->redirect(['view', 'id' => $model->id]); // Redirect to the view page after saving
//         } else {
//             Yii::error('Model validation failed: ' . json_encode($model->errors)); // Log validation errors
//         }
//     }

//     // Render the update form if the model was not loaded or saved successfully
//     return $this->render('update', ['model' => $model]);
// }

public function actionUpdate($id)
{
    $model = $this->findModel($id); // Retrieve the existing model by its ID

    if ($model->load($this->request->post())) {
        // Get the newly uploaded images, if any
        $uploadedImages = UploadedFile::getInstances($model, 'imej');
        $gambarArray = json_decode($model->gambar, true); // Decode existing images into an array

        Yii::error("Uploaded Images: " . json_encode($uploadedImages));

        // Handle uploaded images
        if (!empty($uploadedImages)) {
            foreach ($uploadedImages as $uploadedImage) {
                $imageName = uniqid() . '.' . $uploadedImage->extension;
                $imageUploadPath = 'images/' . $imageName;

                // Try saving the uploaded image
                if ($uploadedImage->saveAs($imageUploadPath)) {
                    $gambarArray[] = $imageName; // Add new image name to the array
                } else {
                    Yii::error("Failed to upload image: " . $uploadedImage->name);
                }
            }
        }

        // Handle removed images
        if (isset($this->request->post()['remove_images'])) {
            $removedImages = $this->request->post()['remove_images']; // Array of image names to remove

            // Loop through removed images and delete them from the file system and the gambar array
            foreach ($removedImages as $removedImage) {
                if (in_array($removedImage, $gambarArray)) {
                    // Remove image from the file system
                    $imagePath = 'images/' . $removedImage;
                    if (file_exists($imagePath)) {
                        unlink($imagePath); // Delete the file from the server
                    }

                    // Remove image name from the gambar array
                    $gambarArray = array_diff($gambarArray, [$removedImage]);
                }
            }
        }

        // If there are any images (new or existing), update the gambar field in the model
        if (!empty($gambarArray)) {
            $model->gambar = json_encode($gambarArray); // Store images in JSON format
            Yii::error('Updated images saved: ' . json_encode($gambarArray));
            Yii::error("Decoded gambarArray: " . print_r($gambarArray, true));

        }

        // Save the model, skipping validation (set to `false` if you want to skip validation)
        if ($model->save(false)) {
            return $this->redirect(['view', 'id' => $model->id]); // Redirect to the view page after saving
        } else {
            Yii::error('Model validation failed: ' . json_encode($model->errors)); // Log validation errors
        }
    }

    // Render the update form if the model was not loaded or saved successfully
    return $this->render('update', ['model' => $model]);
}


    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }

    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Deletes an existing JenisAsrama model.
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
     * Finds the JenisAsrama model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return JenisAsrama the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JenisAsrama::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
