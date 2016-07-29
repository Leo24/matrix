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

        $str = file_get_contents(getcwd() . '/tmp/2016:07:28-09:06:52.txt');

        $json = json_decode($str, true);
        $jsonSleepData = json_decode($json['sleep_data'], true);
        $jsonCalcData = json_decode($json['calc_data'], true);
        $jsonHrvData = json_decode($json['hrv_data'], true);
        $jsonHrvRmssdData = json_decode($json['hrv_rmssd_data'], true);

        $currentStep = $jsonCalcData[0][0];
        foreach($jsonCalcData as $k => $m){
            if($m[0] - $currentStep >= 6000  || $k == 0){
                $currentStep = $m[0];
                $CalcDataModel->id = null;
                $CalcDataModel->user_id = $json['user_id'];
                $CalcDataModel->timestamp = $this->checkData($m[0]) ? $m[0] : null;
                $CalcDataModel->heart_rate = $this->checkData($m[1]) ? $m[1] : null;
                $CalcDataModel->respiration_rate = $this->checkData($m[2]) ? $m[2] : null;
                $CalcDataModel->activity = $this->checkData($m[3]) ? $m[3] : null;
                $CalcDataModel->isNewRecord = true;
                $CalcDataModel->save();
            }
        }
        foreach($jsonSleepData as $k => $m){
                $SleepDataModel->id = null;
                $SleepDataModel->user_id = $json['user_id'];
                $SleepDataModel->timestamp = $this->checkData($m[0]) ? $m[0] : null;
                $SleepDataModel->sleep_type = $this->checkData($m[1]) ? $m[1] : null;
                $SleepDataModel->isNewRecord = true;
                $SleepDataModel->save();
        }

        foreach($jsonHrvData as $k => $m){
            $HrvDataModel->id = null;
            $HrvDataModel->user_id = $json['user_id'];
            $HrvDataModel->start_rmssd = $this->checkData($m[0]) ? $m[0] : null;
            $HrvDataModel->end_rmssd = $this->checkData($m[1]) ? $m[1] : null;
            $HrvDataModel->total_recovery = $this->checkData($m[2]) ? $m[2] : null;
            $HrvDataModel->recovery_ratio = $this->checkData($m[3]) ? $m[3] : null;
            $HrvDataModel->recovery_rate = $this->checkData($m[4]) ? $m[4] : null;
            $HrvDataModel->isNewRecord = true;
            $HrvDataModel->save();
        }

        $currentStep = $jsonHrvRmssdData [0][0];
        foreach($jsonHrvRmssdData as $k => $m){
            if($m[0] - $currentStep >= 6000  || $k == 0) {
                $currentStep = $m[0];
                $HrvRmssdDataModel->id = null;
                $HrvRmssdDataModel->user_id = $json['user_id'];
                $HrvRmssdDataModel->timestamp = $this->checkData($m[0]) ? $m[0] : null;
                $HrvRmssdDataModel->rmssd = $this->checkData($m[1]) ? $m[1] : null;
                $HrvRmssdDataModel->low_frequency = $this->checkData($m[2]) ? $m[2] : null;
                $HrvRmssdDataModel->high_frequency = $this->checkData($m[3]) ? $m[3] : null;
                $HrvRmssdDataModel->isNewRecord = true;
                $HrvRmssdDataModel->save();
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
