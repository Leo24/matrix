<?php

namespace common\modules\api\v1\notification\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "notifications".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $description
 * @property integer $viewed
 * @property ENUM('positivity','negativity','neutrality') $type
 * @property ENUM('report','experiment','tip','matrix','goal') $tag
 * @property integer $created_at
 * @property integer $updated_at
 */
class Notification extends ActiveRecord
{
    const BAD_TYPE = 'bad';
    const NOT_GOOD_TYPE = 'not_good';
    const OKEY_TYPE = 'okey';
    const GOOD_TYPE = 'good';
    const VERY_GOOD_TYPE = 'very_good';

    const REPORT_TAG = 'report';
    const EXPERIMENT_TAG = 'experiment';
    const TIP_TAG = 'tip';
    const MATRIX_TAG = 'matrix';
    const GOAL_TAG = 'goal';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notification}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'title'], 'required', 'on' => 'create'],
            [['user_id', 'viewed'], 'integer'],
            [['type'], 'in', 'range' => $this->getTypeList()],
            [['tag'], 'in', 'range' => $this->getTagList()],
            [['description', 'created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255]
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'user_id'     => 'User ID',
            'title'       => 'Title',
            'description' => 'Description',
            'viewed'      => 'Viewed',
            'type'        => 'Type',
            'tag'         => 'Tag'
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
     * Return list of types
     *
     * @return array
     */
    public function getTypeList()
    {
        return [
            self::BAD_TYPE,
            self::NOT_GOOD_TYPE,
            self::OKEY_TYPE,
            self::GOOD_TYPE,
            self::VERY_GOOD_TYPE,
        ];
    }

    /**
     * Return list of tags
     *
     * @return array
     */
    public function getTagList()
    {
        return [
            self::REPORT_TAG,
            self::EXPERIMENT_TAG,
            self::GOAL_TAG,
            self::TIP_TAG,
            self::MATRIX_TAG
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
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query'      => $query
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(
            [
                'tag'     => $this->tag,
                'user_id' => $this->user_id,
                'type'    => $this->type,
                'viewed'  => $this->viewed
            ]
        );

        if ($this->created_at) {
            $query->andWhere(['>=', 'created_at', $this->created_at]);
        }

        return $dataProvider;
    }
}
