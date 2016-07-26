<?php
namespace common\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * Class Profile
 * @package common\modules
 */
class Profile extends ActiveRecord
{
    /**
     * Table name
     * @return string
     */
    public static function tableName()
    {
        return '{{%profiles}}';
    }
}