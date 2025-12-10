<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Asrama;
use Yii;

/**
 * AsramaSearch represents the model behind the search form of `app\models\Asrama`.
 */
class AsramaSearch extends Asrama
{
    public $status_hari_ini; // <-- tambahkan virtual attribute
    public $status_log;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'aras', 'status_asrama', 'kelamin', 'jenis_asrama_id', 'penginap_kategori_id', 'kapasiti'], 'integer'],
            [['blok', 'no_asrama'], 'safe'],
        ];
    }

    public static function getStatusList()
    {
        return [
            0 => 'Kosong',
            1 => 'Sedang dibersihkan',
            2 => 'Simpanan',
            3 => 'Rosak',
            4 => 'Risiko',
            5 => 'Sedang dibaiki',
            6 => 'Diisi',
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
        $query = Asrama::find();

        $role = Yii::$app->user->identity->role ?? null;
        // add conditions that should always apply here
        if (in_array($role, [3, 4])) {
            $lastBilikId = Asrama::find()->max('id');
            $query->where(['!=', 'id', $lastBilikId]);
        }
        

        $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => [
            'pageSize' => 10,
        ],
        'sort' => [
            'defaultOrder' => [
                'blok' => SORT_ASC,
                'aras' => SORT_ASC,
                'no_asrama' => SORT_ASC,
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
            'id' => $this->id,
            'aras' => $this->aras, 
            'status_asrama' => $this->status_asrama,
     
            'kelamin' => $this->kelamin,
            'jenis_asrama_id' => $this->jenis_asrama_id,
            'penginap_kategori_id' => $this->penginap_kategori_id,
            'kapasiti' => $this->kapasiti,
        ]);

        if(Yii::$app->user->identity->role > 2) {
            $query->andWhere(['<>', 'jenis_asrama_id', 5]);
        }

        if ($this->status_hari_ini !== null && $this->status_hari_ini !== '') {
            $today = date('Y-m-d');
        
            // Step 1: cari ID asrama yg ada status aktif today
            $asramaIDsToday = \app\models\AsramaStatusLog::find()
                ->select('id_asrama')
                ->where(['<=', 'tarikh_mula', $today])
                ->andWhere([
                    'or',
                    ['>=', 'tarikh_tamat', $today],
                    ['tarikh_tamat' => null]
                ])
                ->andWhere(['status_log' => $this->status_hari_ini])
                ->column();
        
            // Step 2: cari ID asrama yang latest log sebelum hari ini (kalau tiada status aktif)
            $asramaIDsFallback = \Yii::$app->db->createCommand("
                SELECT l1.id_asrama FROM asrama_status_log l1
                INNER JOIN (
                    SELECT id_asrama, MAX(tarikh_mula) AS latest_mula
                    FROM asrama_status_log
                    WHERE tarikh_mula < :today
                    GROUP BY id_asrama
                ) l2 ON l1.id_asrama = l2.id_asrama AND l1.tarikh_mula = l2.latest_mula
                WHERE l1.status_log = :status
            ", [
                ':today' => $today,
                ':status' => $this->status_hari_ini,
            ])->queryColumn();
        
            $query->andWhere(['id' => array_unique(array_merge($asramaIDsToday, $asramaIDsFallback))]);
        }
        
        // $type = Yii::$app->request->get('type');
        // if ($type === 'pelajar') {
        //     $query->andWhere(['jenis_bilik' => 5]); // Only rooms with 'jenis_bilik' = 5
        // } else {
        //     $query->andWhere(['jenis_bilik' => [1, 2, 3, 4]]); // Rooms with 'jenis_bilik' = 1-4
        // }

        $query->andFilterWhere(['like', 'blok', $this->blok])
            ->andFilterWhere(['like', 'aras', $this->aras])
            ->andFilterWhere(['like', 'no_asrama', $this->no_asrama]);

        return $dataProvider;
    }
}
