<?php

namespace console\controllers;

use console\models\Exmo;
use yii\console\Controller;

class RatesController extends Controller
{
    /*
     * Запрос котировок валютных пар с Exmo
     */
    public function actionExmoRates(){
        $currArr = [
            'BTC_RUB'
        ];
        $exmoModel = new Exmo();
        $exmoModel->Rates($currArr);
    }
}