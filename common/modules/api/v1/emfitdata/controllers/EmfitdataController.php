<?php
namespace common\modules\api\v1\emfitdata\controllers;

use Yii;
use yii\rest\ActiveController;
use common\models\Awakening;
use common\models\CalcData;
use common\models\HeartFlex;
use common\models\HrvData;
use common\models\HrvRmssdData;
use common\models\SleepData;
use common\models\Movement;
use common\models\SleepCycle;
use common\models\SleepQuality;
use common\models\Stress;

/**
 * Class EmfitdataController
 * @package common\modules\api\v1\emfitdata\controllers
 */
class EmfitdataController extends ActiveController
{

    public $modelClass = 'app\models\VitalParams';
    /**
     * @put data in file
     */
    public function actionGetdata()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $data = json_encode($request->bodyParams);
            date_default_timezone_set('europe/kiev');
            $filename = date('Y:m:d-H:i:s', time());
            $myfile = fopen(getcwd() . '/tmp/' . $filename . ".txt", "w");
//            fwrite($myfile, 'File created!!!)))'."\n");
            fwrite($myfile, $data."\n");
//            fwrite($myfile, 'End of input' . "\n");
            fclose($myfile);
            return 'Json Saved';
        }
    }

    /**
     * @return array
     */
    public function actionParseData()
    {

        $AwakeningsModel = new Awakening;
        $CalcDataModel = new CalcData;
        $HrvDataModel = new HrvData;
        $HrvRmssdDataModel = new HrvRmssdData;
        $SleepDataModel = new SleepData;
        $MovementsModel = new Movement;
        $SleepCyclesModel = new SleepCycle;
        $SleepQualityModel = new SleepQuality;
        $StressModel = new Stress;

        $path = getcwd() . '/tmp/';
        $dir = scandir($path);
        foreach($dir as $key => $str){
            $file = file_get_contents($path . $str);


            $file_parts = pathinfo($str);

            if($file_parts['extension'] == 'txt') {

                $json = json_decode($file, true);




//            $SleepQualityModel->user_id                 = $json['user_id'];
            $SleepQualityModel->id                 = null;
            $SleepQualityModel->user_id                 = 2;
            $SleepQualityModel->from                    = $json['from'];
            $SleepQualityModel->to                      = $json['to'];
            $SleepQualityModel->date                    = strtotime($json['from']);
            $SleepQualityModel->sleep_score             = $json['sleep_score'];
            $SleepQualityModel->duration                = $json['duration'];
            $SleepQualityModel->duration_in_bed         = $json['duration_in_bed'];
            $SleepQualityModel->duration_awake          = $json['duration_awake'];
            $SleepQualityModel->duration_in_sleep       = $json['duration_in_sleep'];
            $SleepQualityModel->duration_in_rem         = $json['duration_in_rem'];
            $SleepQualityModel->duration_in_light       = $json['duration_in_light'];
            $SleepQualityModel->duration_in_deep        = $json['duration_in_deep'];
            $SleepQualityModel->duration_sleep_onset    = $json['duration_sleep_onset'];
            $SleepQualityModel->bedexit_duration        = $json['bedexit_duration'];
            $SleepQualityModel->bedexit_count           = $json['bedexit_count'];
            $SleepQualityModel->tossnturn_count         = $json['tossnturn_count'];
            $SleepQualityModel->fm_count                = $json['fm_count'];
            $SleepQualityModel->awakenings              = $json['awakenings'];
            $SleepQualityModel->isNewRecord             = true;
            $SleepQualityModel->save();


                if(!empty($json['calc_data'])) {
                    $jsonCalcData = json_decode($json['calc_data'], true);
                    $currentStep = $jsonCalcData[0][0];
                    foreach ($jsonCalcData as $k => $m) {
                        if ((int)$m[0] - (int)$currentStep >= 6000 || $k == 0) {
                            $currentStep = $m[0];
                            $CalcDataModel->id                  = null;
                            $CalcDataModel->user_id             = $json['user_id'];
                            $CalcDataModel->timestamp           = $this->checkData($m[0]) ? $m[0] : null;
                            $CalcDataModel->heart_rate          = $this->checkData($m[1]) ? $m[1] : null;
                            $CalcDataModel->respiration_rate    = $this->checkData($m[2]) ? $m[2] : null;
                            $CalcDataModel->activity            = $this->checkData($m[3]) ? $m[3] : null;
                            $CalcDataModel->isNewRecord         = true;
                            $CalcDataModel->save();
                        }
                    }
                }else{
                    continue;
                }

                if(!empty($json['sleep_data'])) {
                    $jsonSleepData = json_decode($json['sleep_data'], true);
                    foreach ($jsonSleepData as $k => $m) {
                        $SleepDataModel->id             = null;
                        $SleepDataModel->user_id        = $json['user_id'];
                        $SleepDataModel->timestamp      = $this->checkData($m[0]) ? $m[0] : null;
                        $SleepDataModel->sleep_type     = $this->checkData($m[1]) ? $m[1] : null;
                        $SleepDataModel->isNewRecord    = true;
                        $SleepDataModel->save();
                    }
                }else{
                    continue;
                }

               if(!empty($json['hrv_data'])) {
                    $jsonHrvData = json_decode($json['hrv_data'], true);
                    foreach ($jsonHrvData as $k => $m) {
                        $HrvDataModel->id               = null;
                        $HrvDataModel->user_id          = $json['user_id'];
                        $HrvDataModel->start_rmssd      = $this->checkData($m[0]) ? $m[0] : null;
                        $HrvDataModel->end_rmssd        = $this->checkData($m[1]) ? $m[1] : null;
                        $HrvDataModel->total_recovery   = $this->checkData($m[2]) ? $m[2] : null;
                        $HrvDataModel->recovery_ratio   = $this->checkData($m[3]) ? $m[3] : null;
                        $HrvDataModel->recovery_rate    = $this->checkData($m[4]) ? $m[4] : null;
                        $HrvDataModel->isNewRecord      = true;
                        $HrvDataModel->save();
                    }
                }else{
                    continue;
                }

                if(!empty($json['hrv_rmssd_data'])) {

                    $jsonHrvRmssdData = json_decode($json['hrv_rmssd_data'], true);
                    $currentStep = $jsonHrvRmssdData [0][0];
                    foreach ($jsonHrvRmssdData as $k => $m) {
                        if ((int)$m[0] - (int)$currentStep >= 6000 || $k == 0) {
                            $currentStep = $m[0];
                            $HrvRmssdDataModel->id              = null;
                            $HrvRmssdDataModel->user_id         = $json['user_id'];
                            $HrvRmssdDataModel->timestamp       = $this->checkData($m[0]) ? $m[0] : null;
                            $HrvRmssdDataModel->rmssd           = $this->checkData($m[1]) ? $m[1] : null;
                            $HrvRmssdDataModel->low_frequency   = $this->checkData($m[2]) ? $m[2] : null;
                            $HrvRmssdDataModel->high_frequency  = $this->checkData($m[3]) ? $m[3] : null;
                            $HrvRmssdDataModel->isNewRecord     = true;
                            $HrvRmssdDataModel->save();
                        }
                    }
                }else{
                    continue;
                }

            }






        }


//        $request = Yii::$app->request;
//        if ($request->isPost) {
//            $data = json_encode($request->bodyParams);
//         print_r($data);
//        }
    }



    protected function checkData($data){
        if(!empty($data) && !is_null($data) && isset($data)){
            return $data;
        }
        return false;
    }
}
