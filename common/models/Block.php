<?php
namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "block".
 *
 * @package common\models
 */
class Block extends ActiveRecord
{
    /**
     * Table name
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%block}}';
    }
    /**
     * Primary key
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return 'token';
    }
    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'token',
            'user_id',
            'created_at',
            'expired_at',
        ];
    }
    /**
     * @inheritdoc
     */
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
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'token', 'expired_at'], 'safe'],
            [['token'], 'string', 'max' => 255],
            [['expired_at'], 'integer'],
            [['user_id', 'token', 'expired_at'], 'required'],
        ];
    }
    /**
     * Attributes labels
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'token' => Yii::t('app', 'Token'),
            'created_at' => Yii::t('app', 'Created at'),
            'expired_at' => Yii::t('app', 'Expired at'),
        ];
    }
}