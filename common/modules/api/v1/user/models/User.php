<?php

namespace common\modules\api\v1\user\models;

use Yii;
use yii\base\ErrorHandler;
use yii\base\Exception;
use yii\db\Exception as ExceptionDb;
use yii\db\ActiveRecord;
use yii\web\HttpException;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use common\modules\api\v1\authorization\models\Block;
use common\modules\api\v1\notification\models\Notification;
use common\modules\api\v1\settings\models\SettingNotification;
use common\modules\api\v1\user\traits\AuthorizationJwtTrait;
use yii\web\ServerErrorHttpException;

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
     * @throws ExceptionDb
     */
    private function validationExceptionFirstMessage($modelErrors, $code = false)
    {
        if (is_array($modelErrors) && !empty($modelErrors)) {
            $fields = array_keys($modelErrors);
            $first_message = current($modelErrors[$fields[0]]);
            throw new ExceptionDb("Validation exception: {$first_message}", $code);
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
    public function registerUser($data)
    {
        $this->setScenario(self::SCENARIO_REGISTER);
        $this->attributes = $data;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($this->save()) {

                /** @var $sleepingPositionModel SleepingPosition */
                $sleepingPositionModel = new SleepingPosition();
                /** Saving "sleeping position" information for new register user  */
                $sleepingPositionModel->saveSleepingPosition($data, $this->id);

                /** @var $reasonUsingMatrixModel ReasonUsingMatrix */
                $reasonUsingMatrixModel = new ReasonUsingMatrix();
                /** Saving "reason using matrix" information for new register user  */
                $reasonUsingMatrixModel->saveReasonUsingMatrix($data, $this->id);

                /** @var $deviceModel Device */
                $deviceModel = new Device();
                /** Saving device information  */
                $deviceModel->saveDevice($data, $this->id);

                /** @var  $settingNotification SettingNotification */
                $settingNotification = new SettingNotification();
                /** Saving default setting of notification for new register user */
                $settingNotification->createDefaultRecordForNewRegisterUser($this->id);

                /** @var $healthModel Health */
                $healthModel = new Health();
                /** Saving default Health information for new register user */
                $healthModel->createDefaultRecordForNewRegisterUser($this->id);

                /** @var $profileModel Profile */
                $profileModel = new Profile();
                /** Saving profile information for new register user */
                $profileModel->saveProfile($data, $this->id);

                $socialNetworksResponseData = [];
                if (isset($data['social_networks'])) {
                    /** Saving social_network information for new register user */
                    foreach ($data['social_networks'] as $socialNetwork) {
                        /** @var $socialNetworkModel SocialNetwork */
                        $socialNetworkModel = new SocialNetwork();
                        $socialNetworksResponseData[] = $socialNetworkModel->saveSocialNetwork($socialNetwork, $this->id);
                    }
                }

                $transaction->commit();

                return [
                    'token'               => $this->getJWT(),
                    'user'                => $this,
                    'profile'             => $profileModel,
                    'device'              => $deviceModel,
                    'sleeping_position'   => $sleepingPositionModel,
                    'reason_using_matrix' => $reasonUsingMatrixModel,
                    'social_network'      => $socialNetworksResponseData,
                ];
            } else {
                $this->validationExceptionFirstMessage($this->errors);
            }
        } catch (ExceptionDb $e) {
            $transaction->rollBack();
            throw new HttpException(422, $e->getMessage());
        } catch (Exception $e) {
            $transaction->rollBack();
            \Yii::error(ErrorHandler::convertExceptionToString($e), \Yii::$app->params['logger']['register_user']['category']);
            throw new ServerErrorHttpException('Failed to register user for unknown reason.');
        }

        throw new ServerErrorHttpException('Failed to register user for unknown reason.');
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
            /** @var $model Block */
            $model = new Block();
            $model->setScenario(Block::SCENARIO_CREATE_BLOCK);

            $values = [
                'user_id'    => User::getPayload($token, 'jti'),
                'expired_at' => User::getPayload($token, 'exp'),
                'token'      => $token
            ];
            $model->attributes = $values;
            return $model->save();
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
