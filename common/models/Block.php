<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

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
        return '{{%blocks}}';
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

    /**
     * Set the date create a new row
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->created_at = date('Y-m-d H:i:s');
            return true;
        }
        return false;
    }
}