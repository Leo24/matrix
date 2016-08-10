<?php

namespace common\modules\api\v1\report\models;

use Yii;
use common\modules\api\v1\user\models\User;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "sleep_data".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $timestamp
 * @property double $sleep_type
 */
class SleepData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sleep_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'timestamp'], 'integer'],
            [['sleep_type'], 'number'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'timestamp' => 'Timestamp',
            'sleep_type' => 'Sleep Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['user_id' => $this->user_id]);

        return $dataProvider;
    }
}
