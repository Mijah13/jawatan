<?php

namespace app\controllers;

use app\models\PelajarAsrama;
use app\models\Asrama;
use app\models\User;
use app\models\PelajarAsramaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\web\UploadedFile;


use Yii;

/**
 * PelajarAsramaController implements the CRUD actions for PelajarAsrama model.
 */
class PelajarAsramaController extends Controller
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
     * Lists all PelajarAsrama models.
     *
     * @return string
     */

    // public function actionIndex()
    // {
    //     $searchModel1 = new PelajarAsramaSearch();
    //     $dataProvider1 = $searchModel1->search(Yii::$app->request->queryParams);
    //     $dataProvider1->query->andWhere(['id_asrama' => null]);

    //     $searchModel2 = new PelajarAsramaSearch();
    //     $dataProvider2 = null;

    //     $asramaList = ArrayHelper::map(
    //         Asrama::find()->joinWith('jenisAsrama')
    //             ->where(['jenis_asrama_id' => [4, 5]])
    //             // ->andWhere(['status_asrama' => 0])
    //             ->andWhere(['in', 'status_asrama', [0, 7]])
    //             ->all(),
    //         'id',
    //         function ($data) {
    //             return "{$data->blok}{$data->aras}{$data->no_asrama}";
    //         }
    //     );

    //     $sesiBatch = Yii::$app->request->get('sesi_batch');
    //     // var_dump($sesiBatch);

    //     if (!empty($sesiBatch)) {
    //         $dataProvider2 = $searchModel2->search(Yii::$app->request->queryParams);
    //         $dataProvider2->query->andWhere(['IS NOT', 'id_asrama', null])
    //                             ->andWhere(['sesi_batch' => $sesiBatch]);
    //     }

    //     return $this->render('index', [
    //         'searchModel1' => $searchModel1,
    //         'dataProvider1' => $dataProvider1,
    //         'searchModel2' => $searchModel2,
    //         'dataProvider2' => $dataProvider2,
    //         'sesiBatch' => $sesiBatch,
    //         'asramaList' => $asramaList,
    //     ]);
    // }

    public function actionIndex()
    {
        /* ---------- REKOD BELUM LENGKAP ---------- */
        $searchModel1   = new PelajarAsramaSearch();
        $dataProvider1  = $searchModel1->search(Yii::$app->request->queryParams);

        // Mesti kekal dalam DP1 selagi ADA salah satu field NULL
        $dataProvider1->query
            ->andWhere(['status_penginapan' => 0])
            ->andWhere([
                'or',
                ['id_asrama' => null],
                ['tarikh_masuk' => null],
            ]);

        /* ---------- REKOD LENGKAP ---------- */
        $searchModel2  = new PelajarAsramaSearch();
        $dataProvider2 = null;

        $sesiBatch = Yii::$app->request->get('sesi_batch');

        if (!empty($sesiBatch)) {
            $dataProvider2 = $searchModel2->search(Yii::$app->request->queryParams);

            // Hanya lepasi kalau SEMUA field dah ada
            $dataProvider2->query
                // ->andWhere(['IS NOT', 'id_asrama',     null])
                ->andWhere(['IS NOT', 'tarikh_masuk',  null])
                // ->andWhere(['IS NOT', 'tarikh_keluar', null])
                ->andWhere(['sesi_batch' => $sesiBatch]);
        }

        /* ---------- SENARAI BILIK ---------- */
        $asramaList = ArrayHelper::map(
            Asrama::find()->joinWith('jenisAsrama')
                ->where(['jenis_asrama_id' => [4, 5]])
                ->andWhere(['in', 'status_asrama', [0, 7]])
                ->all(),
            'id',
            fn($data) => "{$data->blok}{$data->aras}{$data->no_asrama}"
        );

        return $this->render('index', [
            'searchModel1'  => $searchModel1,
            'dataProvider1' => $dataProvider1,
            'searchModel2'  => $searchModel2,
            'dataProvider2' => $dataProvider2,
            'sesiBatch'     => $sesiBatch,
            'asramaList'    => $asramaList,
        ]);
    }



    /**
     * Displays a single PelajarAsrama model.
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
     * Creates a new PelajarAsrama model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PelajarAsrama();
        $model->user_id = Yii::$app->user->id;
        $model->email = Yii::$app->user->identity->email;
        $user = \app\models\User::findOne(Yii::$app->user->id);
        $showModal = false;
    
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $showModal = true; // <- ni flag utk trigger modal nanti
            }
        } else {
            $model->loadDefaultValues();
        }
    
        return $this->render('create', [
            'model' => $model,
            'showModal' => $showModal,
        ]);
    }
    
    /**
     * Updates an existing PelajarAsrama model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($id)
    {
        $model = PelajarAsrama::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Jangan tukar $model->user_id = Yii::$app->user->id kat sini
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PelajarAsrama model.
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
        $model = $this->findModel($id);

        // Simpan ID bilik sebelum delete
        $idAsrama = $model->id_asrama;

        if ($model->delete()) {

            // Update status asrama ke 'Kosong' (0)
            if ($idAsrama) {
                $asrama = \app\models\Asrama::findOne($idAsrama);
                if ($asrama && $asrama->status_asrama == 6) { // kalau asalnya 'Diisi'
                    $asrama->status_asrama = 0; // Kosong
                    $asrama->save(false);

                    // Optional: rekod ke log status
                    $log = new \app\models\AsramaStatusLog();
                    $log->id_asrama = $asrama->id;
                    $log->status_log = 0;
                    $log->tarikh_mula = date('Y-m-d');
                    $log->tarikh_tamat = date('Y-m-d');
                    // $log->catatan = 'Auto kosong selepas padam pelajar';
                    $log->save(false);
                }
            }

            Yii::$app->session->setFlash('success', 'Rekod pelajar dipadam dan status bilik dikosongkan.');
        } else {
            Yii::$app->session->setFlash('error', 'Gagal padam rekod.');
        }

        return $this->redirect(['index']);
    }


    /**
     * Finds the PelajarAsrama model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return PelajarAsrama the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PelajarAsrama::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionPrintSenaraiPelajar()
    {

         // Fetch all bookings for the logged-in user that meet the conditions
         $approvedBookings = \app\models\PelajarAsrama::find()
            ->where(['not', ['id_asrama' => null]])
            ->all();
    
            return $this->renderPartial('print-senarai-pelajar', [
                'approvedBookings' => $approvedBookings,
            ]);
    }

    // public function actionExportExcel()
    // {
    //     // 1. Guna search model untuk ikut filter user
    //     $searchModel = new PelajarAsramaSearch();
    //     // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    //     // $data = $dataProvider->getModels();

    //     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    //     $dataProvider->pagination = false; // ✅ Matikan pagination
    //     $data = $dataProvider->getModels(); // ✅ Sekarang ni ambil semua rekod

    //     // 2. Siapkan spreadsheet
    //     $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     // 3. Header kolum
    //     $headers = [
    //         'A' => 'ID',
    //         'B' => 'Bilik Asrama',
    //         'C' => 'Nama Pemohon',
    //         'D' => 'No Tel',
    //         'E' => 'Alamat',
    //         'F' => 'E-mail',
    //         'G' => 'Kod Kursus',
    //         'H' => 'Sesi Batch',
    //         'I' => 'Status',
    //         'J' => 'Jantina',
    //     ];
    //     foreach ($headers as $col => $title) {
    //         $sheet->setCellValue("{$col}1", $title);
    //     }

    //     // 4. Isi data
    //     $row = 2;
    //     foreach ($data as $item) {
    //         $sheet->setCellValue('A' . $row, $item->id);
    //         $sheet->setCellValue('B' . $row, 
    //             $item->id_asrama && $item->asrama 
    //                 ? $item->asrama->blok . $item->asrama->aras . $item->asrama->no_asrama 
    //                 : 'Tiada Bilik'
    //         );
    //         $sheet->setCellValue('C' . $row, $item->user->nama ?? '');
    //         $sheet->setCellValue('D' . $row, $item->no_tel);
    //         $sheet->setCellValue('E' . $row, $item->alamat);
    //         $sheet->setCellValue('F' . $row, $item->user->email ?? '');
    //         $sheet->setCellValue('G' . $row, $item->kod_kursus);
    //         $sheet->setCellValue('H' . $row, $item->sesi_batch);
    //         $sheet->setCellValue('I' . $row, $item->status == 0 ? 'Bujang' : 'Berkahwin');
    //         $sheet->setCellValue('J' . $row, $item->jantina == 0 ? 'Lelaki' : 'Perempuan');
    //         $row++;
    //     }

    //     // 5. Output download
    //     $filename = 'Senarai_Tempahan_Asrama_Pelajar_' . date('Ymd_His') . '.xlsx';
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment; filename="' . $filename . '"');
    //     header('Cache-Control: max-age=0');

    //     $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    //     $writer->save('php://output');
    //     exit;
    // }

    //diubah oleh raihan pada 17/10/2025, keluarkan data ikut batch yang dipilih dan export tersebut mempunyai header data

public function actionExportExcel()
{
    // --- 1) Baca sesi_batch dari query ---
    $params = Yii::$app->request->queryParams;
    $sesi = null;

    // Sokong dua bentuk: ?PelajarAsramaSearch[sesi_batch]=... ATAU ?sesi_batch=...
    if (isset($params['PelajarAsramaSearch']['sesi_batch'])) {
        $sesi = $params['PelajarAsramaSearch']['sesi_batch'];
    } elseif (isset($params['sesi_batch'])) {
        $sesi = $params['sesi_batch'];
    }

    // Wajib ada batch dipilih (kalau nak jadikan optional, buang exception ni)
    if (empty($sesi)) {
        throw new \yii\web\BadRequestHttpException('Sila pilih "sesi_batch" untuk eksport.');
    }

    // Sokong multiple: array atau CSV "Jan 2025,Feb 2025"
    if (is_string($sesi) && strpos($sesi, ',') !== false) {
        $sesi = array_map('trim', explode(',', $sesi));
    }

    // --- 2) Query direct ikut sesi_batch sahaja (bypass search()) ---
    /** @var \app\models\PelajarAsrama $PelajarAsrama */
    $query = \app\models\PelajarAsrama::find()
        // JOIN dengan alias 'a' supaya boleh ORDER BY kolum asrama
        ->joinWith(['asrama a'])
        // Kekalkan eager load user untuk elak N+1
        ->with(['user']);

    if (is_array($sesi)) {
        $query->andWhere(['in', 'sesi_batch', $sesi]);
    } else {
        $query->andWhere(['sesi_batch' => $sesi]);
    }

    // Susun ikut: batch > blok > aras > no_asrama (numeric ASC) > id
    $models = $query
        ->orderBy([
            'sesi_batch' => SORT_ASC,
            'a.blok'     => SORT_ASC,
            'a.aras'     => SORT_ASC,
            new \yii\db\Expression('CAST(a.no_asrama AS UNSIGNED) ASC'),
            'id'         => SORT_ASC,
        ])
        ->all();

    // --- 3) Sediakan spreadsheet ---
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Papar maklumat filter pada atas sheet
    $currentFilter = is_array($sesi) ? implode(', ', $sesi) : $sesi;
    $sheet->setCellValue('A1', 'Laporan Mengikut Sesi Batch: ' . $currentFilter);
    $sheet->mergeCells('A1:J1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

    // Header table pada row ke-3
    $headerRow = 3;
    $headers = [
        'A' => 'ID',
        'B' => 'Bilik Asrama',
        'C' => 'Nama Pemohon',
        'D' => 'Nombor Kad Pengenalan',
        'E' => 'No Tel',
        'F' => 'Alamat',
        'G' => 'E-mail',
        'H' => 'Kod Kursus',
        'I' => 'Sesi Batch',
        'J' => 'Status',
        'K' => 'Jantina',
    ];
    foreach ($headers as $col => $title) {
        $sheet->setCellValue("{$col}{$headerRow}", $title);
    }
    $sheet->getStyle("A{$headerRow}:J{$headerRow}")->getFont()->setBold(true);
    $sheet->freezePane('A' . ($headerRow + 1)); // freeze data row

    // --- 4) Isi data ---
    $row = $headerRow + 1;

    if (!empty($models)) {
        foreach ($models as $item) {
            $bilik = 'Tiada Bilik';
            if ($item->id_asrama && $item->asrama) {
                // Gabung terus tanpa simbol, contoh: B20312
                $bilik = trim(
                    (string)($item->asrama->blok ?? '') .
                    (string)($item->asrama->aras ?? '') .
                    (string)($item->asrama->no_asrama ?? '')
                );
            }

            $sheet->setCellValue('A' . $row, $item->id);
            $sheet->setCellValue('B' . $row, $bilik);
            $sheet->setCellValue('C' . $row, $item->user->nama ?? '');
            $sheet->setCellValue('D' . $row, $item->no_kp);
            $sheet->setCellValue('E' . $row, $item->no_tel);
            $sheet->setCellValue('F' . $row, $item->alamat);
            $sheet->setCellValue('G' . $row, $item->user->email ?? '');
            $sheet->setCellValue('H' . $row, $item->kod_kursus);
            $sheet->setCellValue('I' . $row, $item->sesi_batch); // nilai batch setiap rekod
            $sheet->setCellValue('J' . $row, ((int)$item->status === 0 ? 'Bujang' : 'Berkahwin'));
            $sheet->setCellValue('K' . $row, ((int)$item->jantina === 0 ? 'Lelaki' : 'Perempuan'));
            $row++;
        }
    } else {
        // Tiada rekod — letak 1 baris placeholder
        $sheet->setCellValue('A' . $row, 'Tiada rekod dijumpai untuk Sesi Batch dipilih.');
        $sheet->mergeCells("A{$row}:J{$row}");
    }

    // Auto-size kolum
    foreach (range('A', 'J') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // --- 5) Output download ---
    // Bersih output buffer untuk elak BOM/whitespace rosakkan fail
    if (function_exists('ob_get_length') && ob_get_length()) {
        @ob_end_clean();
    }

    $filename = 'Senarai_Tempahan_Asrama_Pelajar_' . date('Ymd_His') . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    exit;
}




    // public function actionExportExcel($sesi_batch = null)
    // {
    //     // 1. Siapkan Spreadsheet
    //     $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     // 2. Build Query dan tapis ikut sesi_batch
    //     $query = PelajarAsrama::find();
    //     if ($sesi_batch !== null) {
    //         $query->andWhere(['sesi_batch' => $sesi_batch]);
    //     }
    //     $data = $query->all(); // <-- gunakan data ini

    //     // 3. Set header kolum
    //     $headers = [
    //         'A' => 'ID',
    //         'B' => 'Bilik Asrama',
    //         'C' => 'Nama Pemohon',
    //         'D' => 'No Tel',
    //         'E' => 'Alamat',
    //         'F' => 'E-mail',
    //         'G' => 'Kod Kursus',
    //         'H' => 'Sesi Batch',
    //         'I' => 'Status',
    //         'J' => 'Jantina',
    //     ];
    //     foreach ($headers as $col => $title) {
    //         $sheet->setCellValue("{$col}1", $title);
    //     }

    //     // 4. Isi data baris demi baris
    //     $row = 2;
    //     foreach ($data as $item) {
    //         $sheet->setCellValue('A' . $row, $item->id);
    //         $sheet->setCellValue('B' . $row, 
    //             $item->id_asrama && $item->asrama 
    //                 ? $item->asrama->blok . $item->asrama->aras . $item->asrama->no_asrama 
    //                 : 'Tiada Bilik'
    //         );

    //         $sheet->setCellValue('C' . $row, $item->user->nama);
    //         $sheet->setCellValue('D' . $row, $item->no_tel);
    //         $sheet->setCellValue('E' . $row, $item->alamat);
    //         $sheet->setCellValue('F' . $row, $item->user->email);
    //         $sheet->setCellValue('G' . $row, $item->kod_kursus);
    //         $sheet->setCellValue('H' . $row, $item->sesi_batch);
    //         $sheet->setCellValue('I' . $row, $item->status == 1 ? 'Bujang' : 'Berkahwin');
    //         $sheet->setCellValue('J' . $row, $item->jantina == 1 ? 'Lelaki' : 'Perempuan');
    //         $row++;
    //     }

    //     // 5. Output untuk download
    //     $cleanBatch = $sesi_batch ? str_replace('/', '_', $sesi_batch) : 'SEMUA';
    //     $filename = 'Senarai_Tempahan_Asrama_Pelajar_' . $cleanBatch . '.xlsx';

    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment; filename="' . $filename . '"');
    //     header('Cache-Control: max-age=0');

    //     $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    //     $writer->save('php://output');
    //     exit;
    // }

    // public function actionSetBilik($id)
    // {
    //     Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    //     $model = PelajarAsrama::findOne($id);
    //     if (!$model) {
    //         return ['success' => false, 'message' => 'Tempahan tak dijumpai.'];
    //     }

    //     $bilikId = Yii::$app->request->post('id_asrama');

    //     if (!$bilikId) {
    //         return ['success' => false, 'message' => 'ID bilik tidak sah.'];
    //     }

    //     $validBilikIds = Asrama::find()
    //         ->select('id')
    //         // ->where(['jenis_asrama_id' => [4, 5], 'status_asrama' => 0])
    //         ->where([
    //         'penginap_kategori_id' => 1, // Untuk pelajar
    //         ])
    //         ->andWhere(['in', 'status_asrama', [0, 7]])
    //             ->column();

    //     if (!in_array($bilikId, $validBilikIds)) {
    //         return ['success' => false, 'message' => 'Bilik yang dipilih tidak sah atau tidak tersedia.'];
    //     }

    //     $model->id_asrama = $bilikId;

    //     if ($model->save()) {
    //         // Lepas berjaya save pelajar, update status bilik ke 'Diisi' (6)
    //         $bilik = Asrama::findOne($bilikId);
    //         if ($bilik) {
    //             $bilik->status_asrama = 6;
    //             $bilik->save(false); // Kalau nak elak validation
    //         }

    //         return ['success' => true];
    //     } else {
    //         return [
    //             'success' => false,
    //             'message' => 'Gagal simpan.',
    //             'errors' => $model->getErrors()
    //         ];
    //     }
    // }

    public function actionSetBilik($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        /* ---------- 1. Dapatkan rekod pelajar ---------- */
        $pelajar = PelajarAsrama::findOne($id);
        if (!$pelajar) {
            return ['success' => false, 'message' => 'Tempahan tak dijumpai.'];
        }

        /* ---------- 2. Validasi bilik ID ---------- */
        $bilikId = Yii::$app->request->post('id_asrama');
        if (!$bilikId) {
            return ['success' => false, 'message' => 'ID bilik tidak sah.'];
        }

        /* ---------- 3. Ambil rekod bilik & semak syarat asas ---------- */
        $bilik = Asrama::find()
            ->where([
                'id'                   => $bilikId,
                'penginap_kategori_id' => 1,          // khusus pelajar
            ])
            ->andWhere(['in', 'status_asrama', [0, 7]]) // kosong @ separa diisi
            ->one();

        if (!$bilik) {
            return ['success' => false, 'message' => 'Bilik bukan untuk pelajar atau tak tersedia.'];
        }

        /* ---------- 4. Kira penghuni semasa ---------- */
        $penghuniSemasa = PelajarAsrama::find()
            ->where(['id_asrama' => $bilikId])
            ->andWhere(['or',
                ['tarikh_keluar' => null],
                ['>', 'tarikh_keluar', date('Y-m-d')]
            ])
            ->count();

        if ($penghuniSemasa >= $bilik->kapasiti) {
            return ['success' => false, 'message' => 'Bilik telah penuh.'];
        }

        /* ---------- 5. Assign bilik kepada pelajar ---------- */
        $pelajar->id_asrama = $bilikId;

        if (!$pelajar->save()) {
            return [
                'success' => false,
                'message' => 'Gagal simpan.',
                'errors'  => $pelajar->getErrors(),
            ];
        }

        /* ---------- 6. Kemas kini status_asrama ---------- */
        $penghuniBaru = $penghuniSemasa + 1;

        if ($penghuniBaru >= $bilik->kapasiti) {
            $bilik->status_asrama = 6; // penuh
        } else {
            $bilik->status_asrama = 7; // separa diisi
        }
        $bilik->save(false);

        return ['success' => true];
    }


    public function actionUpdateTarikh()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $post = Yii::$app->request->post();
        if (!isset($post['id'], $post['field'], $post['value'])) {
            return ['success' => false, 'msg' => 'Parameter tak lengkap'];
        }

        $id = $post['id'];
        $field = $post['field'];
        $value = $post['value'];

        $model = PelajarAsrama::findOne($id);
        if ($model && in_array($field, ['tarikh_masuk', 'tarikh_keluar'])) {
            $model->$field = $value;
            if ($model->save(false)) {
                return ['success' => true, 'value' => $value];
            } else {
                return ['success' => false, 'msg' => 'Simpan gagal'];
            }
        }

        return ['success' => false, 'msg' => 'Model tak dijumpai'];
    }

   public function actionSetTarikhKeluar()
{
    Yii::$app->response->format = Response::FORMAT_JSON;

    try {
        $kodKursus = Yii::$app->request->post('kod_kursus');
        $tarikhKeluar = Yii::$app->request->post('tarikh_keluar');
        $sesiBatch = Yii::$app->request->post('sesi_batch');

        if (!$kodKursus || !$tarikhKeluar || !$sesiBatch) {
            return ['success' => false, 'message' => 'Maklumat tidak lengkap.'];
        }

        $pelajarList = PelajarAsrama::find()
            ->where(['sesi_batch' => $sesiBatch, 'kod_kursus' => $kodKursus])
            // ->andWhere(['tarikh_keluar' => null])
            ->all();

        $count = 0;
        foreach ($pelajarList as $pelajar) {
            $pelajar->tarikh_keluar = $tarikhKeluar;
            if (!$pelajar->save(false)) {
                Yii::error("Gagal save pelajar ID {$pelajar->id}: " . json_encode($pelajar->getErrors()));
            } else {
                $count++;
            }
        }

        if ($count > 0) {
            return ['success' => true, 'message' => "$count pelajar berjaya dikemaskini."];
        } else {
            return ['success' => false, 'message' => "Tiada pelajar yang perlu dikemaskini."];
        }
    } catch (\Throwable $e) {
        Yii::error("Error tarikh keluar: " . $e->getMessage(), __METHOD__);
        return ['success' => false, 'message' => 'Ralat server: ' . $e->getMessage()];
    }
}




    public function actionTerimaKasih()
    {
        return $this->render('terima-kasih');
    }

    public function actionImport()
    {
        $modelUpload = new \yii\base\DynamicModel(['excelFile']);
        $modelUpload->addRule('excelFile', 'file', ['extensions' => ['xlsx', 'xls'], 'skipOnEmpty' => false]);

        if (Yii::$app->request->isPost) {
            $modelUpload->excelFile = UploadedFile::getInstance($modelUpload, 'excelFile');
            if ($modelUpload->validate()) {
                $spreadsheet = IOFactory::load($modelUpload->excelFile->tempName);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();

                foreach ($rows as $i => $row) {
                    if ($i == 0) continue; // skip header

                    [$nama, $no_kp, $jantina, $no_tel, $sesi_batch, $no_bilik, $kod_kursus] = $row;

                    // Cipta user
                    $user = new User();
                    $user->nama = $nama;
                    $user->email = 'pelajar_' . $no_kp . '@dummy.local';
                    $user->setPassword('default123');
                    $user->authKey = Yii::$app->security->generateRandomString();
                    $user->accessToken = Yii::$app->security->generateRandomString();
                    $user->verification_token = Yii::$app->security->generateRandomString();
                    $user->role = 5; // pelajar
                    $user->status = 1; // aktif
                    $user->save(false);

                    // Cari bilik
                    $asrama = Asrama::find()->where(['id' => $no_bilik])->one();

                    if (!$asrama) continue;

                    // Masukkan data pelajar_asrama
                    $pelajar = new PelajarAsrama();
                    $pelajar->user_id = $user->id;
                    $pelajar->no_kp = $no_kp;
                    $pelajar->jantina = $jantina;
                    $pelajar->no_tel = $no_tel;
                    $pelajar->sesi_batch = $sesi_batch;
                    $pelajar->id_asrama = $asrama->id;
                    $pelajar->kod_kursus = $kod_kursus;
                    $pelajar->save(false);
                }

                Yii::$app->session->setFlash('success', 'Import selesai!');
                return $this->redirect(['index']);
            }
        }

        return $this->render('import', ['modelUpload' => $modelUpload]);
    }



}
