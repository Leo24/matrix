<?php

namespace common\modules\api\v1\synchronize\models;

use common\modules\api\v1\user\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "movement".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $timestamp
 * @property integer $created_at
 * @property integer $updated_at
 */
class Movement extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%movement}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'timestamp'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'created_at' => 'Date of creation',
            'updated_at' => 'Update date',
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
     * @param $jsonMovementData
     * @param $userId
     * @throws \Exception
     */
    public function saveMovement($jsonMovementData, $userId)
    {
        $rows = [];
        foreach ($jsonMovementData as $m) {
            $rows[] = [
                'user_id'    => $userId,
                'timestamp'  => $m,
                'created_at' => time(),
                'updated_at' => time()
            ];
        }

        $attr = $this->attributes();
        unset($attr[0]);

        Yii::$app->db->createCommand()
            ->batchInsert(Movement::tableName(), $attr, $rows)
            ->execute();
    }
}
