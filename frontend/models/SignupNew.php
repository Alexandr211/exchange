<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\UserNew;
class SignupNew extends Model
{
    public $login;
    public $pass;
    public $pass_hash;
    public $reCaptcha;
    public $attr;
    public $count;
    const ACHTUNG = 2;
    const CAPCHAOK = 3;
    const PASSWORDOK = 4;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['login', 'unique', 'targetClass' => '\common\models\UserNew', 'message' => 'This username has already been taken.'],

            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator2::className(),
                'uncheckedMessage' => 'Please confirm that you are not a bot.'],
        ];
    }

    /**
     * Signs user up.
     *
     * @param $request_result
     * @return bool whether the creating new account was successful
     */
    public function signup($request_result)
    {
        if(!empty($request_result)){
            if(!empty($request_result['SignupNew']['reCaptcha']) && !empty($request_result['g-recaptcha-response']) && $request_result['SignupNew']['reCaptcha'] == $request_result['g-recaptcha-response']){
                $session = Yii::$app->session;
                if ($session->isActive){
                    if (isset($session[$request_result['SignupNew']['reCaptcha']])){
                        $reCaptcha = $session[$request_result['SignupNew']['reCaptcha']];
                        if($reCaptcha == $request_result['SignupNew']['reCaptcha']){
                            return self::ACHTUNG;
                        }else{
                            return self::CAPCHAOK;
                        }
                    }else{
                        $session[$request_result['SignupNew']['reCaptcha']] = $request_result['SignupNew']['reCaptcha'];
                        return self::CAPCHAOK;
                    }
                }else{
                    return self::ACHTUNG;
                }
            }
            if(!empty($request_result['SignupNew']['login']) && !empty($request_result['SignupNew']['pass'])){
                return self::PASSWORDOK;
            }
        }
    //    if (!$this->validate()) {
    //        \Yii::info(print_r('not good validate!',1), 'enter');
    //        return null;
    //    }
        return true;
    }

    public function setUserData(){
        // формируем случайные логин и пароль, записываем в БД и отображаем клиенту
        $factory = new \RandomLib\Factory;
        $generator = $factory->getGenerator(new \SecurityLib\Strength(\SecurityLib\Strength::MEDIUM));
        $login = $generator->generateString(20, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789()[]{}<>');
        $pass = $generator->generateString(32, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789()[]{}<>');

        // Проверим, что пароль уникален
        $userNewModel = UserNew::find()->where(['login'=>$login])->one();

        if(!$userNewModel){
            $user = new UserNew();
            $user->login = $login;
            $user->pass = '1';
            $user->pass_hash = Yii::$app->security->generatePasswordHash($pass);
            if(!($user->save())){
                return self::ACHTUNG;
            };
        }else{
            \Yii::info(print_r($userNewModel,1), 'enter');
            return self::ACHTUNG;
        }
        $data_arr = [];
        $data_arr['login'] = $login;
        $data_arr['pass'] = $pass;

        \Yii::info(print_r($data_arr,1), 'enter');
        return $data_arr;

    }

}