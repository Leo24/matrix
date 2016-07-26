<?php
namespace common\models;

use Firebase\JWT\JWT;
use yii\web\UnauthorizedHttpException;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use Yii;

/**
 * Class User
 * @package common\modules\api\v1\authorization\models
 */
class User extends ActiveRecord implements IdentityInterface
{

    const UNAUTHORIZED_INCORRECT_CODE = 12;
    const UNAUTHORIZED_EXPIRED_CODE = 13;

    /**
     * Token expire
     * @var int
     */
    protected $tokenExpire = 3600; // 1 hour

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
                'expired_at' => date('Y-m-d H:i:s', User::getPayload($token, 'exp')),
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
        return $this->token;
    }

    /**
     * Validates user token
     *
     * @param string $token
     * @return bool
     */
    public function validateAuthKey($token)
    {
        return $this->getAuthKey() === $token;
    }

    /**
     * Validates password
     *
     * @param  string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
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
     * Table name
     * @return string
     */
    public static function tableName()
    {
        return '{{%user}}';
    }
}
