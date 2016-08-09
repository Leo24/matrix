<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table 'accomplishment'.
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\models
 */
class Accomplishment extends ActiveRecord
{
    /**
     * Table name
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%accomplishment}}';
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'id',
            'user_id',
            'title',
            'created_at',
            'updated_at',
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
                'updatedAtAttribute' => 'updated_at',
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
            [['user_id', 'title'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['created_at', 'updated_at', 'user_id'], 'integer'],
            [['user_id', 'title'], 'required'],
        ];
    }

    /**
     * Attributes labels
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'title' => Yii::t('app', 'Title'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
        ];
    }
}
