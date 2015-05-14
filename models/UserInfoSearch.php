<?php

namespace thinker_g\UserAuth\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use thinker_g\UserAuth\models\UserInfo;

/**
 * UserInfoSearch represents the model behind the search form about `thinker_g\UserAuth\models\UserInfo`.
 */
class UserInfoSearch extends UserInfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'is_male'], 'integer'],
            [['dob', 'board_type', 'ski_age'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = UserInfo::find();

        $dataProvider = new ActiveDataProvider([
            'key' => 'user_id',
            'query' => $query,
            'sort' => [
                'defaultOrder' => [self::primaryKey()[0] => SORT_DESC]
            ],
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'is_male' => $this->is_male,
            'dob' => $this->dob,
        ]);

        $query->andFilterWhere(['like', 'board_type', $this->board_type])
            ->andFilterWhere(['like', 'ski_age', $this->ski_age]);

        return $dataProvider;
    }
}
