<?php
namespace frontend\controllers;

use Yii;
use yii\rest\ActiveController;

//use frontend\models\VitalParams;

/**
 * Site controller
 */
class DataController extends ActiveController
{
//    public $enableCsrfValidation = false;
    public $modelClass = 'app\models\VitalParams';
    /**
     * @put data in file
     */    
    public function actionReceaveEmfitData()
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
}
