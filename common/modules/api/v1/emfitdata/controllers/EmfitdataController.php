<?php
namespace common\modules\api\v1\emfitdata\controllers;

use Yii;
use yii\rest\ActiveController;
use common\models\Awakening;
use common\models\CalcData;
use common\models\HeartFlex;
use common\models\HrvData;
use common\models\HrvRnssdData;
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
    $HrvRnssdDataModel = new HrvRnssdData;
    $SleepDataModel = new SleepData;
    $MovementsModel = new Movement;
    $SleepCyclesModel = new SleepCycle;
    $SleepQualityModel = new SleepQuality;
    $StressModel = new Stress;

        $str = file_get_contents(getcwd() . '/tmp/2016:07:28-09:06:52.txt');

        $json = json_decode($str, true);
        $jsonSleepData = json_decode($json['sleep_data'], true);
        $jsonCalcData = json_decode($json['calc_data'], true);

        $currentStep = $jsonCalcData[0][0];
        foreach($jsonCalcData as $m){
            if($m[0] - $currentStep === 6000 ){
                $currentStep = $m[0];
                $CalcDataModel->id = null;
                $CalcDataModel->user_id = $json['user_id'];
                $CalcDataModel->timestamp = $m[0];
                $CalcDataModel->heart_rate = $m[1];
                $CalcDataModel->respiration_rate = $m[2];
                $CalcDataModel->activity = $m[3];
                $CalcDataModel->isNewRecord = true;
                $CalcDataModel->save();
            }
        }

        foreach($jsonSleepData as $m){
            if($m[0] - $currentStep === 6000 ){
                $currentStep = $m[0];
                $SleepDataModel->id = null;
                
                
                $CalcDataModel->user_id = $json['user_id'];
                $CalcDataModel->timestamp = $m[0];
                $CalcDataModel->heart_rate = $m[1];
                $CalcDataModel->respiration_rate = $m[2];
                $CalcDataModel->activity = $m[3];
                $CalcDataModel->isNewRecord = true;
                $CalcDataModel->save();
                $foo = false;
            }


        }
        
        

        $jsonHrvData = json_decode($json['hrv_data'], true);
        $jsonHrvRmssdData = json_decode($json['hrv_rmssd_data'], true);
        $jsonTossnTurnData = json_decode($json['tossnturn_data'], true);

//        var_dump($json, 'json');
//        var_dump($jsonSleepData, 'jsonSleepData');
//        var_dump($jsonCalcData, 'jsonCalcData');
//        var_dump($jsonHrvData, 'jsonHrvData');
//        var_dump($jsonHrvRmssdData, 'jsonHrvRmssdData');
//        var_dump($jsonTossnTurnData, 'jsonTossnTurnData');


//        $request = Yii::$app->request;
//        if ($request->isPost) {
//            $data = json_encode($request->bodyParams);
//         print_r($data);
//        }
    }

}
