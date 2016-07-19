<?php
namespace api\modules\v1\models;
use \yii\db\ActiveRecord;
/**
 * Country Model
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class Device extends ActiveRecord
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
    public static function primaryKey()
    {
        return ['code'];
    }

    /**
     * Define rules for validation
     */
    public function rules()
    {
        return [
            [['code', 'name', 'population'], 'required']
        ];
    }
}
