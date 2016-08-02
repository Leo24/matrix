<?php

namespace common\models;

use Firebase\JWT\JWT;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\web\UnauthorizedHttpException;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use yii\web\HttpException;
use Yii;

/**
 * Class User
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

    public $confirm;
    public $firstname;
    public $lastname;

    /**
     * Token expire
     * @var int
     */
    protected $tokenExpire = 3600 * 24 * 7; // 7 days

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password', 'confirm'], 'required', 'on' => 'register'],
            ['confirm', 'compare', 'compareAttribute' => 'password', 'on' => 'register'],
            [['password', 'email'], 'string', 'max' => 255],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => self::className(), 'message' => Yii::t('app', 'Email exists')],
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
    public function scenarios()
    {
        return [
            self::SCENARIO_LOGIN => ['email', 'password'],
            self::SCENARIO_REGISTER => ['email', 'password', 'username', 'confirm', 'firstname', 'lastname'],
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
        return ['userProfile', 'sleepPosition', 'reasonUsingMatrix'];
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
    public function getReasonUsingMatrix()
    {
        return $this->hasOne(ReasonUsingMatrix::class, ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        parent::beforeSave($insert);

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
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        unset($this->password);
    }

    /**
     * Table name
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * Attribute labels
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Password',
            'username' => 'User name',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'last_login' => 'Last login',
            'sleeping_position' => 'Sleeping position'
        ];
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

        $sleepingPositionModel = new SleepingPosition;
        $sleepingPositionModel->attributes = isset($data['sleeping_position']) ? $data['sleeping_position'] : null;

        $reasonUsingMatrixModel = new ReasonUsingMatrix;
        $reasonUsingMatrixModel->attributes = isset($data['reason_using_matrix']) ? $data['reason_using_matrix'] : null;

        $profileModel = new Profile;
        $profileModel->attributes = $data;

        if ($userModel->validate()
            && $sleepingPositionModel->validate()
            && $reasonUsingMatrixModel->validate()
            && $profileModel->validate()
        ) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $userModel->setPassword($data['password']);
                if ($userModel->save(false)) {
                    $sleepingPositionModel->user_id = $userModel->id;
                    $sleepingPositionModel->save(false);

                    $reasonUsingMatrixModel->user_id = $userModel->id;
                    $reasonUsingMatrixModel->save(false);

                    $profileModel->user_id = $userModel->id;
                    $profileModel->save(false);

                    $socialNetworksResponseData = [];
                    if (isset($data['social_networks'])) {
                        foreach ($data['social_networks'] as $socialNetwork) {
                            $socialNetworkModel = new SocialNetwork;
                            $socialNetworkModel->setScenario(self::SCENARIO_REGISTER);
                            $socialNetworkModel->attributes = $socialNetwork;
                            $socialNetworkModel->user_id = $userModel->id;

                            if (!SocialNetwork::existSocialNetwork($userModel->id, $socialNetwork['social_network_type'])) {
                                if ($socialNetworkModel->save()) {
                                    $socialNetworksResponseData[] = $socialNetworkModel;
                                }
                            }
                        }
                    }
                    $transaction->commit();

                    return [
                        'token' => $userModel->getJWT(),
                        'user' => $userModel,
                        'profile' => $profileModel,
                        'sleeping_position' => $sleepingPositionModel,
                        'reason_using_matrix' => $reasonUsingMatrixModel,
                        'social_network' => $socialNetworksResponseData
                    ];
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new HttpException(422, $e->getMessage(), self::INTERNAL_ERROR_CODE);
            }
        } else {
            // Validation errors
            /*
            return array_merge(
                $userModel->errors,
                $reasonUsingMatrixModel->errors,
                $sleepingPositionModel->errors
            );*/
            throw new HttpException(422, 'Validation exception', self::VALIDATION_EXCEPTION_CODE);
        }
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
     * @return string secret key used to generate JWT
     */
    protected static function getSecretKey()
    {
        return Yii::$app->params['secretJWT'];
    }

    /**
     * Getter for "header" array that's used for generation of JWT
     * @return array JWT Header Token param, see http://jwt.io/ for details
     */
    protected static function getHeaderToken()
    {
        return [
            'typ' => 'JWT',
            'alg' => self::getAlgo()
        ];
    }

    /**
     * Logins user by given JWT encoded string. If string is correctly decoded
     * - array (token) must contain 'jti' param - the id of existing user
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
            $decoded = JWT::decode($token, $secret, [static::getAlgo()]);
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
     * Getter for encryption algorytm used in JWT generation and decoding
     * Override this method to set up other algorytm.
     * @return string needed algorytm
     */
    public static function getAlgo()
    {
        return 'HS256';
    }

    /**
     * Returns some 'id' to encode to token. By default is current model id.
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
     * @param  array $payloads payloads data to set, default value is empty array. See registered claim names for payloads at https://tools.ietf.org/html/rfc7519#section-4.1
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
            $token['exp'] = time() + $this->tokenExpire;
        }
        return JWT::encode($token, $secret, static::getAlgo());
    }

    /**
     * Get payload data in a JWT string
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
     * Adds token in the black list;
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
     * @param int|string $id
     * @return null|static
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * Finds user by username
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Returns id user
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns AuthKey user
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
     * @param string $token
     * @return bool
     */
    public function validateAuthKey($token)
    {
        return (bool)JWT::decode($token, self::getSecretKey(), [static::getAlgo()]);
    }

    /**
     * Validates password
     * @param  string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

}
