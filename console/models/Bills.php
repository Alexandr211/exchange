<?php

namespace console\models;
use Exmo\Api\Request;
use Qiwi\Api\BillPaymentsException;
use yii\base\Model;
use common\models\Orders;

class Bills extends Model
{
    /*
     * for Qiwi
     */
    CONST SECRET_KEY = '';

    /*
     * for Exmo
     */
    CONST KEY_EXMO = '';
    CONST SECRET_EXMO = '';
    CONST KEY_EXMO_OUT = '';
    CONST SECRET_EXMO_OUT = '';

    public function Billcreate(){
        $nextWeek = time() + (7 * 24 * 60 * 60);
        $expirationDateTime = date (\DateTime::W3C, $nextWeek);

        $orders = Orders::find()->where(['code'=>'in the process'])->all();
        if(!empty($orders)){
            foreach($orders as $order){
                // create Bill Qiwi
                $billPayments = new \Qiwi\Api\BillPayments(self::SECRET_KEY);
                $billId = $order['id'];
                $fields = [
                    'amount' => $order['sum'],
                    'currency' => 'RUB',
                    'comment' => 'Безвозмездно',
                    'expirationDateTime' => $expirationDateTime
                ];

                /** @var \Qiwi\Api\BillPayments $billPayments */
                try {
                    $response = $billPayments->createBill($billId, $fields);
                } catch (BillPaymentsException $e) {
                    \Yii::error(print_r($e,1), 'qiwi');
                    //    echo print_r($e,1);
                }
                // теперь пишем ссылку на оплату в строку
                if(!empty($response['payUrl'])){
                    $updOrder = Orders::findOne($order['id']);
                    $updOrder->code = $response['payUrl'];
                    $updOrder->save();
                }else{
                    \Yii::error(print_r('Пустой ответ при создании счета',1), 'qiwi');
                    \Yii::error(print_r('Идентификатор счета: '.$order['id'],1), 'qiwi');
                }
            }
        }
    }

    public function Billmonitor(){
        $orders = Orders::find()->where(['NOT IN', 'code', 'in the process'])->andWhere(['IN', 'status', ['new', 'WAITING']])->all();
        foreach($orders as $order){
            // get Qiwi info
            $billPayments = new \Qiwi\Api\BillPayments(self::SECRET_KEY);
            $billId = $order['id'];
            /** @var \Qiwi\Api\BillPayments $billPayments */
            $response = $billPayments->getBillInfo($billId);
            if(isset($response['status']['value'])){
                $status = '';
                if($response['status']['value'] === 'WAITING'){
                    $status = 'WAITING';
                }
                if($response['status']['value'] === 'PAID'){
                    $status = 'PAID stage 1 of 5';
                }
                if($response['status']['value'] === 'REJECTED'){
                    $status = 'REJECTED';
                }
                if($response['status']['value'] === 'EXPIRED'){
                    $status = 'EXPIRED';
                }
                $updOrder = Orders::findOne($order['id']);
                $updOrder->status = $status;
                $updOrder->save();
            }else{
                \Yii::error(print_r('Пустой ответ при запросе информации о счете',1), 'qiwi');
                \Yii::error(print_r('Идентификатор счета: '.$order['id'],1), 'qiwi');
            }
        }
    }

    public function Exmomonitor(){
        $orders = Orders::find()->where(['NOT IN', 'code', 'in the process'])->andWhere(['NOT IN', 'status', ['new', 'WAITING', 'PAID', 'REJECTED', 'EXPIRED']])->all();

        if(!empty($orders)){
            foreach($orders as $order){
                if($order['status'] === 'PAID stage 1 of 5'){
                    $request = new Request(self::KEY_EXMO_OUT, self::SECRET_EXMO_OUT);
                    $response = $request->query('user_info');
                    if(isset($response['balances']['RUB'])){
                        if($order['sum'] < $response['balances']['RUB']){
                            \Yii::info(print_r('Выставим ордер на покупку',1), 'exmo');
                            \Yii::info(print_r('id заявки: '.$order['id'].'сумма '.$order['sum'],1), 'exmo');
                            \Yii::info(print_r('на счете Exmo: '.$response['balances']['RUB'],1), 'exmo');

                            $this->Exmobuybtc($order['sum'], $order['id']);
                        }else{
                        //    \Yii::info(print_r('Недостаточно средств на счете Exmo',1), 'exmo');
                        }
                    }else{
                        \Yii::error(print_r('Пустой ответ при запросе информации о счете',1), 'exmo');
                        \Yii::error(print_r('Идентификатор счета: '.$order['id'],1), 'exmo');
                    }
                }

                if($order['status'] === 'PAID stage 2 of 5'){
                    $this->Exmobtcbuyaccept($order['exmo_id'], $order['id']);
                }

                if($order['status'] === 'PAID stage 3 of 5'){
                    // выведем BTC на указанный кошелек
                    $this->Exmocriptoout($order['out_sum'], $order['wallet_id'], $order['id']);
                }

                if($order['status'] === 'PAID stage 4 of 5'){
                    $this->Criptooutaccept($order['exmo_out_id'], $order['id']);
                }
            }
        }
    }

    private function Exmobuybtc($sum, $order_id){
        if($sum){
            $params = [
                "pair"=>"BTC_RUB",
                "quantity"=>$sum,
                "price"=>0,
                "type"=>"market_buy_total"
            ];
            $request = new Request(self::KEY_EXMO_OUT, self::SECRET_EXMO_OUT);
            $response = $request->query('order_create', $params);
            if(!empty($response)){
                \Yii::info(print_r('Создание ордера на покупку BTC на Exmo '. 'order_id: '.$order_id,1), 'exmo');
                \Yii::info(print_r($response,1), 'exmo');
                if(!empty($response['order_id'])){
                    $updOrder = Orders::findOne($order_id);
                    $updOrder->status = 'PAID stage 2 of 5';
                    $updOrder->exmo_id = $response['order_id'];
                    if(!$updOrder->save()){
                        $updOrder = Orders::findOne($order_id);
                        $updOrder->status = 'ERROR while save';
                        $updOrder->save();
                    }
                }
            }
            if(!empty($response['error'])){
                $updOrder = Orders::findOne($order_id);
                $updOrder->status = $response['error'];
                $updOrder->save();
                \Yii::error(print_r('Ошибка при выставлении ордера на покупку Exmo '. $response['error'],1), 'exmo');
            }
        }
    }

    private function Exmobtcbuyaccept($exmo_order_id, $order_id){
        if($exmo_order_id){
            $params = [
                "order_id"=>$exmo_order_id
            ];
            $request = new Request(self::KEY_EXMO_OUT, self::SECRET_EXMO_OUT);
            $response = $request->query('order_trades', $params);
            if(!empty($response['trades'])){
                \Yii::info(print_r('Реализована сделка на покупку BTC на Exmo '. 'order_id: '.$order_id,1), 'exmo');
                if(!empty($response['in_amount'])){
                    $updOrder = Orders::findOne($order_id);
                    $updOrder->status = 'PAID stage 3 of 5';
                    $updOrder->out_sum = $response['in_amount'];
                    $updOrder->save();
                }
            }
        }
    }

    private function Exmocriptoout($amount, $address, $invoice){
        $params = [
            "amount"=>$amount,
            "currency"=>"BTC",
            "address"=>$address,
            "invoice"=>$invoice
        ];
        $request = new Request(self::KEY_EXMO_OUT, self::SECRET_EXMO_OUT);
        $response = $request->query('withdraw_crypt', $params);
        if(!empty($response)){
            \Yii::info(print_r('Создание ордера на вывод BTC '. 'order_id: '.$invoice,1), 'exmo');
            \Yii::info(print_r($response,1), 'exmo');
            if(!empty($response['task_id'])){
                $updOrder = Orders::findOne($invoice);
                $updOrder->status = 'PAID stage 4 of 5';
                $updOrder->exmo_out_id = $response['task_id'];
                if(!$updOrder->save()){
                    $updOrder = Orders::findOne($invoice);
                    $updOrder->status = 'ERROR while out and save ';
                    $updOrder->save();
                }
            }
            if(!empty($response['error'])){
                $updOrder = Orders::findOne($invoice);
                $updOrder->status = $response['error'];
                $updOrder->save();
                \Yii::error(print_r('Ошибка при выводе биткойна '. $response['error'],1), 'exmo');
            }
        }
    }

    private function Criptooutaccept($task_id, $order_id){
        if($task_id){
            $params = [
                "task_id"=>$task_id
            ];
            $request = new Request(self::KEY_EXMO_OUT, self::SECRET_EXMO_OUT);
            $response = $request->query('withdraw_get_txid', $params);
            if($response['status'] == true){
                \Yii::info(print_r('Реализован вывод BTC по сделке '. 'order_id: '.$order_id,1), 'exmo');
                \Yii::info(print_r($response,1), 'exmo');
                $updOrder = Orders::findOne($order_id);
                $updOrder->status = 'PAID stage 5 of 5';
                $updOrder->save();
            }
            if(!empty($response['error'])){
                \Yii::info(print_r($response,1), 'exmo');
            }
        }
    }

}