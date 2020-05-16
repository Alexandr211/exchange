<?php


namespace console\models;

use frontend\models\Rates;
use yii\base\Model;
use yii\db\StaleObjectException;

/**
 * Class Exmo
 * @package console\models
 */
class Exmo extends Model
{
    public function Rates($curr=[]){
        /*
         * $curr = ['BTC_RUB', ..];
        */
        if(!empty($curr)){
            $ch = curl_init();
            $url = "https://api.exmo.com/v1/ticker/";
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
            curl_setopt($ch,CURLOPT_TIMEOUT, 20);
            $response = curl_exec($ch);
            curl_close ($ch);
            $response_arr = json_decode($response,1);

            foreach($response_arr as $item_key=>$item_value){
                if(in_array($item_key, $curr, true)){
                    // пишем в базу результат !!!!!!!!!
                    $findCurr = Rates::find()->where(['cripto_name'=>$item_key])->one();
                    if(empty($findCurr)){
                        $ratesModel = new Rates();
                        $ratesModel->cripto_name = $item_key;
                        $ratesModel->curr_id = 1;
                        $ratesModel->curr_price_buy = $item_value['buy_price'];
                        $ratesModel->curr_price_sell = $item_value['sell_price'];
                        try {
                            $ratesModel->insert();
                        } catch (\Throwable $e) {
                            // здесь логи если что не так
                            \Yii::error(print_r($e,1), 'rates');
                        }
                    }else{
                        $findCurr->cripto_name = $item_key;
                        $findCurr->curr_price_buy = $item_value['buy_price'];
                        $findCurr->curr_price_sell = $item_value['sell_price'];
                        try {
                            $findCurr->update();
                        } catch (StaleObjectException $e) {
                            // здесь логи если что не так
                            \Yii::error($e, 'rates');
                        } catch (\Throwable $e) {
                            // здесь логи если что не так
                            \Yii::error($e, 'rates');
                        }
                    }
                //     print "curl response is:" . print_r($item_value,1);
                }
            }
        }
    }
}