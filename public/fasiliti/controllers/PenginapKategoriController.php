<?php

namespace app\controllers;

use app\models\PenginapKategori;
use app\models\PenginapKategoriSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PenginapKategoriController implements the CRUD actions for PenginapKategori model.
 */
class PenginapKategoriController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
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
     * Lists all PenginapKategori models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PenginapKategoriSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PenginapKategori model.
     * @param int $id_penginap Id Penginap
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_penginap)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_penginap),
        ]);
    }

    /**
     * Creates a new PenginapKategori model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PenginapKategori();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_penginap' => $model->id_penginap]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PenginapKategori model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_penginap Id Penginap
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_penginap)
    {
        $model = $this->findModel($id_penginap);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_penginap' => $model->id_penginap]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PenginapKategori model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_penginap Id Penginap
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_penginap)
    {
        $this->findModel($id_penginap)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PenginapKategori model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_penginap Id Penginap
     * @return PenginapKategori the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_penginap)
    {
        if (($model = PenginapKategori::findOne(['id_penginap' => $id_penginap])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
