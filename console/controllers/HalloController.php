<?php


namespace console\controllers;


use console\models\Bills;
use frontend\models\Rates;
use Qiwi\Api\BillPaymentsException;
use Exmo\Api\Request;

class HalloController extends \yii\console\Controller
{
    /*
     * for Qiwi
     */
    CONST SECRET_KEY = '';

    public function actionIndex(){
    //    var_dump('Hallo!');
        $messageLog = [
            'test' => 22,
            'test1' => 'gfgfg'
        ];

        $message = 'hallo11';

    //    \Yii::info($messageLog, 'rates');
    //    \Yii::warning($message, 'rates');
    //    \Yii::error($messageLog, 'qiwi');

        $factory = new \RandomLib\Factory;
        $generator = $factory->getGenerator(new \SecurityLib\Strength(\SecurityLib\Strength::MEDIUM));

        $bytes = $generator->generateString(32, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789()[]{}<>');

        var_dump($bytes);

    }

    public function actionRates(){
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
            if($item_key == 'BTC_RUB'){
                print "curl response is:" . print_r($item_value,1);
            }
        }
    }

    public function actionCreateBillQiwiSimple(){
        $billPayments = new \Qiwi\Api\BillPayments(self::SECRET_KEY);
        $publicKey = '48e7qUxn9T7RyYE1MVZswX1FRSbE6iyCj2gCRwwF3Dnh5XrasNTx3BGPiMsyXQFNKQhvukniQG8RTVhYm3iPv6vpah22DxGfzxkmSpzEk4UHxBstt7w4DDe6BAVHiMdDefu8rCGzdoLJSbLqVq9N6CAUi8Ui8FFtzz9bi2cxnDkZZdJ8JZjCcbet93XEV';
        $billId = '12349';
        $comment = 'Test';
        $billId = urlencode($billId);
        $comment = urlencode($comment);
        $params = [
            'publicKey' => $publicKey,
            'billId' => $billId,
            'comment' => 'Безвозмездно',
            'amount' => 1
        ];
        /** @var \Qiwi\Api\BillPayments $billPayments */
        $link = $billPayments->createPaymentForm($params);

        $ch = curl_init();
        $url = $link;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
        curl_setopt($ch,CURLOPT_TIMEOUT, 20);
        $response = curl_exec($ch);

        curl_close ($ch);

        print "curl response is:" . print_r($response,1);

        $anySum ='https://oplata.qiwi.com/create?billId=12349&publicKey=48e7qUxn9T7RyYE1MVZswX1FRSbE6iyCj2gCRwwF3Dnh5XrasNTx3BGPiMsyXQFNKQhvukniQG8RTVhYm3iPv6vpah22DxGfzxkmSpzEk4UHxBstt7w4DDe6BAVHiMdDefu8rCGzdoLJSbLqVq9N6CAUi8Ui8FFtzz9bi2cxnDkZZdJ8JZjCcbet93XEV';

        echo $link;

    }

    public function actionCreateBillQiwi(){
        $response = '';
        $billPayments = new \Qiwi\Api\BillPayments(self::SECRET_KEY);
        $billId = '12351';
        $fields = [
            'amount' => 10.00,
            'currency' => 'RUB',
            'comment' => 'Безвозмездно',
            'expirationDateTime' => '2020-03-10T08:44:07+03:00'
        ];

        /** @var \Qiwi\Api\BillPayments $billPayments */
        try {
            $response = $billPayments->createBill($billId, $fields);
        } catch (BillPaymentsException $e) {
           echo print_r($e,1);
        }
        echo print_r($response,1);

    }

    public function actionGetBillInfoQiwi(){
        $billPayments = new \Qiwi\Api\BillPayments(self::SECRET_KEY);
        $billId = '12349';
        /** @var \Qiwi\Api\BillPayments $billPayments */
        $response = $billPayments->getBillInfo($billId);
        echo print_r($response,1);
      //  print_r($response,1);
    }

    public function actionRatesData(){
        $upd = '1582451863';
        $price = 10134.99970594;


        $dt = date("j-n-Y h:i:s" , $upd);

        $item_key = 'BTC_RUB';
        $findCurr = Rates::find()->where(['cripto_name'=>$item_key])->one();
        echo $findCurr;

    }

    public function actionExmoinfo(){
        $key = '';
        $secret = '';

        $request = new Request($key, $secret);
        $response = $request->query('user_info');
        echo print_r($response['balances']['BTC'],1);

    }

    public function actionBtcoutinfo(){
        $params = [
            "task_id"=>8950929
        ];
        $request = new Request(Bills::KEY_EXMO_OUT, Bills::SECRET_EXMO_OUT);
        $response = $request->query('withdraw_get_txid', $params);
        echo print_r($response,1);

    }
}