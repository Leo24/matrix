<?php

namespace common\modules\api\v1\user\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\web\HttpException;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use common\modules\api\v1\block\models\Block;
use common\modules\api\v1\device\models\Device;
use common\modules\api\v1\profile\models\Profile;
use common\modules\api\v1\notification\models\Notification;
use common\modules\api\v1\socialNetwork\models\SocialNetwork;
use common\modules\api\v1\settings\models\SettingNotification;
use common\modules\api\v1\sleepingPosition\models\SleepingPosition;
use common\modules\api\v1\reasonUsingMatrix\models\ReasonUsingMatrix;
use common\modules\api\v1\user\traits\AuthorizationJwtTrait;

/**
 * This is the model class for table 'user'
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_login
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\user\models
 */
class User extends ActiveRecord implements IdentityInterface
{
    use AuthorizationJwtTrait;

    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER = 'register';
    const SCENARIO_UPDATE_PASSWORD = 'password';

    public $confirm;
    public $current_password;
    public $firstname;
    public $lastname;

    /**
     * Table name
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_LOGIN] = ['email', 'password'];
        $scenarios[self::SCENARIO_REGISTER] = ['email', 'password', 'username', 'confirm', 'firstname', 'lastname'];
        $scenarios[self::SCENARIO_UPDATE_PASSWORD] = ['password', 'confirm', 'current_password'];

        return $scenarios;
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password', 'confirm'], 'required', 'on' => self::SCENARIO_REGISTER],
            [['current_password', 'password', 'confirm'], 'required', 'on' => self::SCENARIO_UPDATE_PASSWORD],
            [
                'confirm',
                'compare',
                'compareAttribute' => 'password',
                'on'               => [self::SCENARIO_REGISTER, self::SCENARIO_UPDATE_PASSWORD]
            ],
            [['username', 'email'], 'safe'],
            [['password', 'email'], 'string', 'max' => 255],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => self::className(), 'message' => Yii::t('app', 'Email exists')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['password']);

        return $fields;
    }

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return [
            'userProfile',
            'sleepPosition',
            'reasonUsingMatrix',
            'userNotifications',
            'accountFields',
            'socialNetwork'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSleepPosition()
    {
        return $this->hasOne(SleepingPosition::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(Profile::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountFields()
    {
        return $this->hasOne(Profile::class, ['user_id' => 'id'])->select('phone');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReasonUsingMatrix()
    {
        return $this->hasOne(ReasonUsingMatrix::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserNotifications()
    {
        return $this->hasMany(Notification::class, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialNetwork()
    {
        return $this->hasOne(SocialNetwork::class, ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        parent::beforeSave($insert);

        $this->setPassword($this->password);
        if ($this->scenario == self::SCENARIO_REGISTER) {
            $this->username = $this->getUsername();
        }
        if ($this->scenario == self::SCENARIO_LOGIN) {
            $this->last_login = time();
            unset($this->password, $this->email);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            Profile::findOne(['user_id', $this->id])->deleteAvatar();

            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        unset($this->password);
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'      => Yii::t('app', 'Email'),
            'password'   => Yii::t('app', 'Password'),
            'username'   => Yii::t('app', 'User name'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
            'last_login' => Yii::t('app', 'Last login'),
        ];
    }

    /**
     * @param $modelErrors
     * @param bool $code
     * @return bool
     * @throws HttpException
     */
    public static function validationExceptionFirstMessage($modelErrors, $code = false)
    {
        if (is_array($modelErrors) && !empty($modelErrors)) {
            $fields = array_keys($modelErrors);
            $first_message = current($modelErrors[$fields[0]]);
            throw new HttpException(422, "Validation exception: {$first_message}", $code);
        }

        return false;
    }

    /**
     * Register a new user
     *
     * @param $data
     * @return array
     * @throws HttpException
     * @throws \yii\db\Exception
     */
    public static function registerUser($data)
    {
        /** @var $userModel User */
        $userModel = new User;
        $userModel->setScenario(self::SCENARIO_REGISTER);
        $userModel->attributes = $data;
        if ($userModel->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($userModel->save(false)) {
                    $data['user_id'] = isset($userModel->id) ? $userModel->id : null;

                    /** @var $sleepingPositionModel SleepingPosition */
                    $sleepingPositionModel = new SleepingPosition;
                    $sleepingPositionModel->attributes = isset($data['sleeping_position']) ? $data['sleeping_position'] : null;
                    $sleepingPositionModel->user_id = $data['user_id'];

                    /** @var $reasonUsingMatrixModel ReasonUsingMatrix */
                    $reasonUsingMatrixModel = new ReasonUsingMatrix;
                    $reasonUsingMatrixModel->attributes = isset($data['reason_using_matrix']) ? $data['reason_using_matrix'] : null;
                    $reasonUsingMatrixModel->user_id = $data['user_id'];

                    /** @var $deviceModel Device */
                    $deviceModel = new Device;
                    $deviceModel->attributes = isset($data['device']) ? $data['device'] : null;
                    $deviceModel->user_id = $data['user_id'];

                    /** @var $profileModel Profile */
                    $profileModel = new Profile;
                    $profileModel->attributes = $data;
                    $socialNetworksResponseData = [];
                    if (isset($data['social_networks'])) {
                        foreach ($data['social_networks'] as $socialNetwork) {

                            /** @var $socialNetworkModel SocialNetwork */
                            $socialNetworkModel = new SocialNetwork;
                            $socialNetworkModel->setScenario(self::SCENARIO_REGISTER);
                            $socialNetworkModel->attributes = $socialNetwork;
                            $socialNetworkModel->user_id = $data['user_id'];
                            if (!SocialNetwork::existSocialNetwork($userModel->id,
                                $socialNetwork['social_network_type'])
                            ) {
                                if ($socialNetworkModel->save()) {
                                    $socialNetworksResponseData[] = $socialNetworkModel;
                                }
                            }
                        }
                    }

                    /** @var  $settingNotification SettingNotification */
                    $settingNotification = new SettingNotification();
                    /** Saving default setting of notification for new register user */
                    $settingNotification->createDefaultRecordForNewRegisterUser($data['user_id']);

                    if ($sleepingPositionModel->validate()
                        && $reasonUsingMatrixModel->validate()
                        && $profileModel->validate()
                        && $deviceModel->validate()
                    ) {
                        $sleepingPositionModel->save(false);
                        $reasonUsingMatrixModel->save(false);
                        $deviceModel->save(false);
                        $profileModel->save(false);
                        $transaction->commit();

                        return [
                            'token'               => $userModel->getJWT(),
                            'user'                => $userModel,
                            'profile'             => $profileModel,
                            'device'              => $deviceModel,
                            'sleeping_position'   => $sleepingPositionModel,
                            'reason_using_matrix' => $reasonUsingMatrixModel,
                            'social_network'      => $socialNetworksResponseData,
                        ];
                    } else {
                        $errors = array_merge(
                            $userModel->errors,
                            $reasonUsingMatrixModel->errors,
                            $sleepingPositionModel->errors,
                            $deviceModel->errors,
                            $profileModel->errors
                        );
                        self::validationExceptionFirstMessage($errors);
                    }
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new HttpException(422, $e->getMessage());
            }
        } else {
            self::validationExceptionFirstMessage($userModel->errors);
        }
        throw new HttpException(500, 'Internal server error.');
    }

    /**
     * Returns username
     *
     * @return string
     */
    public function getUsername()
    {
        return ucfirst(strtolower($this->firstname)) . ucfirst(strtolower($this->lastname));
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Returns id user
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Finds user by id
     *
     * @param int|string $id
     * @return null|static
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * Adds token in the black list
     *
     * @param $token
     * @return bool
     */
    public static function addBlackListToken($token)
    {
        if ($token) {
            if (self::isBlocked($token)) {
                return true;
            }
            $block = new Block();
            $values = [
                'user_id'    => User::getPayload($token, 'jti'),
                'expired_at' => User::getPayload($token, 'exp'),
                'token'      => $token
            ];
            $block->attributes = $values;
            return $block->save();
        }
        return false;
    }

    /**
     * Check the token for the block
     *
     * @param $token
     * @return bool
     */
    public static function isBlocked($token)
    {
        if (Block::find()->where(['token' => $token])->one()) {
            return true;
        }
        return false;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $hash = $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Returns password hash
     *
     * @param string $password
     * @return string
     */
    public function getPasswordHash($password)
    {
        return Yii::$app->security->generatePasswordHash($password);
    }
}
