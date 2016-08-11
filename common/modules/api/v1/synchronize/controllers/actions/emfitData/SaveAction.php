<?php

namespace common\modules\api\v1\synchronize\controllers\actions\emfitData;

use common\modules\api\v1\synchronize\models\CalcData;
use common\modules\api\v1\synchronize\models\HrvData;
use common\modules\api\v1\synchronize\models\HrvRmssdData;
use common\modules\api\v1\synchronize\models\SleepData;
use common\modules\api\v1\synchronize\models\SleepQuality;
use Yii;
use yii\base\Exception;
use yii\console\ErrorHandler;
use yii\rest\Action;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use common\modules\api\v1\device\models\Device;
use common\modules\api\v1\user\models\User;

/**
 * Class SaveAction
 *
 * Action for saving emfit data in DB
 *
 * @package common\modules\api\v1\synchronize\controllers\actions\emfitData
 */
class SaveAction extends Action
{
    /**
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

            /** @var  $device Device.php */
            $device = Device::findOne(['sn' => $json['device']]);
            if (!$device) {
                throw new NotFoundHttpException('Device not found!');
            }

            $user = User::findOne(['id' => $device->user_id]);
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

                $hrvDataModel->saveHrvData($json['hrv_data'], $user->id);

            }

            /** Saving Rmssd Data in DB */
            if (!empty($json['hrv_rmssd_data'])) {

                /** @var  $hrvRmssdDataModel HrvRmssdData.php */
                $hrvRmssdDataModel = new HrvRmssdData();

                $hrvRmssdDataModel->saveRmssdData($json['hrv_rmssd_data'], $user->id);
            }

            $transaction->commit();
            return ['status' => 'success', 'message' => 'Saving was successful'];

        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (Exception $e) {
            $transaction->rollBack();

            \Yii::error(ErrorHandler::convertExceptionToString($e), \Yii::$app->params['synchronize']['emfit_data']['loggerCategory']);

            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
    }
}
