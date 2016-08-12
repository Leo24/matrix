<?php

namespace common\modules\api\v1\emfit\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use \yii\db\ActiveRecord;
use common\modules\api\v1\user\models\User;
use yii\db\Query;

/**
 * This is the model class for table "sleep_data".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $timestamp
 * @property double $sleep_type
 *
 * @property User $user
 */
class SleepData extends ActiveRecord
{
    const SLEEP_TYPE_DEEP  = 'deep';
    const SLEEP_TYPE_LIGHT = 'light';
    const SLEEP_TYPE_REM   = 'rem';
    const SLEEP_TYPE_AWAKE = 'awake';

    /** @var  $startDate */
    public $startDate;

    /** @var  $endDate */
    public $endDate;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sleep_data}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'timestamp'], 'integer'],
            [['sleep_type'], 'in', 'range' => $this->getSleepTypeList()],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['startDate', 'endDate'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'user_id'    => 'User ID',
            'timestamp'  => 'Timestamp',
            'sleep_type' => 'Sleep Type',
        ];
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
     * @param $id
     * @return array
     */
    public function getSleepTypeList($id = false)
    {
        $sleepTypes = [
            1 => self::SLEEP_TYPE_DEEP,
            2 => self::SLEEP_TYPE_LIGHT,
            3 => self::SLEEP_TYPE_REM,
            4 => self::SLEEP_TYPE_AWAKE
        ];

        return ($id) ? $sleepTypes[$id] : $sleepTypes;
    }

    /**
     * Method of saving sleep data
     *
     * @param $jsonSleepData
     * @param $userId
     * @throws \Exception
     */
    public function saveSleepData($jsonSleepData, $userId)
    {
        $rows = [];
        foreach ($jsonSleepData as $k => $m) {
            $rows[$k] = [
                'user_id'          => $userId,
                'timestamp'        => isset($m[0]) ? $m[0] : null,
                'sleep_type'       => isset($m[1]) ? $this->getSleepTypeList($m[1]) : null,
                'created_at'       => time(),
                'updated_at'       => time()
            ];
        }

        $attr = $this->attributes();
        unset($attr[0]);

        Yii::$app->db->createCommand()
            ->batchInsert(SleepData::tableName(), $attr, $rows)
            ->execute();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return array
     */
    public function sleepCyclesGraphData($params)
    {
        $this->load($params);
//        $numDays = abs($this->endDate - $this->startDate)/60/60/24;
//        $days = [];
//        for ($i = 1; $i < $numDays; $i++) {
//            $days[] = [date('Y m d', strtotime("+{$i} day", $this->endDate))];
//        }

        $query = (new Query())
            ->select(['timestamp', 'sleep_type'])
            ->from('sleep_data')
            ->where(['user_id' => $this->user_id])
            ->andWhere(['between', 'timestamp', strtotime("-1 day", $this->startDate), $this->endDate]);

        return $query->all();

//        $sleepCyclesGraphData = $query->all();
//
//        foreach ($sleepCyclesGraphData as $ln) {
//            if ($ln['sleep_type'] == 'awake') {
//
//            } elseif($ln['sleep_type'] == 'rem'){
//
//            } elseif($ln['sleep_type'] == 'light'){
//
//            } elseif($ln['sleep_type'] == 'deep'){
//
//            }
//        }

    }




    
    
}
