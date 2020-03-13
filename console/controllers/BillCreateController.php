<?php


namespace console\controllers;
use yii\console\Controller;
use console\models\Bills;

class BillCreateController extends Controller
{
    /*
     * Создаем счет
     */
    public function actionBillcreate(){
        $model = new Bills();
        $model->Billcreate();
    }

    /*
     * Мониторим состояние счета Киви
     */
    public function actionBillmonitor(){
        $model = new Bills();
        $model->Billmonitor();
    }

    /*
     * Exmo monitoring
     */
    public function actionExmomonitor(){
        $model = new Bills();
        $model->Exmomonitor();

    }
}