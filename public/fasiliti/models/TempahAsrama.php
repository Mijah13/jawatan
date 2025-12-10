<?php

namespace app\models;
use app\models\Expression;
use Yii;

/**
 * This is the model class for table "tempah_asrama".
 *
 * @property int $id
 * @property int|null $id_asrama
 * @property int $user_id
 * @property int $jenis_penginap
 * @property string|null $no_kp_pemohon
 * @property string|null $agensi_pemohon
 * @property string|null $tujuan
 * @property string|null $tarikh_masuk
 * @property string|null $tarikh_keluar
 * @property string|null $tarikh_pembersihan
 * @property string|null $no_tel
 * @property string|null $alamat
 * @property string|null $email
 * @property int|null $jenis_bilik
 * @property resource|null $surat_sokongan
 * @property int|null $jantina
 * @property string|null $nama_penginap_1
 * @property string|null $no_kp_penginap_1 
 * @property string|null $email_penginap_1
 * @property string|null $no_tel_penginap_1
 * @property string|null $alamat_penginap_1
 * @property string|null $nama_penginap_2
 * @property string|null $no_kp_penginap_2
 * @property string|null $email_penginap_2
 * @property string|null $no_tel_penginap_2
 * @property string|null $alamat_penginap_2
 * @property int|null $disokong_oleh
 * @property int|null $status_tempahan_adminKemudahan 0 = belum disemak, 1 = sedang diproses, 2 = menunggu bayaran, 3 = diluluskan, 4 = dibatalkan   
 * @property string|null $alasan_batal
 * @property int|null $dibatalkan_oleh
 * @property int|null $status_pembayaran 0 = Belum disemak, 1 = Tidak Berbayar, 2 = Berbayar, 3 = Telah Dibayar
 * @property string|null $slip_pembayaran
 * @property string|null $tarikh_upload_slip
 * @property int|null $diluluskan_oleh
 * @property int|null $status_tempahan_pelulus 0 = belum disemak, 1 = sedang diproses, 2 = lulus, 3 = batal
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $is_simpanan 
 * @property float|null $diskaun
 * @property int $pengiraan_bayaran
 * @property int $invois_dijana 
 * @property string|null $tarikh_invois_dijana 
 * @property string|null $no_resit
 * @property int|null $disahkanBayaran_oleh 
 * @property string|null $no_plate
 *
 * @property Asrama $asrama
 * @property AsramaStatusLog[] $asramaStatusLogs 
 * @property User $diluluskanOleh
 * @property User $disokongOleh
 * @property User $dibatalkanOleh
 * @property User $disahkanBayaranOleh 
 * @property JenisAsrama $jenisBilik
 * @property User $user
 */
class TempahAsrama extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tempah_asrama';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'jenis_penginap', 'agensi_pemohon', 'tujuan', 'tarikh_masuk', 'tarikh_keluar', 'jantina'], 'required'],
            [['id_asrama', 'no_kp_pemohon', 'agensi_pemohon', 'tujuan', 'tarikh_masuk', 'tarikh_keluar', 'tarikh_pembersihan', 'no_tel', 'alamat', 'email', 'jenis_bilik', 'surat_sokongan', 'jantina', 'nama_penginap_1', 'no_kp_penginap_1', 'email_penginap_1', 'no_tel_penginap_1', 'alamat_penginap_1', 'nama_penginap_2', 'no_kp_penginap_2', 'email_penginap_2', 'no_tel_penginap_2', 'alamat_penginap_2', 'disokong_oleh', 'alasan_batal', 'slip_pembayaran', 'tarikh_upload_slip', 'diluluskan_oleh', 'status_tempahan_pelulus', 'diskaun', 'no_resit', 'disahkanBayaran_oleh', 'no_plate'], 'default', 'value' => null], 
            [['id_asrama', 'user_id', 'jenis_penginap', 'jenis_bilik', 'jantina', 'disokong_oleh', 'status_tempahan_adminKemudahan', 'dibatalkan_oleh', 'status_pembayaran', 'diluluskan_oleh', 'status_tempahan_pelulus', 'is_simpanan', 'pengiraan_bayaran', 'invois_dijana', 'disahkanBayaran_oleh'], 'integer'], 
            [['tarikh_masuk', 'tarikh_keluar', 'tarikh_pembersihan', 'tarikh_upload_slip', 'created_at', 'updated_at', 'tarikh_invois_dijana'], 'safe'],
            [['surat_sokongan', 'alasan_batal'], 'string'],
            ['no_kp_pemohon', 'match', 'pattern' => '/^\d{12}$/', 'message' => 'Nombor kad pengenalan mesti 12 digit tanpa (-).'],
            ['no_tel', 'match', 'pattern' => '/^\d{10,11}$/', 'message' => 'Nombor telefon mesti tidak melebihi 11 digit tanpa (-).'],
            [['no_kp_pemohon', 'agensi_pemohon', 'tujuan', 'no_tel', 'alamat', 'email', 'nama_penginap_1', 'no_kp_penginap_1', 'email_penginap_1', 'no_tel_penginap_1', 'alamat_penginap_1', 'nama_penginap_2', 'no_kp_penginap_2', 'email_penginap_2', 'no_tel_penginap_2', 'alamat_penginap_2', 'slip_pembayaran'], 'string', 'max' => 255],
            [['is_simpanan', 'pengiraan_bayaran'], 'default', 'value' => 0], 
            [['invois_dijana'], 'default', 'value' => 0],
            [['diskaun'], 'number'],
            [['no_resit'], 'string', 'max' => 20],
            [['id_asrama'], 'required', 'on' => 'update'],
            [['id_asrama'], 'exist', 'skipOnError' => true, 'targetClass' => Asrama::class, 'targetAttribute' => ['id_asrama' => 'id']],
            [['jenis_bilik'], 'exist', 'skipOnError' => true, 'targetClass' => JenisAsrama::class, 'targetAttribute' => ['jenis_bilik' => 'id']],
            [['diluluskan_oleh'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['diluluskan_oleh' => 'id']],
            [['disokong_oleh'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['disokong_oleh' => 'id']],
            [['disahkanBayaran_oleh'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['disahkanBayaran_oleh' => 'id']], 
            [['dibatalkan_oleh'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['dibatalkan_oleh' => 'id']], 
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['surat_sokongan', 'slip_pembayaran'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpeg, jpg, doc, docx, pdf', 'maxSize' => 1024 * 1024 * 5], 
            [['alasan_batal'], 'required', 'on' => 'batal'], 

            [['no_plate'], 'string', 'max' => 50],

        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_asrama' => 'Id Asrama',
            'user_id' => 'User ID',
            'jenis_penginap' => 'Jenis Penginap',
            'no_kp_pemohon' => 'No. Kad Pengenalan',
            'agensi_pemohon' => 'Agensi Pemohon',
            'tujuan' => 'Tujuan',
            'tarikh_masuk' => 'Tarikh Masuk',
            'tarikh_keluar' => 'Tarikh Keluar',
            'tarikh_pembersihan' => 'Tarikh Pembersihan',
            'no_tel' => 'No. Tel',
            'alamat' => 'Alamat Pemohon',
            'email' => 'Email',
            'jenis_bilik' => 'Jenis Bilik',
            'surat_sokongan' => 'Surat Sokongan',
            'jantina' => 'Jantina',
            'nama_penginap_1' => 'Nama Penginap 1',
            'no_kp_penginap_1' => 'No. Kad Pengenalan Penginap 1', 
            'email_penginap_1' => 'Email Penginap 1',
            'no_tel_penginap_1' => 'No Tel Penginap 1',
            'alamat_penginap_1' => 'Alamat Penginap 1',
            'nama_penginap_2' => 'Nama Penginap 2',
            'no_kp_penginap_2' => 'No Kp Penginap 2', 
            'email_penginap_2' => 'Email Penginap 2',
            'no_tel_penginap_2' => 'No Tel Penginap 2',
            'alamat_penginap_2' => 'Alamat Penginap 2',
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
            'diskaun' => 'Diskaun',
            'pengiraan_bayaran' => 'Pengiraan Bayaran',
            'invois_dijana' => 'Invois Dijana', 
            'tarikh_invois_dijana' => 'Tarikh Invois Dijana', 
            'no_resit' => 'No Resit',
            'disahkanBayaran_oleh' => 'Disahkan Bayaran Oleh', 
            'no_plate' => 'No Plate',
        ];
    }

    /**
     * Gets query for [[Asrama]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAsrama()
    {
        return $this->hasOne(Asrama::class, ['id' => 'id_asrama']);
    }

     
    public function getAsramaStatusLogs()
    {
        return $this->hasMany(AsramaStatusLog::class, ['tempah_asrama_id' => 'id']);
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
		

    /**
     * Gets query for [[JenisBilik]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenisBilik()
    {
        return $this->hasOne(JenisAsrama::class, ['id' => 'jenis_bilik']);
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
    

    public function getStatusLabel()
    {
        $statuses = [
            0 => 'Belum Disemak',
            1 => 'Sedang Diproses',
            2 => 'Menunggu bayaran',
            3 => 'Diluluskan',
            4 => 'Dibatalkan',
        ];
        return $statuses[$this->status_tempahan_adminKemudahan] ?? 'Tidak diketahui';
    }

    //Laporan Penghuni Asrama
    public static function getHostelOccupancy($month, $year)
    {
        return self::find()
            ->select(['jenis_asrama.jenis_bilik', 'COUNT(*) AS total_occupants'])
            ->joinWith('jenisBilik')
            ->andWhere(new \yii\db\Expression("MONTH(tarikh_masuk) = :month"), [':month' => $month])
            ->andWhere(new \yii\db\Expression("YEAR(tarikh_masuk) = :year"), [':year' => $year])
            ->groupBy('jenis_asrama.id')
            ->asArray()
            ->all();
    }

    //Statistik Penghuni Asrama
    // public static function getHostelResidentStats($year = null)
    // {
    //     // Default to current year if no year is provided
    //     if ($year === null) {
    //         $year = date('Y'); // Get the current year dynamically
    //     }

    //     return static::find()
    //     ->select([
    //         'jenis_asrama.jenis_bilik',
    //         'SUM(CASE WHEN tempah_asrama.jantina = 0 THEN 1 ELSE 0 END) AS total_male',  // Male is 0
    //         'SUM(CASE WHEN tempah_asrama.jantina = 1 THEN 1 ELSE 0 END) AS total_female', // Female is 1
    //         'COUNT(tempah_asrama.id) AS total_residents' // Total residents per room type
    //     ])
    //         ->joinWith(['asrama.jenisAsrama'])
    //         ->andWhere(new \yii\db\Expression("YEAR(tarikh_masuk) = :year"), [':year' => $year]) // Filter by year
    //         ->andWhere(['IS NOT', 'jenis_asrama.jenis_bilik', null]) // Filter jenis_bilik NOT NULL
    //         ->groupBy('jenis_asrama.jenis_bilik')
    //         ->asArray()
    //         ->all();
    // }

        public static function getHostelResidentStats($year = null)
    {
        // Default to current year if no year is provided
        if ($year === null) {
            $year = date('Y');
        }

        return static::find()
            ->select([
                'jenis_asrama.jenis_bilik',

                // Jumlah lelaki
                'SUM(
                    CASE 
                        WHEN tempah_asrama.jantina = 0 THEN 
                            CASE 
                                WHEN tempah_asrama.jenis_penginap = 3 THEN 2 
                                WHEN tempah_asrama.jenis_penginap = 2 THEN 1 
                                ELSE 0 
                            END
                        ELSE 0 
                    END
                ) AS total_male',

                // Jumlah perempuan
                'SUM(
                    CASE 
                        WHEN tempah_asrama.jantina = 1 THEN 
                            CASE 
                                WHEN tempah_asrama.jenis_penginap = 3 THEN 2 
                                WHEN tempah_asrama.jenis_penginap = 2 THEN 1 
                                ELSE 0 
                            END
                        ELSE 0 
                    END
                ) AS total_female',

                // Jumlah keseluruhan penghuni
                'SUM(
                    CASE 
                        WHEN tempah_asrama.jenis_penginap = 3 THEN 2 
                        WHEN tempah_asrama.jenis_penginap = 2 THEN 1 
                        ELSE 0 
                    END
                ) AS total_residents',
            ])
            ->joinWith(['asrama.jenisAsrama'])
            ->andWhere(new \yii\db\Expression("YEAR(tarikh_masuk) = :year"), [':year' => $year])
            ->andWhere(['IS NOT', 'jenis_asrama.jenis_bilik', null])
            ->groupBy('jenis_asrama.jenis_bilik')
            ->asArray()
            ->all();
    }

    public static function getMonthlyHostelBookings($month, $year)
    {
        return static::find()
            ->select([
                'jenis_asrama.jenis_bilik',
                'COUNT(tempah_asrama.id) AS total_bookings',
                'SUM(CASE WHEN tempah_asrama.jantina = 0 THEN 1 ELSE 0 END) AS total_male',
                'SUM(CASE WHEN tempah_asrama.jantina = 1 THEN 1 ELSE 0 END) AS total_female'
            ])
            ->joinWith(['asrama.jenisAsrama'])
            ->where([
                'MONTH(tarikh_masuk)' => $month,
                'YEAR(tarikh_masuk)' => $year
            ])
            ->andWhere(['IS NOT', 'jenis_asrama.jenis_bilik', null]) // Filter jenis_bilik NOT NULL
            ->groupBy('jenis_asrama.jenis_bilik')
            ->asArray()
            ->all();
    }

    public static function getYearlyHostelBookings($year)
    {
        // Ambil jumlah tempahan untuk setiap bulan dalam tahun yang dipilih
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[$m] = static::find()
                ->where(['YEAR(tarikh_masuk)' => $year, 'MONTH(tarikh_masuk)' => $m])
                ->count();  // Mengambil jumlah tempahan bagi setiap bulan
        }
        return $monthlyData;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->id_asrama) {
            $asrama = Asrama::findOne($this->id_asrama);

            if ($asrama && $this->is_simpanan == 1) {
                // Kalau simpanan, terus tukar ke status 2
                $asrama->status_asrama = 2; // Simpanan
                $asrama->save(false);
            }
            
            // Jangan sentuh status lain — biar cronjob urus ikut tarikh
        }
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->tarikh_keluar) {
                // Auto-set tarikh_pembersihan kepada 5 hari selepas tarikh_keluar
                $this->tarikh_pembersihan = date('Y-m-d', strtotime($this->tarikh_keluar . ' +5 days'));
            }
            return true;
        }
        return false;
    }



    public static function releaseExpiredReservations()
    {
        $today = date('Y-m-d');

        // Cari semua tempahan asrama simpanan yang dah tamat tempoh
        $expiredReservations = self::find()
            ->where(['is_simpanan' => 1])
            ->andWhere(['<', 'tarikh_keluar', $today])
            ->all();

        foreach ($expiredReservations as $reservation) {
            $asrama = Asrama::findOne($reservation->id_asrama);
            if ($asrama) {
                $asrama->status_asrama = 1; // Tukar status ke kosong
                $asrama->save(false);
            }

            // Set is_simpanan ke 0 supaya tempahan tak trigger lagi
            $reservation->is_simpanan = 0;
            $reservation->save(false);
        }
    }



}
