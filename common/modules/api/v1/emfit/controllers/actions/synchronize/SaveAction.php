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
            if (!empty($json['calc_data'])) {

                /** @var  $calcDataModel CalcData.php */
                $calcDataModel = new CalcData();

                $calcDataModel->saveCalcData($json['calc_data'], $user->id);
            }

            /** Saving Sleep Data in DB */
            if (!empty($json['sleep_data'])) {

                /** @var  $sleepDataModel SleepData.php */
                $sleepDataModel = new SleepData();

                $sleepDataModel->saveSleepData($json['sleep_data'], $user->id);

            }

            /** Saving Hrv Data in DB */
            if (!empty($json['hrv_data'])) {

                /** @var  $hrvDataModel HrvData.php */
                $hrvDataModel = new HrvData();

                $hrvDataModel->saveHrvData($json['hrv_data'], $user->id, $json['from']);

            }

            /** Saving Rmssd Data in DB */
            if (!empty($json['hrv_rmssd_data'])) {

                /** @var  $hrvRmssdDataModel HrvRmssdData.php */
                $hrvRmssdDataModel = new HrvRmssdData();

                $hrvRmssdDataModel->saveRmssdData($json['hrv_rmssd_data'], $user->id);
            }

            /** Saving Movement information in DB */
            if (!empty($json['tossnturn_data'])) {

                /** @var  $movementModel Movement.php */
                $movementModel = new Movement();

                $movementModel->saveMovement($json['tossnturn_data'], $user->id);
            }

            $transaction->commit();
            return ['status' => 'success', 'message' => 'Saving was successful'];

        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (Exception $e) {
            $transaction->rollBack();

            \Yii::error(ErrorHandler::convertExceptionToString($e), \Yii::$app->params['logger']['synchronize_emfit_data']['category']);

            throw new ServerErrorHttpException('Failed to synchronize emfit data for unknown reason.');
        }
    }
}