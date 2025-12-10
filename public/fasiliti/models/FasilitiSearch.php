<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fasiliti;



/**
 * FasilitiSearch represents the model behind the search form of `app\models\Fasiliti`.
 */
class FasilitiSearch extends Fasiliti
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fasiliti_status', 'akses_pengguna'], 'integer'],
            [['nama_fasiliti', 'deskripsi', 'gambar'], 'safe'],
            [['kadar_sewa_perJam', 'kadar_sewa_perHari', 'kadar_sewa_perJamSiang', 'kadar_sewa_perJamMalam'], 'number'],
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
        $query = Fasiliti::find();

        // add conditions that should always apply here
           // Filter untuk user biasa: hanya tunjuk fasiliti yang statusnya "Kosong" (0)
           if (!\Yii::$app->user->isGuest) {
            $role = \Yii::$app->user->identity->role;
            if (!in_array($role, [0, 1, 6])) {
                $query->andWhere(['fasiliti_status' => 0]); // Hanya tunjuk fasiliti kosong
                
                // Hide fasiliti yang dalam simpanan (is_simpanan = 1)
                $query->andWhere(['not in', 'id', (new \yii\db\Query())
                    ->select('fasiliti_id')
                    ->from('tempah_fasiliti')
                    ->where(['is_simpanan' => 1])
                    ->andWhere(['>', 'tarikh_keluar', date('Y-m-d')]) // Simpanan belum tamat
                ]);
            }
        }
        


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
            'kadar_sewa_perJam' => $this->kadar_sewa_perJam,
            'kadar_sewa_perHari' => $this->kadar_sewa_perHari,
            'kadar_sewa_perJamSiang' => $this->kadar_sewa_perJamSiang,
            'kadar_sewa_perJamMalam' => $this->kadar_sewa_perJamMalam,
            'fasiliti_status' => $this->fasiliti_status,
            'akses_pengguna' => $this->akses_pengguna, 
        ]);

        $query->andFilterWhere(['like', 'nama_fasiliti', $this->nama_fasiliti])
            ->andFilterWhere(['like', 'deskripsi', $this->deskripsi])
            ->andFilterWhere(['like', 'gambar', $this->gambar]);

        return $dataProvider;
    }
}
