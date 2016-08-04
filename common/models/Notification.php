<?php

namespace common\models;

use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

/**
 * Class Notification
 * @package common\models
 */
class Notification extends ActiveRecord
{

    public $sleeping_position;

    const SCENARIO_REGISTER = 'register';

    public $primaryKey = 'user_id';

    /**
     * Table name
     * @return string
     */
    public static function tableName()
    {
        return '{{%notification}}';
    }

    /**
     * Attribute labels
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User Id',
            'title' => 'Title',
            'description' => 'Description',
            'viewed' => 'Viewed',
            'type' => 'Type',
            'tag' => 'Tag',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title', 'description', 'type', 'tag'], 'trim'],
            [['title', 'type', 'tag'], 'string', 'max' => 255],
            [['viewed'], 'boolean'],
        ];
    }
    
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => null,
                'updatedAtAttribute' => 'updated_at',
                'value' => function () {
                    return time();
                },
            ],
        ];
    }

}