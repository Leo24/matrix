<?php

namespace common\modules\api\v1\emfit\controllers\actions\synchronize;

use common\modules\api\v1\emfit\models\CalcData;
use common\modules\api\v1\emfit\models\HrvData;
use common\modules\api\v1\emfit\models\HrvRmssdData;
use common\modules\api\v1\emfit\models\Movement;
use common\modules\api\v1\emfit\models\SleepData;
use common\modules\api\v1\emfit\models\SleepQuality;
use common\modules\api\v1\user\models\Device;
use Yii;
use yii\base\Exception;
use yii\console\ErrorHandler;
use yii\helpers\Json;
use yii\rest\Action;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use common\modules\api\v1\user\models\User;

/**
 * Class SaveAction
 * @package common\modules\api\v1\emfit\controllers\actions\synchronize
 */
class SaveAction extends Action
{
    /**
     * Action for saving emfit data in DB
     *
     * @parse data into database
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $json = Yii::$app->request->bodyParams;

            \Yii::error($json, \Yii::$app->params['logger']['emfit_data']['category']);

//            /** @var  $device Device.php */
//            $device = Device::findOne(['sn' => $json['device']]);
//            if (!$device) {
//                throw new NotFoundHttpException('Device not found!');
//            }

            // todo заглушка временная
            $user = User::findOne(['id' => 3]);
            if (!$user) {
                throw new NotFoundHttpException('User not found!');
            }

            /** @var  $sleepQualityModel SleepQuality.php */
            $sleepQualityModel = new $this->modelClass();
            /** Saving Sleep Quality data in DB */
            $sleepQualityModel->saveSleepQualityData($json, $user->id);

            /** Saving Calc Data in DB */
            $calcData = Json::decode($json['calc_data']);
            if (!empty($calcData)) {

                /** @var  $calcDataModel CalcData.php */
                $calcDataModel = new CalcData();

                $calcDataModel->saveCalcData($calcData, $user->id);
            }

            /** Saving Sleep Data in DB */
            $sleepData = Json::decode($json['sleep_data']);
            if (!empty($sleepData)) {

                /** @var  $sleepDataModel SleepData.php */
                $sleepDataModel = new SleepData();

                $sleepDataModel->saveSleepData($sleepData, $user->id);

            }

            /** Saving Hrv Data in DB */
            $hrvData = Json::decode($json['hrv_data']);
            if (!empty($hrvData)) {

                /** @var  $hrvDataModel HrvData.php */
                $hrvDataModel = new HrvData();

                $hrvDataModel->saveHrvData($hrvData, $user->id, $json['from']);

            }

            /** Saving Rmssd Data in DB */
            $hrvRmssdData = Json::decode($json['hrv_rmssd_data']);
            if (!empty($hrvRmssdData)) {

                /** @var  $hrvRmssdDataModel HrvRmssdData.php */
                $hrvRmssdDataModel = new HrvRmssdData();

                $hrvRmssdDataModel->saveRmssdData($hrvRmssdData, $user->id);
            }

            /** Saving Movement information in DB */
            $tossnturnData = Json::decode($json['tossnturn_data']);
            if (!empty($tossnturnData)) {

                /** @var  $movementModel Movement.php */
                $movementModel = new Movement();

                $movementModel->saveMovement($tossnturnData, $user->id);
            }

            $transaction->commit();
            return ['status' => 'success', 'message' => 'Saving was successful'];

        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (Exception $e) {
            $transaction->rollBack();

            \Yii::error(ErrorHandler::convertExceptionToString($e), \Yii::$app->params['logger']['error_emfit_data']['category']);

            throw new ServerErrorHttpException('Failed to synchronize emfit data for unknown reason.');
        }
    }
}
