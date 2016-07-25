<?php
namespace common\modules\api\v1\note\controllers;

use Yii;
use yii\rest\ActiveController;

/**
 * Notes controller
 */
class NoteController extends ActiveController
{

    public $modelClass = 'app\models\Notes';


    /*
     *
     *
     */

    /**
     * @return array
     */
    public function actionIndex()
    {
        $foo = false;
        
    }
}