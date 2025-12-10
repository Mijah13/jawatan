<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\JenisAsrama;

/**
 * JenisAsramaSearch represents the model behind the search form of `app\models\JenisAsrama`.
 */
class JenisAsramaSearch extends JenisAsrama
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'asrama_id', 'akses_pengguna'], 'integer'],
            [['jenis_bilik', 'deskripsi', 'gambar'], 'safe'],
            [['kadar_sewa'], 'number'],
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
        $query = JenisAsrama::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'kadar_sewa' => $this->kadar_sewa,
            'asrama_id' => $this->asrama_id,
            'akses_pengguna' => $this->akses_pengguna, 
        ]);

        $query->andFilterWhere(['like', 'jenis_bilik', $this->jenis_bilik])
            ->andFilterWhere(['like', 'deskripsi', $this->deskripsi])
            ->andFilterWhere(['like', 'gambar', $this->gambar]);

        
        return $dataProvider;
    }
}
