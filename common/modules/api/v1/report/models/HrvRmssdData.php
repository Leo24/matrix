<?php

namespace common\modules\api\v1\report\models;

use Yii;
use \yii\db\Query;
use yii\data\ActiveDataProvider;
use common\modules\api\v1\user\models\User;

/**
 * This is the model class for table "hrv_rmssd_data".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $timestamp
 * @property integer $rmssd
 * @property integer $low_frequency
 * @property integer $high_frequency
 *
 */
class HrvRmssdData extends \yii\db\ActiveRecord
{
    public $startDate;
    public $endDate;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hrv_rmssd_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'rmssd', 'low_frequency', 'high_frequency', 'timestamp'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['startDate', 'endDate'], 'safe']
        ];
    }

    /**
     * @return string
     */
    public function formName()
    {
        return '';
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
            'rmssd' => 'Rmssd',
            'low_frequency' => 'Low Frequency',
            'high_frequency' => 'High Frequency',
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
     * @return array
     */
    public function heartHealthGraphData($params)
    {
        $this->load($params);
        $query = (new Query())
            ->select(['{{timestamp}}', '{{rmssd}}'])
            ->from('hrv_rmssd_data')
            ->where(['user_id' => $this->user_id]);
        if ($this->startDate && $this->endDate) {
            $query->andWhere(['between', 'timestamp', $this->startDate, $this->endDate]);
        }
        return $query->all();
    }
}
