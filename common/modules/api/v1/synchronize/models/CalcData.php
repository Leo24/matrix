<?php

namespace common\modules\api\v1\synchronize\models;

use common\modules\api\v1\user\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use \yii\db\ActiveRecord;
use yii\db\Query;

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
class CalcData extends ActiveRecord
{
    /** @var  $startDate */
    public $startDate;

    /** @var  $endDate */
    public $endDate;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%calc_data}}';
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
            [['startDate', 'endDate'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'               => 'ID',
            'user_id'          => 'User ID',
            'timestamp'        => 'Timestamp',
            'heart_rate'       => 'Heart Rate',
            'respiration_rate' => 'Respiration Rate',
            'activity'         => 'Activity',
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
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value'              => function () {
                    return time();
                },
            ],
        ];
    }

    /**
     * Method of saving calc data
     *
     * @param $jsonCalcData
     * @param $userId
     * @throws \Exception
     */
    public function saveCalcData($jsonCalcData, $userId)
    {
//        $currentTimestamp = $jsonCalcData[0][0];
        $rows = [];
        foreach ($jsonCalcData as $k => $m) {
            // todo интервал должен быть 10 минут
            $rows[$k] = [
                'user_id'          => $userId,
                'timestamp'        => isset($m[0]) ? $m[0] : null,
                'heart_rate'       => isset($m[1]) ? $m[1] : null,
                'respiration_rate' => isset($m[2]) ? $m[2] : null,
                'activity'         => isset($m[3]) ? $m[3] : null,
                'created_at'       => time(),
                'updated_at'       => time()
            ];
        }

        $attr = $this->attributes();
        unset($attr[0]);

        Yii::$app->db->createCommand()
            ->batchInsert(CalcData::tableName(), $attr, $rows)
            ->execute();
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
        } else {
            return 'Params startDate and endDate are Required.';
        }
        return $query->all();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return array
     */
    public function breathingGraphData($params)
    {
        $this->load($params);
        $query = (new Query())
            ->select(['{{timestamp}}', '{{respiration_rate}}'])
            ->from('calc_data')
            ->where(['user_id' => $this->user_id]);
        if ($this->startDate && $this->endDate) {
            $query->andWhere(['between', 'timestamp', $this->startDate, $this->endDate]);
        } else {
            return 'Params startDate and endDate are Required.';
        }
        return $query->all();
    }
}
