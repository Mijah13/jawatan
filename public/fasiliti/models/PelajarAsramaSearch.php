<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PelajarAsrama;

/**
 * PelajarAsramaSearch represents the model behind the search form of `app\models\PelajarAsrama`.
 */
class PelajarAsramaSearch extends PelajarAsrama
{
    public $nama;
    public $bilik_asrama;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
             [['id', 'user_id', 'id_asrama', 'status', 'jantina', 'jenis_bilik', 'status_penginapan'], 'integer'],
            [['nama', 'blok', 'aras', 'no_asrama', 'bilik_asrama'], 'safe'],

            [['tarikh_masuk', 'tarikh_keluar', 'tarikh_pembersihan', 'no_kp', 'no_tel', 'email', 'kod_kursus', 'sesi_batch', 'alamat', 'created_at', 'updated_at'], 'safe'],
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = PelajarAsrama::find();
        $query->joinWith(['user', 'asrama']); // pastikan ada relation getUser() dalam PelajarAsrama

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'id',
                    'nama' => [
                        'asc' => ['user.nama' => SORT_ASC],
                        'desc' => ['user.nama' => SORT_DESC],
                    ],
                    'bilik_asrama' => [
                        'asc' => ['asrama.blok' => SORT_ASC, 'asrama.aras' => SORT_ASC, 'asrama.no_asrama' => SORT_ASC],
                        'desc' => ['asrama.blok' => SORT_DESC, 'asrama.aras' => SORT_DESC, 'asrama.no_asrama' => SORT_DESC],
                    ],
                    // Tambah attribute lain kalau nak
                ],
                'defaultOrder' => ['bilik_asrama' => SORT_ASC], // ni yang penting
            ],
        ]);

        // $dataProvider = new ActiveDataProvider([
        //     'query' => $query,
        //     'sort' => [
        //     // 'defaultOrder' => ['id_asrama' => SORT_ASC], // ✅ Tambah ni
        // ],
        // ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            // 'id' => $this->id,
            'user_id' => $this->user_id,
            'id_asrama' => $this->id_asrama,
            // 'tarikh_masuk' => $this->tarikh_masuk,
            // 'tarikh_keluar' => $this->tarikh_keluar,
            'tarikh_pembersihan' => $this->tarikh_pembersihan,
            'status' => $this->status,
            'jantina' => $this->jantina,
            'jenis_bilik' => $this->jenis_bilik,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status_penginapan' => $this->status_penginapan, 
        ]);

        $query->andFilterWhere(['like', 'no_kp', $this->no_kp])
            ->andFilterWhere(['pelajar_asrama.id' => $this->id])

            ->andFilterWhere(['like', 'no_tel', $this->no_tel])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'kod_kursus', $this->kod_kursus])
            ->andFilterWhere(['like', 'sesi_batch', $this->sesi_batch])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'user.nama', $this->nama])
            ->andFilterWhere(['like', "CONCAT(asrama.blok, asrama.aras, asrama.no_asrama)", $this->bilik_asrama]);

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

        return $dataProvider;
    }
}
