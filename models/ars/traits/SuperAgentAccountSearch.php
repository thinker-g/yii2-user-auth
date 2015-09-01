<?php
/**
 * @link https://github.com/thinker-g/yii2-user-auth
 * @copyright Copyright (c) Thinker_g
 * @license MIT
 * @version v0.0.1
 * @author Thinker_g
 * @since v0.0.1
 */

namespace thinker_g\UserAuth\models\ars\traits;

use yii\data\ActiveDataProvider;
use thinker_g\UserAuth\models\ars\User;

/**
 *
 * @author Thinker_g
 *
 */
trait SuperAgentAccountSearch
{
    use UserExtAccountSearch;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'ext_user_id'], 'integer'],
            [[
                'username',
                'primary_email',
                'access_token',
                'from_source',
                'email',
                'created_at',
                'updated_at'
            ], 'safe'],
        ];
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
        $query = parent::find()->joinWith('user');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [self::primaryKey()[0] => SORT_DESC]
            ],
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        $dataProvider->sort->attributes['username'] = [
            'label' => 'Username',
            'asc' => [User::tableName() . '.username' => SORT_ASC],
            'desc' =>[User::tableName() . '.username' => SORT_DESC]
        ];
        $dataProvider->sort->attributes['primary_email'] = [
            'label' => 'Primary email',
            'asc' => [User::tableName() . '.primary_email' => SORT_ASC],
            'desc' =>[User::tableName() . '.primary_email' => SORT_DESC]
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            self::tableName() . '.id' => $this->id,
            'user_id' => $this->user_id,
            'from_source' => $this->from_source ? $this->from_source : $this->availableSources(),
        ]);

        $query->andFilterWhere(['like', User::tableName() . '.username', $this->username])
            ->andFilterWhere(['like', User::tableName() . '.primary_email', $this->primary_email])
            ->andFilterWhere(['like', 'access_token', $this->access_token])
            ->andFilterWhere(['like', 'from_source', $this->from_source])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', self::tableName() . '.created_at', $this->created_at])
            ->andFilterWhere(['like', self::tableName() . '.updated_at', $this->updated_at]);

        return $dataProvider;
    }
}

?>