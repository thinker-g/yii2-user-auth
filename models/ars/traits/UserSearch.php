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

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 *
 * @author Thinker_g
 *
 */
trait UserSearch
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['username', 'primary_email','phone', 'password_hash', 'display_name', 'auth_key', 'password_reset_token', 'created_at', 'updated_at', 'last_login_at'], 'safe'],
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
        $query = parent::find();

        $dataProvider = new ActiveDataProvider([
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
            'id' => $this->id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
        ->andFilterWhere(['like', 'primary_email', $this->primary_email])
        ->andFilterWhere(['like', 'phone', $this->primary_email])
        ->andFilterWhere(['like', 'display_name', $this->primary_email])
        // ->andFilterWhere(['like', 'password_hash', $this->password_hash])
        // ->andFilterWhere(['like', 'auth_key', $this->auth_key])
        ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
        ->andFilterWhere(['like', 'created_at', $this->created_at])
        ->andFilterWhere(['like', 'updated_at', $this->updated_at])
        ->andFilterWhere(['like', 'last_login_at', $this->last_login_at]);

        return $dataProvider;
    }
}

?>
