<?php

namespace app\models;
use DateTime;
use Yii;

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "tempah_fasiliti".
 *
 * @property int $id
 * @property int|null $fasiliti_id
 * @property int $user_id
 * @property string $no_kp_pemohon
 * @property string $agensi_pemohon
 * @property string $tujuan
 * @property string $tarikh_masuk
 * @property string $tarikh_keluar
 * @property string $no_tel
 * @property string $alamat
 * @property string $email
 * @property int|null $tempoh
 * @property int|null $jangkaan_hadirin
 * @property resource|null $surat_sokongan
 * @property int|null $disokong_oleh
 * @property int|null $status_tempahan_adminKemudahan 0 = belum disemak, 1 = sedang diproses, 2 = Menunggu bayaran, 3 = Diluluskan, 4 = dibatalkan
 * @property string|null $alasan_batal
 * @property int|null $dibatalkan_oleh
 * @property int $status_pembayaran 0 = Belum disemak, 1 = Tidak Diperlukan, 2 = Diperlukan, 3 =Telah Dibayar
 * @property string|null $slip_pembayaran
 * @property string|null $tarikh_upload_slip
 * @property int|null $diluluskan_oleh
 * @property int|null $status_tempahan_pelulus 0 = belum disemak, 1 = sedang diproses, 2 = lulus, 3 = batal
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $is_simpanan 
 * @property int $invois_dijana
 * @property string|null $tarikh_invois_dijana
 * @property string|null $no_resit 
 * @property int|null $disahkanBayaran_oleh 
 *
 * @property User $diluluskanOleh
 * @property User $disokongOleh
 * @property User $disahkanBayaranOleh 
 * @property Fasiliti $fasiliti 
 * @property User $user
 */
class TempahFasiliti extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tempah_fasiliti';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fasiliti_id', 'jangkaan_hadirin', 'surat_sokongan', 'disokong_oleh', 'status_tempahan_adminKemudahan', 'alasan_batal', 'dibatalkan_oleh', 'slip_pembayaran', 'tarikh_upload_slip', 'diluluskan_oleh', 'status_tempahan_pelulus', 'tarikh_invois_dijana', 'no_resit', 'disahkanBayaran_oleh'], 'default', 'value' => null], 
            [['fasiliti_id', 'user_id', 'jangkaan_hadirin', 'disokong_oleh', 'status_tempahan_adminKemudahan', 'dibatalkan_oleh', 'status_pembayaran', 'diluluskan_oleh', 'status_tempahan_pelulus', 'is_simpanan', 'invois_dijana', 'disahkanBayaran_oleh'], 'integer'],
            [['user_id', 'no_kp_pemohon', 'agensi_pemohon', 'tujuan', 'tarikh_masuk', 'tarikh_keluar', 'no_tel', 'alamat', 'email', 'tempoh', 'jangkaan_hadirin'], 'required'],
            [['invois_dijana'], 'default', 'value' => 0],
            [['tarikh_masuk', 'tarikh_keluar', 'tarikh_upload_slip', 'created_at', 'updated_at', 'tarikh_invois_dijana'], 'safe'],
            [['surat_sokongan', 'alasan_batal'], 'string'],
            [['jangkaan_hadirin'], 'integer', 'min' => 0, 'tooSmall' => 'Jangkaan hadirin tidak boleh negatif.'],
            [['no_kp_pemohon', 'agensi_pemohon', 'tujuan', 'no_tel', 'alamat', 'email', 'slip_pembayaran'], 'string', 'max' => 255],
            ['no_kp_pemohon', 'match', 'pattern' => '/^\d{12}$/', 'message' => 'Nombor kad pengenalan mesti 12 digit tanpa (-).'],
            ['no_tel', 'match', 'pattern' => '/^\d{10,11}$/', 'message' => 'Nombor telefon mesti tidak melebihi 11 digit tanpa (-).'],
            [['no_resit'], 'string', 'max' => 20],
            [['disahkanBayaran_oleh'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['disahkanBayaran_oleh' => 'id']],
            [['diluluskan_oleh'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['diluluskan_oleh' => 'id']],
            [['disokong_oleh'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['disokong_oleh' => 'id']],
            [['fasiliti_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fasiliti::class, 'targetAttribute' => ['fasiliti_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['dibatalkan_oleh'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['dibatalkan_oleh' => 'id']], 
            [['surat_sokongan', 'slip_pembayaran'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpeg, jpg, doc, docx, pdf', 'maxSize' => 1024 * 1024 * 5], 
            [['alasan_batal'], 'required', 'on' => 'batal'],
            

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fasiliti_id' => 'Fasiliti ID',
            'user_id' => 'User ID',
            'no_kp_pemohon' => 'No Kp Pemohon',
            'agensi_pemohon' => 'Agensi Pemohon',
            'tujuan' => 'Tujuan',
            'tarikh_masuk' => 'Tarikh Masuk',
            'tarikh_keluar' => 'Tarikh Keluar',
            'no_tel' => 'No Tel',
            'alamat' => 'Alamat Pemohon',
            'email' => 'Email',
            'tempoh' => 'Tempoh',
            'jangkaan_hadirin' => 'Bilangan Pengguna',
            'surat_sokongan' => 'Surat Sokongan',
            'disokong_oleh' => 'Disokong Oleh',
            'status_tempahan_adminKemudahan' => 'Status Tempahan Admin Kemudahan',
            'alasan_batal' => 'Alasan Batal',
            'dibatalkan_oleh' => 'Dibatalkan Oleh', 
            'status_pembayaran' => 'Status Pembayaran',
            'slip_pembayaran' => 'Slip Pembayaran', 
		    'tarikh_upload_slip' => 'Tarikh Upload Slip', 
            'diluluskan_oleh' => 'Diluluskan Oleh',
            'status_tempahan_pelulus' => 'Status Tempahan Pelulus',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_simpanan' => 'Is Simpanan', 
            'invois_dijana' => 'Invois Dijana',
		    'tarikh_invois_dijana' => 'Tarikh Invois Dijana',
            'no_resit' => 'No Resit',
            'disahkanBayaran_oleh' => 'Disahkan Bayaran Oleh',
            'no_plate' => 'No Plate',
        ];
    }
 
    /**
     * Gets query for [[Fasiliti]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFasiliti()
    {
        return $this->hasOne(Fasiliti::class, ['id' => 'fasiliti_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
    * Gets query for [[DiluluskanOleh]].
    *
    * @return \yii\db\ActiveQuery
    */
    public function getDiluluskanOleh()
    {
        return $this->hasOne(User::class, ['id' => 'diluluskan_oleh']);
    }
    
    /**
     * Gets query for [[DisokongOleh]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDisokongOleh()
    {
        return $this->hasOne(User::class, ['id' => 'disokong_oleh']);
    }

     /**
    * Gets query for [[DisahkanBayaranOleh]].
    *
    * @return \yii\db\ActiveQuery
    */
    public function getDisahkanBayaranOleh()
    {
        return $this->hasOne(User::class, ['id' => 'disahkanBayaran_oleh']);
    }

    /**
    * Gets query for [[DibatalkanOleh]].
    *
    * @return \yii\db\ActiveQuery
    */
    public function getDibatalkanOleh()
    {
        return $this->hasOne(User::class, ['id' => 'dibatalkan_oleh']);
    }
		

    public static function getSenaraiTempoh()
    {
        return [
            1 => 'Sesi pagi : 9am - 12pm',
            2 => 'Sesi petang : 2pm - 5pm',
            3 => 'Sesi malam : 8pm - 11pm',
            4 => 'Sesi Pagi - Petang',
            5 => 'Satu Hari',
        ];
    }

    public function getTempohLabel()
    {
        $list = self::getSenaraiTempoh();
        return $list[$this->tempoh] ?? 'Tidak Dikenal Pasti';
    }


 
    public function calculateJumlah()
    {
        // Assume you have the relevant facility rates and other details
        $rate = 0;

        if ($this->fasiliti) {
            switch ($this->tempoh) {
                case 1: // sesiPagi
                case 2: // sesiPetang
                case 4: // sesiPagiPetang
                    // Check which rate is available for day sessions
                    $rate = $this->fasiliti->kadar_sewa_perJamSiang ?? $this->fasiliti->kadar_sewa_perJam ?? 0;
                    break;
        
                case 3: // sesiMalam
                    // Use night rate
                    $rate = $this->fasiliti->kadar_sewa_perJamMalam ?? $this->fasiliti->kadar_sewa_perJam ?? 0;
                    break;
        
                case 5: // satuHari
                    // Kalau fasiliti ada rate satuHari guna tu
                    if (!empty($this->fasiliti->kadar_sewa_perHari)) {
                        $rate = $this->fasiliti->kadar_sewa_perHari;
                    } else {
                        // fallback ke kadar sesi pagi+petang
                        $rate = $this->fasiliti->kadar_sewa_perJamSiang ?? $this->fasiliti->kadar_sewa_perJam ?? 0;
                        $durations = 6; // pagi + petang = 6 jam
                    }
                    break;
        
                default:
                    $rate = 0; // Fallback if the period is not recognized
            }
        }
        
        // Calculate the amount based on the session type and duration
        if ($this->tempoh === 5) {
            $date1 = new DateTime($this->tarikh_masuk);
            $date2 = new DateTime($this->tarikh_keluar);
            $days = $date1->diff($date2)->days;
            $days = $days > 0 ? $days : 1;
            
            
            if (!empty($this->fasiliti->kadar_sewa_perHari)) {
                // Ada rate satu hari
                return $rate * $days;
            } else {
                // Tak ada rate satu hari → fallback kira ikut sesi pagi+petang
                $rate = $this->fasiliti->kadar_sewa_perJamSiang ?? $this->fasiliti->kadar_sewa_perJam ?? 0;
                $hours = 6; // sesi pagi + petang
                return $rate * $hours * $days;
            }
        }

        $durations = [
            1 => 3, // sesiPagi
            2 => 3, // sesiPetang
            3 => 3, // sesiMalam
            4 => 6, // sesiPagiPetang
        ];

        return $rate * ($durations[$this->tempoh] ?? 0);
    }

    public function getCardActions()
    {
        $buttons = [];

        // Copy-paste satu-satu button logic dari 'buttons' array tadi:

        // 1. Bayar
        $showBayarButton = (
            $this->status_tempahan_adminKemudahan == 2 &&
            $this->status_pembayaran == 2 &&
            $this->status_tempahan_pelulus == 2
        );
        if ($showBayarButton) {
            $buttons[] = Html::a('<i class="bi bi-cash-stack"></i> Bayar',
                'https://ipayment.anm.gov.my/',
                [
                    'class' => 'btn btn-sm btn-success me-1',
                    'target' => '_blank',
                    'title' => 'Teruskan ke pembayaran',
                ]
            );
        }

        // 2. Upload Slip
        $bolehUpload = $showBayarButton;
        if ($bolehUpload) {
            $uploadUrl = Url::to(['tempah-fasiliti/upload-slip', 'id' => $this->id]);
            $csrf = Yii::$app->request->getCsrfToken();

            if (!empty($this->slip_pembayaran)) {
                $slipUrl = Yii::getAlias('@web') . '/uploads/' . $this->slip_pembayaran;
                $buttons[] = Html::a('<i class="bi bi-eye"></i> Lihat Slip', $slipUrl, [
                    'class' => 'btn btn-sm btn-success me-1',
                    'target' => '_blank',
                    'title' => 'Lihat Slip Pembayaran',
                ]);
            } else {
                $buttons[] = <<<HTML
                    <form action="{$uploadUrl}" method="post" enctype="multipart/form-data" class="upload-slip-form d-inline">
                        <input type="hidden" name="_csrf" value="{$csrf}">
                        <input type="file" name="TempahFasiliti[slip_pembayaran]" class="d-none slip-input" onchange="this.form.submit()">
                        <button type="button" class="btn btn-sm btn-warning btn-upload-slip me-1">
                            <i class="bi bi-upload"></i> Upload Slip
                        </button>
                    </form>
                HTML;
            }
        }

        // 3. Delete
        $disabled = in_array($this->status_tempahan_adminKemudahan, [3, 4, 5]);
        $buttons[] = Html::a('<i class="bi bi-trash"></i>', ['tempah-fasiliti/delete', 'id' => $this->id], [
            'class' => 'btn btn-sm btn-danger me-1' . ($disabled ? ' disabled' : ''),
            'data-confirm' => $disabled ? false : 'Adakah anda pasti untuk memadam tempahan ini?',
            'data-method' => 'post',
            'tabindex' => $disabled ? '-1' : '0',
        ]);

        // 4. Pengesahan
        if (!in_array($this->status_tempahan_adminKemudahan, [1, 2, 3, 4, 5])) {
            $buttons[] = Html::a('<i class="bi bi-check"></i> Hantar', ['send-email', 'id' => $this->id], [
                'class' => 'btn btn-sm btn-primary me-1',
                'title' => 'Hantar Pengesahan',
            ]);
        }

        return implode("\n", $buttons);
    }

    
    public static function getMonthlyReport($month, $year) 
    { 
        return self::find() 
            ->select(['fasiliti.nama_fasiliti', 'COUNT(*) AS total_bookings', 'SUM(jumlah_bayaran) AS total_revenue']) 
            ->joinWith('fasiliti') 
            ->where(['MONTH(tarikh_masuk)' => $month, 'YEAR(tarikh_masuk)' => $year]) 
            ->groupBy('fasiliti.id') 
            ->asArray() 
            ->all(); 
    } 
		 
    public static function getYearlyFacilityBookings($year)
    {
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[$m] = static::find()
                ->where(['YEAR(tarikh_masuk)' => $year, 'MONTH(tarikh_masuk)' => $m])
                ->count();
        }
        return $monthlyData;
    }
    //statistic kat laporan/index
    public static function getBookingStats($year = null)
    {
        // Default to current year if no year is provided
        if ($year === null) {
            $year = date('Y'); // Get the current year dynamically
        }

        return (new \yii\db\Query())
            ->select([
                'fasiliti.nama_fasiliti AS jenis_fasiliti',
                // 'MONTH(tempah_fasiliti.tarikh_masuk) AS bulan', // Include month
                'COUNT(tempah_fasiliti.id) AS total_bookings' // Count bookings per month
            ])
            ->from('fasiliti')
            ->leftJoin(
                'tempah_fasiliti', 
                'fasiliti.id = tempah_fasiliti.fasiliti_id 
                AND YEAR(tempah_fasiliti.tarikh_masuk) = :year', 
                [':year' => $year]
            )
            ->where(['!=', 'fasiliti.id', 13]) // Exclude fasiliti_id = 13
            ->groupBy(['fasiliti.id']) // Group by facility and month
            // ->groupBy(['fasiliti.id', 'bulan']) // Group by facility and month
            // ->orderBy(['bulan' => SORT_ASC]) // Sort by month for structured data
            ->orderBy(['fasiliti.id' => SORT_ASC]) // Sort by month for structured data
            ->all();
    }


        
    public static function getStatusReport($startDate, $endDate) 
    { 
        return self::find() 
            ->select(['fasiliti.nama_fasiliti', 'tarikh_masuk', 'tarikh_keluar', 'fasiliti_status']) 
            ->joinWith('fasiliti') 
            ->where(['between', 'tarikh_masuk', $startDate, $endDate]) 
            ->asArray() 
            ->all(); 
    } 

    public static function getMonthlyFacilityBookings($month, $year)
    {
        return static::find()
            ->select([
                'fasiliti.nama_fasiliti',
                'COUNT(tempah_fasiliti.id) AS total_bookings'
            ])
            ->joinWith(['fasiliti'])
            ->where([
                'MONTH(tarikh_masuk)' => $month,
                'YEAR(tarikh_masuk)' => $year
            ])
            ->groupBy('fasiliti.nama_fasiliti')
            ->asArray()
            ->all();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Pastikan fasiliti_id wujud
        if ($this->fasiliti_id) {
            $fasiliti = Fasiliti::findOne($this->fasiliti_id);

             if ($fasiliti && $this->is_simpanan == 1) {
            // Kalau simpanan, terus tukar ke status 2
             $fasiliti->fasiliti_status = 1; //simpanan
             $fasiliti->save(false);
        }


        }
    }


    public static function releaseExpiredReservations()
    {
        $today = date('Y-m-d');

        // Cari semua tempahan simpanan yang dah tamat tempoh
        $expiredReservations = self::find()
            ->where(['is_simpanan' => 1])
            ->andWhere(['<', 'tarikh_keluar', $today])
            ->all();

        foreach ($expiredReservations as $reservation) {
            $fasiliti = Fasiliti::findOne($reservation->fasiliti_id);
            if ($fasiliti) {
                $fasiliti->fasiliti_status = 0;
                $fasiliti->save(false);
            }

            // Optional: Set is_simpanan ke 0 supaya tempahan tak trigger lagi
            $reservation->is_simpanan = 0;
            $reservation->save(false);
        }
    }
		 
		
}
