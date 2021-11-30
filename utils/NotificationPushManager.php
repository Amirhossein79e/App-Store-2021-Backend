<?php


namespace AppStore\utils;


class NotificationPushManager
{
    private function __construct(){}
    private static $pushManager;

    public string $url = 'https://fcm.googleapis.com/fcm/send';

    public static function getInstance() : NotificationPushManager
    {
        if (self::$pushManager == null)
        {
            self::$pushManager = new NotificationPushManager();
        }
        return self::$pushManager;
    }


    public function initNotification(string $token)
    {
        $curl = curl_init();

        curl_setopt($curl,CURLOPT_URL,$this->url);
        curl_setopt($curl,CURLOPT_POST,true);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);

        $headers = array(
            'Content-Type: application/json',
            'Authorization: key="*"'
        );

        curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);

        $postFiled = array(
            'to'=>$token,
            'data'=>array(
                'title_fa'=> 'سلام',
                'title_en'=> 'Hello !',
                'message_fa'=> 'خوشحالیم که از اپلیکیشن ما استفاده می کنید',
                'message_en'=> 'We are glad that you are using our application',
                'icon'=> null
            )
        );

        curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($postFiled));

        curl_exec($curl);
    }


    public function signUpNotification(string $token)
    {
        $curl = curl_init();

        curl_setopt($curl,CURLOPT_URL,$this->url);
        curl_setopt($curl,CURLOPT_POST,true);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);

        $headers = array(
            'Content-Type: application/json',
            'Authorization: key="*"'
        );

        curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);

        $postFiled = array(
            'to'=>$token,
            'data'=>array(
                'title_fa'=> 'ایجاد حساب کاربری',
                'title_en'=> 'Sign up',
                'message_fa'=> 'حساب کاربری شما با موفقیت ایجاد شد',
                'message_en'=> 'You successfully signed up',
                'icon'=> null
            )
        );

        curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($postFiled));

        curl_exec($curl);
    }


    public function signInNotification(string $token)
    {
        $curl = curl_init();

        curl_setopt($curl,CURLOPT_URL,$this->url);
        curl_setopt($curl,CURLOPT_POST,true);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);

        $headers = array(
            'Content-Type: application/json',
            'Authorization: key="*"'
        );

        curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);

        $postFiled = array(
            'to'=>$token,
            'data'=>array(
                'title_fa'=> 'ورود به حساب',
                'title_en'=> 'Sign in',
                'message_fa'=> 'شما با موفقیت به حساب کاربری خود وارد شدید',
                'message_en'=> 'You successfully signed in',
                'icon'=> null
            )
        );

        curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($postFiled));

        curl_exec($curl);
    }

}