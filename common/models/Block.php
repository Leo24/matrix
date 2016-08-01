<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Class Block
 * @package common\modules\api\v1\auth\models
 */
class Block extends ActiveRecord
{

    /**
     * Primary key
     * @return string
     */
    public static function primaryKey()
    {
        return 'token';
    }

    /**
     * Table name
     * @return string
     */
    public static function tableName()
    {
        return '{{%block}}';
    }

    /**
     * Rules
     * @return array
     */
    public function rules()
    {
        return [
            [['user_id', 'token', 'expired_at'], 'safe'],
        ];
    }

    /**
     * Attributes labels
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'token' => 'AuthKey',
            'created_at' => 'Created at',
            'expired_at' => 'Expired at',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => null,
                'value' => function () {
                    return time();
                },
            ],
        ];
    }
}