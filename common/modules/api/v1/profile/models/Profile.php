<?php

namespace common\modules\api\v1\profile\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\helpers\BaseUrl;
use yii\helpers\BaseFileHelper;
use yii\behaviors\TimestampBehavior;
use common\modules\api\v1\user\models\User;

/**
 * This is the model class for table 'profile'
 *
 * @property integer user_id
 * @property enum('public', 'private', 'off-the-grid') $privacy
 * @property string firstname
 * @property string lastname
 * @property string gender
 * @property string avatar_url
 * @property object avatar
 * @property string state
 * @property string city
 * @property string phone
 * @property string profession_interest
 * @property string average_hours_sleep
 * @property integer birthday
 * @property integer updated_at
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\profile\models
 */
class Profile extends ActiveRecord
{
    public $avatar;

    const SCENARIO_REGISTER = 'register';
    const SCENARIO_UPLOAD_AVATAR = 'avatar';
    const PRIVACY_PUBLIC = 'public';
    const PRIVACY_PRIVATE = 'private';
    const PRIVACY_OFF_THE_GRID = 'off-the-grid';

    /**
     * Photo maximum size value of the photo (mbyte)
     */
    const FILE_AVATAR_MAX_SIZE_MB = 3;

    /**
     * Path to folder with avatars
     */
    const FILE_AVATAR_PATH = '/web/uploads/avatars/';

    /**
     * Primary key name
     *
     * @inheritdoc
     */
    public $primaryKey = 'user_id';

    /**
     * Table name
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * Attribute labels
     *
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'firstname' => Yii::t('app', 'First name'),
            'lastname' => Yii::t('app', 'Last name'),
            'gender' => Yii::t('app', 'Gender'),
            'state' => Yii::t('app', 'State'),
            'city' => Yii::t('app', 'City'),
            'phone' => Yii::t('app', 'Phone'),
            'avatar_url' => Yii::t('app', 'Avatar URL'),
            'birthday' => Yii::t('app', 'Birthday'),
            'profession_interest' => Yii::t('app', 'Profession interest'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarion = parent::scenarios();
        $scenarion[self::SCENARIO_REGISTER] = [
            'firstname',
            'lastname',
            'gender',
            'state',
            'city',
            'birthday',
            'profession_interest',
            'average_hours_sleep',
            'user_id',
        ];
        $scenarion[self::SCENARIO_UPLOAD_AVATAR] = [
            'avatar_url',
        ];

        return $scenarion;
    }

    /**
     * @inheritdoc
     */
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname', 'profession_interest', 'state', 'city'], 'trim'],
            [
                ['firstname', 'lastname', 'state', 'city', 'profession_interest', 'birthday'],
                'required',
                'on' => self::SCENARIO_REGISTER
            ],
            [['privacy'], 'in', 'range' => $this->getPrivacyList()],
            [['firstname', 'lastname'], 'string', 'max' => 30],
            [['city', 'state'], 'string', 'max' => 20],
            [['birthday'], 'integer'],
            [['profession_interest', 'average_hours_sleep', 'avatar_url', 'phone'], 'string', 'max' => 255],
            ['gender', 'in', 'range' => ['female', 'male']],
            ['user_id', 'unique', 'targetClass' => self::className(), 'message' => Yii::t('app', 'Profile exists')],
            [
                ['avatar'],
                'file',
                'skipOnEmpty' => false,
                'extensions' => 'png, jpg',
                'on' => self::SCENARIO_UPLOAD_AVATAR,
                'maxSize' => self::getPhotoMaxSize()
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return ['user'];
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
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            $this->deleteAvatar();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns maximum size of the photo (bytes)
     *
     * @return int
     */
    public static function getPhotoMaxSize()
    {
        return self::FILE_AVATAR_MAX_SIZE_MB * 1024 * 1024;
    }

    /**
     * Returns avatar path
     *
     * @return string
     */
    public static function getAvatarPath()
    {
        return Yii::getAlias('@app') . self::FILE_AVATAR_PATH;
    }

    /**
     * Returns avatar filename
     *
     * @return string
     */
    public function createAvatarFileName()
    {
        return 'avatar-'
        . time()
        . '-'
        . $this->user_id
        . '.'
        . $this->avatar->extension;
    }

    /**
     * Returns avatar URL
     *
     * @param $fileName
     * @return string
     */
    public function getAvatarUrl($fileName)
    {
        return BaseUrl::base() . 'uploads/avatars/' . $fileName;
    }

    /**
     * Return list of privacy for users
     *
     * @return array
     */
    public function getPrivacyList()
    {
        return [
            self::PRIVACY_PUBLIC,
            self::PRIVACY_PRIVATE,
            self::PRIVACY_OFF_THE_GRID
        ];
    }


    /**
     * This is method uploads user avatar
     *
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function uploadAvatar()
    {
        if (!is_dir(self::getAvatarPath())) {
            BaseFileHelper::createDirectory(self::getAvatarPath(), $mode = 509, $recursive = true);
        }
        $fileName = $this->createAvatarFileName();
        $avatarFilePath = self::getAvatarPath() . $fileName;

        if ($this->avatar->saveAs($avatarFilePath)) {
            return $fileName;
        }
        return false;
    }

    /**
     * Deletes user avatar
     *
     * @return bool|int
     */
    public function deleteAvatar()
    {
        if (is_dir(self::getAvatarPath())) {
            $mask = "avatar-*-{$this->user_id}.*";
            $list = (glob('/' . self::getAvatarPath() . $mask));
            try {
                array_map('unlink', $list);

                return count($list);
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }
}
