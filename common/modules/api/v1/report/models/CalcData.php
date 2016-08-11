<?php

namespace common\modules\api\v1\report\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\modules\api\v1\user\models\User;
use \yii\db\Query;

/**
 * This is the model class for table "calc_data".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $timestamp
 * @property double $heart_rate
 * @property double $respiration_rate
 * @property integer $activity
 *
 */
class CalcData extends \yii\db\ActiveRecord
{

    public $startDate;
    public $endDate;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calc_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'activity'], 'integer'],
            [['timestamp'], 'integer'],
            [['heart_rate', 'respiration_rate'], 'number'],
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
            'heart_rate' => 'Heart Rate',
            'respiration_rate' => 'Respiration Rate',
            'activity' => 'Activity',
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
    public function heartRateGraphData($params)
    {
        $this->load($params);
        $query = (new Query())
            ->select(['{{timestamp}}', '{{heart_rate}}'])
            ->from('calc_data')
            ->where(['user_id' => $this->user_id]);
        if ($this->startDate && $this->endDate) {
            $query->andWhere(['between', 'timestamp', $this->startDate, $this->endDate]);
        }
        return $query->all();
    }

}
