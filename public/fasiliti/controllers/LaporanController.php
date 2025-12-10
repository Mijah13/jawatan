<?php

namespace app\controllers;

use app\models\TempahFasiliti;
use app\models\Fasiliti;
use app\models\TempahAsrama;
use app\models\Asrama;
use app\models\PenginapKategori;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use Yii;

class LaporanController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'], // Specify actions requiring login
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'], // Allow authenticated users only
                    ],
                ],
                'denyCallback' => function () {
                    return Yii::$app->response->redirect(['site/login']);
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

    public function actionIndex($reportType = null, $year = null, $startDate = null, $endDate = null)
    {
        // Retrieve selected years for both charts
        $year_chart1 = Yii::$app->request->get('year_chart1', date('Y')); // Default to current year
        $year_chart2 = Yii::$app->request->get('year_chart2', date('Y')); // Default to current year
    
        // Fetch statistics data based on selected years
        $facilityStats = TempahFasiliti::getBookingStats($year_chart1);
        $asramaStats = TempahAsrama::getHostelResidentStats($year_chart2);
    
        // Prepare report data
        $data = [];
        $view = 'index'; // Default view

        // Calculate total male and female residents
        $totalMale = array_sum(array_column($asramaStats, 'total_male'));
        $totalFemale = array_sum(array_column($asramaStats, 'total_female'));
    
        switch ($reportType) {
            case 'tempahan-bulanan':
                $data = TempahFasiliti::getMonthlyReport($month, $year_chart1);
                $view = 'laporan-tempahan-bulanan';
                break;
    
            case 'laporan-tahunan':
                $data = TempahFasiliti::getAnnualReport($year_chart1);
                $view = 'laporan-tahunan';
                break;
    
            case 'status-fasiliti':
                $data = TempahFasiliti::getStatusReport($startDate, $endDate);
                $view = 'laporan-status-fasiliti';
                break;

            case 'status-asrama':
                $data = TempahAsrama::getStatusReport($startDate, $endDate);
                $view = 'laporan-status-asrama';
                break;
    
            case 'penghuni-penghuni-asrama':
                $data = TempahAsrama::getHostelOccupancy($month, $year_chart2);
                $view = 'laporan-penghuni-asrama';
                break;
    
            default:
                break;
        }
    
        // Render the index view with separate years for both charts
        return $this->render('index', [
            'facilityStats' => $facilityStats,
            'asramaStats' => $asramaStats,
            'data' => $data,
            'reportType' => $reportType,
            'year_chart1' => $year_chart1,
            'year_chart2' => $year_chart2,
            'totalMale' => $totalMale,
            'totalFemale' => $totalFemale,
        ]);
    }

    public function actionLaporanPenghuniAsrama($month = null, $year = null)
    {
        $year = $year ?? date('Y');
        $month = $month ?? date('m');

        $data = TempahAsrama::getHostelOccupancy($month, $year);

        return $this->render('laporan-penghuni-asrama', [
            'data' => $data,
            'month' => $month,
            'year' => $year,
        ]);
    }

    public function actionLaporanStatusFasiliti($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?? date('Y-m-01');
        $endDate = $endDate ?? date('Y-m-t');

        $data = TempahFasiliti::getStatusReport($startDate, $endDate);

        return $this->render('laporan-status-fasiliti', [
            'data' => $data,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }


    public function actionLaporanStatusAsrama($month = null, $year = null)
    {
        $year = $year ?? date('Y');
        $month = $month ?? date('m');

        $data = TempahAsrama::getHostelOccupancy($month, $year);

        return $this->render('laporan-status-asrama', [
            'data' => $data,
            'month' => $month,
            'year' => $year,
        ]);
    }


    public function actionLaporanTempahanTahunan($year = null)
    {
        $request = Yii::$app->request;
        $year = $request->get('year', date('Y'));

        // Get data for both hostel and facility bookings
        $asramaData = TempahAsrama::getYearlyHostelBookings($year);
        $fasilitiData = TempahFasiliti::getYearlyFacilityBookings($year);

        $viewType = Yii::$app->request->get('viewType', 'tahunan');

        return $this->render('laporan-tempahan-tahunan', [
            'asramaData' => $asramaData,
            'fasilitiData' => $fasilitiData,
            'year' => $year,
            'viewType' => $viewType,
        ]);
    }

    public function actionLaporanTempahanBulanan()
    {
        $request = Yii::$app->request;
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));

        // Get data for both hostel and facility bookings
        $asramaData = TempahAsrama::getMonthlyHostelBookings($month, $year);
        $fasilitiData = TempahFasiliti::getMonthlyFacilityBookings($month, $year);

        $viewType = Yii::$app->request->get('viewType', 'bulanan');

        return $this->render('laporan-tempahan-bulanan', [
            'asramaData' => $asramaData,
            'fasilitiData' => $fasilitiData,
            'month' => $month,
            'year' => $year,
            'viewType' => $viewType,
        ]);
    }

    public function actionGetEvents($filter = 'all')
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            $events = [];
            $currentYear = date('Y');

            // =============================
            // FASILITI EVENTS
            // =============================

            if ($filter === 'all' || (strpos($filter, 'f-') === 0 && $filter !== 'f-13')) {

                $fasilitiQuery = TempahFasiliti::find()
                    ->where(['!=', 'status_tempahan_adminKemudahan', 4])
                    ->andWhere(['!=', 'fasiliti_id', 13])
                    ->andWhere(['between', 'tarikh_masuk', "$currentYear-01-01", "$currentYear-12-31"]);

                if (strpos($filter, 'f-') === 0) {
                    $fasilitiId = str_replace('f-', '', $filter);
                    $fasilitiQuery->andWhere(['fasiliti_id' => $fasilitiId]);
                }

                $fasilitiBookings = $fasilitiQuery->all();

                foreach ($fasilitiBookings as $booking) {
                    if ($booking->tarikh_masuk && $booking->tarikh_keluar) {

                        $gelanggangIds = [15, 16, 17, 18]; // id gelanggang
                        $warnaGelanggang = '#46a4cf'; // Biru
                        $warnaFasiliti = '#63b89e';   // Hijau
                        // $warnaAsrama = '#8e24aa';     // Ungu (kalau nak asingkan dalam list lain)

                        $color = in_array($booking->fasiliti_id, $gelanggangIds) 
                            ? $warnaGelanggang 
                            : $warnaFasiliti;
                            
                        $events[] = [
                            'id' => 'fasiliti-' . $booking->id,
                            // 'title' => $booking->fasiliti->nama_fasiliti . ' - ' . $booking->tujuan,
                            'title' => $booking->fasiliti->nama_fasiliti . ' - ' . $booking->tujuan . ' - ' . ($booking->user->nama ?? 'Tanpa Nama'),

                            'start' => Yii::$app->formatter->asDate($booking->tarikh_masuk, 'php:Y-m-d'),
                            'end' => date('Y-m-d', strtotime($booking->tarikh_keluar . ' +1 day')),
                            'color' => $color,
                            'category' => 'fasiliti',
                            'subtype' => $booking->fasiliti->nama_fasiliti,
                            'session' => $booking->tempoh,
                        ];
                    }
                }
            }

            // =============================
            // ASRAMA EVENTS
            // =============================

            if ($filter === 'all' || strpos($filter, 'a-') === 0) {
                $asramaQuery = TempahAsrama::find()
                    ->where(['!=', 'status_tempahan_adminKemudahan', 4])
                    ->andWhere(['between', 'tarikh_masuk', "$currentYear-01-01", "$currentYear-12-31"])
                    ->andWhere(['!=', 'jenis_bilik', 5]); // terus exclude jenis_bilik = 5

                if (strpos($filter, 'a-') === 0) {
                    $jenisBilikId = str_replace('a-', '', $filter);
                    $asramaQuery->andWhere(['jenis_bilik' => $jenisBilikId]);
                }

                $asramaBookings = $asramaQuery->all();

                foreach ($asramaBookings as $booking) {
                    if ($booking->tarikh_masuk && $booking->tarikh_keluar) {
                        $events[] = [
                            'id' => 'asrama-' . $booking->id,
                            'title' => $booking->jenisBilik->jenis_bilik . ' - ' . $booking->tujuan . ' - ' . ($booking->user->nama ?? 'Tanpa Nama'),
                            'start' => Yii::$app->formatter->asDate($booking->tarikh_masuk, 'php:Y-m-d'),
                            'end' => date('Y-m-d', strtotime($booking->tarikh_keluar . ' +1 day')),
                            'color' => '#913499', // Warna asrama (ungu)
                            'category' => 'asrama',
                            'subtype' => $booking->jenisBilik->jenis_bilik,
                        ];
                    }
                }
            }

            return $events;
                    } catch (\Exception $e) {
                        \Yii::error('Calendar error: ' . $e->getMessage());
                        return ['error' => $e->getMessage()];
                    }
                }


}
