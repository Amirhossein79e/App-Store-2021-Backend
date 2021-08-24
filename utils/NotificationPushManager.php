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


    public function tokenIntNotification(string $token)
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
                'title'=>'سلام',
                'message'=>'خوشحالیم که از اپلیکیشن ما استفاده می کنید',
                'icon'=>''
            )
        );

        curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($postFiled));

        $result = curl_exec($curl);

    }

}