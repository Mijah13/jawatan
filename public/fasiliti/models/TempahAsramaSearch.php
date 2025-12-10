<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TempahAsrama;
use Yii;

/**
 * TempahAsramaSearch represents the model behind the search form of `app\models\TempahAsrama`.
 */
class TempahAsramaSearch extends TempahAsrama
{
    public $nama;
    public $bilik_asrama;
    public $disokong_oleh_nama;
    public $diluluskan_oleh_nama;
    public $dibatalkan_oleh_nama;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_asrama', 'user_id', 'jenis_penginap', 'jenis_bilik', 'jantina', 'disokong_oleh', 'status_tempahan_adminKemudahan', 'dibatalkan_oleh', 'status_pembayaran', 'diluluskan_oleh', 'status_tempahan_pelulus', 'is_simpanan', 'pengiraan_bayaran', 'invois_dijana', 'disahkanBayaran_oleh'], 'integer'],
            [['nama', 'bilik_asrama', 'diluluskan_oleh_nama', 'disokong_oleh_nama', 'dibatalkan_oleh_nama', 'no_kp_pemohon', 'agensi_pemohon', 'tujuan', 'tarikh_masuk', 'tarikh_keluar', 'tarikh_pembersihan', 'no_tel', 'alamat', 'email', 'surat_sokongan', 'nama_penginap_1', 'email_penginap_1', 'no_tel_penginap_1', 'alamat_penginap_1', 'nama_penginap_2', 'email_penginap_2', 'no_tel_penginap_2', 'alamat_penginap_2', 'alasan_batal', 'slip_pembayaran', 'tarikh_upload_slip', 'created_at', 'updated_at', 'tarikh_invois_dijana', 'no_resit'], 'safe'],
            [['diskaun'], 'number'],
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TempahAsrama::find()
            ->joinWith(['user', 'asrama', 'diluluskanOleh diluluskanOlehRelation', 'disokongOleh disokongOlehRelation']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                ], 
                'sort' => [
                'defaultOrder' => [
                'created_at' => SORT_DESC,
                'id' => SORT_DESC,
            ],
            ],
        ]);

        // if  (Yii::$app->user->identity->role == 2 || Yii::$app->user->identity->role == 0) {
        //     $query->orderBy([
        //         'updated_at' => SORT_DESC, // Oldest unapproved bookings first
        //         'created_at' => SORT_DESC, // Newest bookings first if same update time
        //     ]);
        // }
        
        // if  (Yii::$app->user->identity->role == 1 || Yii::$app->user->identity->role == 0) {
        //     $query->orderBy([
        //         'updated_at' => SORT_DESC, // Oldest unprocessed bookings first
        //         'created_at' => SORT_DESC, // Newest bookings first if same update time
        //     ]);
        // }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tempah_asrama.id' => $this->id,
            'tempah_asrama.id_asrama' => $this->id_asrama,

            'user_id' => $this->user_id,
            'jenis_penginap' => $this->jenis_penginap,
            // 'tarikh_masuk' => $this->tarikh_masuk,
            // 'tarikh_keluar' => $this->tarikh_keluar,
            'tarikh_pembersihan' => $this->tarikh_pembersihan, 
            'jenis_bilik' => $this->jenis_bilik,
            'jantina' => $this->jantina,
            'disokong_oleh' => $this->disokong_oleh,
            'status_tempahan_adminKemudahan' => $this->status_tempahan_adminKemudahan,
            'dibatalkan_oleh' => $this->dibatalkan_oleh,
            'status_pembayaran' => $this->status_pembayaran,
            'tarikh_upload_slip' => $this->tarikh_upload_slip,
            'diluluskan_oleh' => $this->diluluskan_oleh, 
            'status_tempahan_pelulus' => $this->status_tempahan_pelulus,
            'created_at' => $this->created_at, 
            'updated_at' => $this->updated_at, 
            'is_simpanan' => $this->is_simpanan,
            'diskaun' => $this->diskaun, 
            'pengiraan_bayaran' => $this->pengiraan_bayaran, 
            'invois_dijana' => $this->invois_dijana,
            'tarikh_invois_dijana' => $this->tarikh_invois_dijana,
            'disahkanBayaran_oleh' => $this->disahkanBayaran_oleh, 
        ]);

        $query->andFilterWhere(['like', 'no_kp_pemohon', $this->no_kp_pemohon])
            ->andFilterWhere(['like', 'user.nama', $this->nama])
            ->andFilterWhere(['like', "CONCAT(asrama.blok, asrama.aras, asrama.no_asrama)", $this->bilik_asrama])
                ->andFilterWhere([
                    'or',
                    ['like', 'diluluskanOlehRelation.nama', $this->diluluskan_oleh_nama],
                    ['like', 'disokongOlehRelation.nama', $this->disokong_oleh_nama],
                ])
            ->andFilterWhere(['like', 'agensi_pemohon', $this->agensi_pemohon])
            ->andFilterWhere(['like', 'tujuan', $this->tujuan])
            ->andFilterWhere(['like', 'no_tel', $this->no_tel])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'surat_sokongan', $this->surat_sokongan])
            ->andFilterWhere(['like', 'nama_penginap_1', $this->nama_penginap_1])
            ->andFilterWhere(['like', 'email_penginap_1', $this->email_penginap_1])
            ->andFilterWhere(['like', 'no_tel_penginap_1', $this->no_tel_penginap_1])
            ->andFilterWhere(['like', 'alamat_penginap_1', $this->alamat_penginap_1])
            ->andFilterWhere(['like', 'nama_penginap_2', $this->nama_penginap_2])
            ->andFilterWhere(['like', 'email_penginap_2', $this->email_penginap_2])
            ->andFilterWhere(['like', 'no_tel_penginap_2', $this->no_tel_penginap_2])
            ->andFilterWhere(['like', 'alamat_penginap_2', $this->alamat_penginap_2])
            ->andFilterWhere(['like', 'alasan_batal', $this->alasan_batal])
            ->andFilterWhere(['like', 'slip_pembayaran', $this->slip_pembayaran])
            ->andFilterWhere(['like', 'no_resit', $this->no_resit]);

             if (!empty($this->tarikh_masuk)) {
                $date = \DateTime::createFromFormat('d-m-Y', $this->tarikh_masuk);
                if ($date) {
                    $query->andFilterWhere(['tarikh_masuk' => $date->format('Y-m-d')]);
                }
            }

            if (!empty($this->tarikh_keluar)) {
                $date = \DateTime::createFromFormat('d-m-Y', $this->tarikh_keluar);
                if ($date) {
                    $query->andFilterWhere(['tarikh_keluar' => $date->format('Y-m-d')]);
                }
            }

            // Filter untuk dibatalkan_oleh_nama
            if (!empty($this->dibatalkan_oleh_nama)) {
                $query->andWhere([
                    'or',
                    // Kalau pelulus yang batalkan
                    [
                        'and',
                        ['status_tempahan_pelulus' => 3],
                        ['like', 'diluluskanOlehRelation.nama', $this->dibatalkan_oleh_nama]
                    ],
                    // Kalau penyokong yang batalkan
                    [
                        'and',
                        ['or', ['status_tempahan_pelulus' => null], ['!=', 'status_tempahan_pelulus', 3]],
                        ['like', 'disokongOlehRelation.nama', $this->dibatalkan_oleh_nama]
                    ]
                ]);
            }



        return $dataProvider;
    }
}
