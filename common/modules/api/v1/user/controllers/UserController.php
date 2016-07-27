<?php
namespace common\modules\api\v1\user\controllers;

use Yii;
use yii\rest\ActiveController;
use common\models\User;
use common\models\Profile;
use yii\web\HttpException;

/**
 * Class UserController
 * @package common\modules\api\v1\user\controllers
 */
class UserController extends ActiveController
{
    const REGISTRATION_SUCCESS_CODE = 20;
    const VALIDATION_EXCEPTION_CODE = 21;
    const INTERNAL_ERROR_CODE = 22;

    public $modelClass = 'app\models\User';

    /**
     * Action register a new user
     * @return array
     * @throws HttpException
     * @throws \yii\db\Exception
     */
    public function actionRegister()
    {
        $userModel = new User;
        $userModel->scenario = 'register';
        $userModel->attributes = Yii::$app->request->post();

        $userModel->username = ucfirst(strtolower(Yii::$app->request->post('firstname')))
                    . ucfirst(strtolower(Yii::$app->request->post('lastname')));

        $profileModel = new Profile;
        $profileModel->scenario = 'register';
        $profileModel->attributes = Yii::$app->request->post();

        if ($userModel->validate() && $profileModel->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $userModel->setPassword($userModel->password);
                if($userModel->save(false)) {
                    $profileModel->user_id = $userModel->id;
                }
                $profileModel->save(false);
                $transaction->commit();

                return [
                    'token' => $userModel->getJWT(),
                    'user' => $userModel
                ];
            } catch (\Exception $e) {
                $transaction->rollBack();

                throw new HttpException(422, $e->getMessage(), self::INTERNAL_ERROR_CODE);
            }
        } else {
            // Validation errors
            //return array_merge($userModel->errors, $profileModel->errors);

            throw new HttpException(422, 'Validation exception', self::VALIDATION_EXCEPTION_CODE);
        }
    }

}