<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TempahFasiliti;
use Yii;

/**
 * TempahFasilitiSearch represents the model behind the search form of `app\models\TempahFasiliti`.
 */
class TempahFasilitiSearch extends TempahFasiliti
{
    public $nama;
    public $disokong_oleh_nama;
    public $diluluskan_oleh_nama;
    public $dibatalkan_oleh_nama;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fasiliti_id', 'user_id', 'tempoh', 'jangkaan_hadirin', 'disokong_oleh', 'status_tempahan_adminKemudahan', 'dibatalkan_oleh', 'status_pembayaran', 'diluluskan_oleh', 'status_tempahan_pelulus', 'is_simpanan', 'invois_dijana', 'disahkanBayaran_oleh'], 'integer'],
            [['nama', 'diluluskan_oleh_nama', 'disokong_oleh_nama', 'dibatalkan_oleh_nama', 'no_kp_pemohon', 'agensi_pemohon', 'tujuan', 'tarikh_masuk', 'tarikh_keluar', 'no_tel', 'alamat', 'email', 'surat_sokongan', 'alasan_batal', 'slip_pembayaran', 'tarikh_upload_slip', 'created_at', 'updated_at', 'tarikh_invois_dijana', 'no_resit'], 'safe'],
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
       $query = TempahFasiliti::find()
            ->joinWith(['user', 'fasiliti', 'diluluskanOleh diluluskanOlehRelation', 'disokongOleh disokongOlehRelation']);

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
        
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tempah_fasiliti.id' => $this->id,
            'tempah_fasiliti.fasiliti_id' => $this->fasiliti_id,
            'user_id' => $this->user_id,
            // 'tarikh_masuk' => $this->tarikh_masuk,
            // 'tarikh_keluar' => $this->tarikh_keluar,
            'tempoh' => $this->tempoh, 
            'jangkaan_hadirin' => $this->jangkaan_hadirin,
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
            'invois_dijana' => $this->invois_dijana, 
            'tarikh_invois_dijana' => $this->tarikh_invois_dijana, 
            'disahkanBayaran_oleh' => $this->disahkanBayaran_oleh,
        ]);

        $query->andFilterWhere(['like', 'no_kp_pemohon', $this->no_kp_pemohon])
            ->andFilterWhere(['like', 'user.nama', $this->nama])
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
