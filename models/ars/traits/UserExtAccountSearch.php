<?php
namespace thinker_g\UserAuth\models\ars\traits;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 *
 * @author Thinker_g
 *
 */
trait UserExtAccountSearch
{
    public $username;
    public $primary_email;

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
                'from_source',
                'access_token',
                'email',
                'created_at',
                'updated_at'
            ], 'safe'],
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
            'asc' => [parent::tableName() . '.username' => SORT_ASC],
            'desc' =>[parent::tableName() . '.username' => SORT_DESC]
        ];
        $dataProvider->sort->attributes['primary_email'] = [
            'label' => 'Primary email',
            'asc' => [parent::tableName() . '.primary_email' => SORT_ASC],
            'desc' =>[parent::tableName() . '.primary_email' => SORT_DESC]
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
            'ext_user_id' => $this->ext_user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'from_source' => $this->from_source ? $this->form_source : array_keys($this->availableSources()),
        ]);

        $query->andFilterWhere(['like', parent::tableName() . '.username', $this->username])
            ->andFilterWhere(['like', parent::tableName() . '.primary_email', $this->primary_email])
            ->andFilterWhere(['like', 'access_token', $this->access_token])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}

?>