<?php

namespace common\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\web\HttpException;
use yii\web\IdentityInterface;
use yii\web\UnauthorizedHttpException;
use yii\behaviors\TimestampBehavior;
use Firebase\JWT\JWT;

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
 * @package common\models
 */
class User extends ActiveRecord implements IdentityInterface
{
    const UNAUTHORIZED_INCORRECT_CODE = 12;
    const UNAUTHORIZED_EXPIRED_CODE = 13;
    const UNAUTHORIZED_BLOCK_CODE = 14;
    const INTERNAL_ERROR_CODE = 22;
    const VALIDATION_EXCEPTION_CODE = 21;

    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER = 'register';
    const SCENARIO_UPDATE_PASSWORD = 'password';

    const TOKEN_EXPIRE_DAYS = 7;

    const ALGORITHM = 'HS256';
    const TYP = 'JWT';

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
            [['email', 'password', 'confirm'], 'required', 'on' => self::SCENARIO_REGISTER],
            [['current_password', 'password', 'confirm'], 'required', 'on' => self::SCENARIO_UPDATE_PASSWORD],
            [
                'confirm',
                'compare',
                'compareAttribute' => 'password',
                'on' => [self::SCENARIO_REGISTER, self::SCENARIO_UPDATE_PASSWORD]
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
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'username' => Yii::t('app', 'User name'),
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
        if(is_array($modelErrors) && !empty($modelErrors)) {
            $fields = array_keys($modelErrors);
            $first_message = current($modelErrors[$fields[0]]);
            throw new HttpException(422, "Validation exception: {$first_message}",
                $code ? self::VALIDATION_EXCEPTION_CODE : 0);
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
        $userModel = new User;
        $userModel->setScenario(self::SCENARIO_REGISTER);
        $userModel->attributes = $data;
        if ($userModel->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($userModel->save(false)) {
                    $data['user_id'] = isset($userModel->id) ? $userModel->id : null;
                    $sleepingPositionModel = new SleepingPosition;
                    $sleepingPositionModel->attributes = isset($data['sleeping_position']) ? $data['sleeping_position'] : null;
                    $sleepingPositionModel->user_id = $data['user_id'];
                    $reasonUsingMatrixModel = new ReasonUsingMatrix;
                    $reasonUsingMatrixModel->attributes = isset($data['reason_using_matrix']) ? $data['reason_using_matrix'] : null;
                    $reasonUsingMatrixModel->user_id = $data['user_id'];
                    $deviceModel = new Device;
                    $deviceModel->attributes = isset($data['device']) ? $data['device'] : null;
                    $deviceModel->user_id = $data['user_id'];
                    $profileModel = new Profile;
                    $profileModel->attributes = $data;
                    $socialNetworksResponseData = [];
                    if (isset($data['social_networks'])) {
                        foreach ($data['social_networks'] as $socialNetwork) {
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
                            'token' => $userModel->getJWT(),
                            'user' => $userModel,
                            'profile' => $profileModel,
                            'device' => $deviceModel,
                            'sleeping_position' => $sleepingPositionModel,
                            'reason_using_matrix' => $reasonUsingMatrixModel,
                            'social_network' => $socialNetworksResponseData,
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
                throw new HttpException(422, $e->getMessage(), self::INTERNAL_ERROR_CODE);
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
     * Getter for secret key that's used for generation of JWT
     *
     * @return string secret key used to generate JWT
     */
    protected static function getSecretKey()
    {
        return Yii::$app->params['secretJWT'];
    }

    /**
     * Getter for "header" array that's used for generation of JWT
     *
     * @return array JWT Header Token param, see http://jwt.io/ for details
     */
    protected static function getHeaderToken()
    {
        return [
            'typ' => self::TYP,
            'alg' => self::getAlgorithm()
        ];
    }

    /**
     * Logins user by given JWT encoded string. If string is correctly decoded
     * - array (token) must contain 'jti' param - the id of existing user
     *
     * @param string $token access token to decode
     * @param null $type
     * @return mixed|null User model or null if there's no user
     * @throws UnauthorizedHttpException if anything went wrong
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $decodedArray = static::decodeJWT($token);
        if (self::isBlocked($token)) {
            throw new UnauthorizedHttpException('Token is blocked', self::UNAUTHORIZED_BLOCK_CODE);
        }
        // If there's no jti param - exception
        if (!isset($decodedArray['jti'])) {
            throw new UnauthorizedHttpException('Unauthorized');
        }
        // JTI is unique identifier of user.
        // For more details: https://tools.ietf.org/html/rfc7519#section-4.1.7
        $id = $decodedArray['jti'];

        return static::findByJTI($id);
    }

    /**
     * Decode JWT token
     *
     * @param string $token access token to decode
     * @return array decoded token
     * @throws UnauthorizedHttpException
     */
    public static function decodeJWT($token)
    {
        $secret = static::getSecretKey();
        $errorText = 'Incorrect token';
        $code = User::UNAUTHORIZED_INCORRECT_CODE;
        // Decode token and transform it into array.
        // Firebase\JWT\JWT throws exception if token can not be decoded
        try {
            $decoded = JWT::decode($token, $secret, [static::getAlgorithm()]);
        } catch (\Exception $e) {
            if ($e->getMessage() == 'Expired token') {
                $errorText = 'Expired token';
                $code = User::UNAUTHORIZED_EXPIRED_CODE;
            }
            throw new UnauthorizedHttpException($errorText, $code);
        }
        $decodedArray = (array)$decoded;

        return $decodedArray;
    }

    /**
     * Finds User model using static method findOne
     * Override this method in model if you need to complicate id-management
     *
     * @param integer $id if of user to search
     * @return mixed User model
     * @throws UnauthorizedHttpException if model is not found
     */
    public static function findByJTI($id)
    {
        $model = static::findOne($id);
        $errorText = "Incorrect token";
        // Throw error if user is missing
        if (empty($model)) {
            throw new UnauthorizedHttpException($errorText);
        }
        return $model;
    }

    /**
     * Getter for encryption algorithm used in JWT generation and decoding
     * Override this method to set up other algorytm.
     *
     * @return string needed algorithm
     */
    public static function getAlgorithm()
    {
        return self::ALGORITHM;
    }

    /**
     * Returns some 'id' to encode to token. By default is current model id.
     *
     * If you override this method, be sure that findByJTI is updated too
     * @return integer any unique integer identifier of user
     */
    public function getJTI()
    {
        //use primary key for JTI
        return $this->getPrimaryKey();
    }

    /**
     * Encodes model data to create custom JWT with model.id set in it
     *
     * @param  array $payloads payloads data to set, default value is empty array.
     * See registered claim names for payloads at https://tools.ietf.org/html/rfc7519#section-4.1
     * @return sting encoded JWT
     */
    public function getJWT($payloads = [])
    {
        $secret = static::getSecretKey();
        // Merge token with presets not to miss any params in custom
        // configuration
        $token = array_merge($payloads, static::getHeaderToken());
        // Set up id user
        $token['jti'] = $this->getJTI();
        //set exp if not isset
        if (!isset($token['exp'])) {
            //default value is an hour from now
            $token['exp'] = time() + $this->getTokenExpire();
        }
        return JWT::encode($token, $secret, static::getAlgorithm());
    }

    /**
     * Returns token expire period
     *
     * @return int
     */
    public function getTokenExpire()
    {
        return 3600 * 24 * self::TOKEN_EXPIRE_DAYS;
    }

    /**
     * Get payload data in a JWT string
     *
     * @param string $token
     * @param string|null $payload_id Payload ID that want to return, the default value is NULL. If NULL it will return all the payloads data
     * @return mixed payload data
     */
    public static function getPayload($token, $payload_id = null)
    {
        $decoded_array = static::decodeJWT($token);
        if ($payload_id != null) {
            return isset($decoded_array[$payload_id]) ? $decoded_array[$payload_id] : null;
        } else {
            return $decoded_array;
        }
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
            if (Block::find()->where(['token' => $token])->one()) {
                return true;
            }
            $block = new Block();
            $values = [
                'user_id' => User::getPayload($token, 'jti'),
                'expired_at' => User::getPayload($token, 'exp'),
                'token' => $token
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
     * Returns AuthKey user
     *
     * @return mixed
     */
    public function getAuthKey()
    {
        $headerAuthorizationKey = Yii::$app->getRequest()->getHeaders()->get('Authorization');
        if ($headerAuthorizationKey !== null && preg_match("/^Bearer\\s+(.*?)$/", $headerAuthorizationKey, $matches)) {
            if (isset($matches[1])) {
                return $matches[1];
            }
        }
        return false;
    }

    /**
     * Validates user token
     *
     * @param string $token
     * @return bool
     */
    public function validateAuthKey($token)
    {
        return (bool)JWT::decode($token, self::getSecretKey(), [static::getAlgo()]);
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
