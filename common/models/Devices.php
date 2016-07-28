<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "devices".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $serial
 * @property string $description
 * @property string $software_version
 */
class Devices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'devices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['description'], 'string'],
            [['name', 'serial'], 'string', 'max' => 256],
            [['software_version'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'serial' => 'Serial',
            'description' => 'Description',
            'software_version' => 'Software Version',
        ];
    }
}
