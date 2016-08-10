<?php

namespace common\modules\api\v1\user\traits;

use Yii;
use common\modules\api\v1\user\models\User;
use yii\web\UnauthorizedHttpException;
use Firebase\JWT\JWT;

trait AuthorizationJwtTrait
{
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
            'typ' => 'JWT',
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
        if (User::isBlocked($token)) {
            throw new UnauthorizedHttpException('Token is blocked');
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

        // Decode token and transform it into array.
        // Firebase\JWT\JWT throws exception if token can not be decoded
        try {
            $decoded = JWT::decode($token, $secret, [static::getAlgorithm()]);
        } catch (\Exception $e) {
            if ($e->getMessage() == 'Expired token') {
                $errorText = 'Expired token';
            }
            throw new UnauthorizedHttpException($errorText);
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
        $model = User::findOne($id);
        // Throw error if user is missing
        if (empty($model)) {
            throw new UnauthorizedHttpException('Incorrect token');
        }
        return $model;
    }

    /**
     * Getter for encryption algorithm used in JWT generation and decoding
     * Override this method to set up other algorithm.
     *
     * @return string needed algorithm
     */
    public static function getAlgorithm()
    {
        return Yii::$app->params['algorithmJWT'];
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
     * @return string encoded JWT
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
        return 3600 * 24 * Yii::$app->params['tokenExpireDays'];
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
        return (bool)JWT::decode($token, self::getSecretKey(), [static::getAlgorithm()]);
    }
}
