<?php


namespace AppStore\controller;
require_once (__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR.'Service.php');
require_once (__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'utils'.DIRECTORY_SEPARATOR.'SecurityManager.php');
use AppStore\model as model , AppStore\utils as utils;

class Controller
{
    private $service = null;
    private $securityManager = null;


    public function __construct()
    {
        $this->service = new model\Service();
        $this->securityManager = utils\SecurityManager::getInstance();
    }


    private function calculateData(string $keyData, string $responseCode, string $data)
    {
        $array = array('responseCode' => $responseCode, 'data' => $data);
        return $this->securityManager->encryptAes($keyData,json_encode($array,JSON_UNESCAPED_UNICODE));
    }


    public function initToken(string $data) : string
    {
        $decrypted = json_decode($this->securityManager->decryptAes($data),true);

        if ($decrypted != null && strlen($decrypted['uid']) == 8 && strlen($decrypted['token']) > 100 && strlen($decrypted['token']) < 450)
        {
            $result = $this->service->initToken($decrypted['uid'],$decrypted['token']);
            if ($result)
            {
                $mainResult = 1;
                $message = 'فرایند با موفقیت اجرا شد';
            }else
            {
                $mainResult = -1;
                $message = 'فرایند با خطا مواجه شد (خطای سیستمی)';
            }

        }else
        {
           $mainResult = 0;
           $message = 'پارامتر های ارسالی نا معتبر است';
        }

        return $this->calculateData($data,$mainResult,$message);
    }


    public function syncToken(string $data) : string
    {
        $decrypted = json_decode($this->securityManager->decryptAes($data),true);

        if ($decrypted != null && strlen($decrypted['uid']) == 8 && strlen($decrypted['token']) > 100 && strlen($decrypted['token']) < 450)
        {
            $result = $this->service->syncToken($decrypted['uid'],$decrypted['token']);
            if ($result)
            {
                $mainResult = 1;
                $message = 'فرایند با موفقیت اجرا شد';
            }else
            {
                $mainResult = -1;
                $message = 'فرایند با خطا مواجه شد (خطای سیستمی)';
            }
        }else
        {
            $mainResult = 0;
            $message = 'پارامتر های ارسالی نا معتبر است';
        }

        return $this->calculateData($data,$mainResult,$message);
    }


    public function signUpUser(string $data) : string
    {
        $decrypted = json_decode($this->securityManager->decryptAes($data),true);

        if ($decrypted != null && filter_var($decrypted['mail'],FILTER_VALIDATE_EMAIL) && strlen($decrypted['password']) >= 8 && strlen($decrypted['username']) > 0 && strlen($decrypted['token']) > 100 && strlen($decrypted['token']) < 450)
        {
            $result = $this->service->signUpUser($decrypted['mail'],$decrypted['password'],$decrypted['username'],$decrypted['token']);
            settype($result,'string');
            switch ($result)
            {
                case '-1':
                    $mainResult = -1;
                    $message = 'فرایند با خطا مواجه شد (خطای سیستمی)';
                    break;

                case '0':
                    $mainResult = 0;
                    $message = 'ایمیل وارد شده در سیستم موجود می باشد . لطفا ایمیل دیگری وارد نمایید';
                    break;

                default:
                    $mainResult = 1;
                    $message = $result;
                    break;
            }

        }else
        {
            $mainResult = 0;
            $message = "پارامتر های ارسالی نا معتبر است";
        }

        return $this->calculateData($data,$mainResult,$message);
    }


    public function signInUser(string $data) : string
    {
        $decrypted = json_decode($this->securityManager->decryptAes($data),true);

        if ($decrypted != null && filter_var($decrypted['mail'],FILTER_VALIDATE_EMAIL) && strlen($decrypted['password']) >= 8)
        {
            $result = $this->service->signInUser($decrypted['mail'],$decrypted['password']);
            settype($result,'string');
            switch ($result)
            {
                case '-1':
                    $mainResult = -1;
                    $message = 'فرایند با خطا مواجه شد (خطای سیستمی)';
                    break;

                case '0':
                    $mainResult = 0;
                    $message = 'رمز عبور وارد شده صحیح نیست و یا ایمیل وارد شده در سیستم وجود ندارد';
                    break;

                default:
                    $mainResult = 1;
                    $message = $result;
                    break;
            }
        }else
        {
            $mainResult = 0;
            $message = "پارامتر های ارسالی نا معتبر است";
        }

        return $this->calculateData($data,$mainResult,$message);
    }


    public function syncUser(string $data) : string
    {
        $decrypted = json_decode($this->securityManager->decryptAes($data),true);

        if ($decrypted != null && strlen($decrypted['access']) > 80 && strlen($decrypted['access']) < 96 && strlen($decrypted['token']) > 100 && strlen($decrypted['token']) < 450)
        {
           $result = $this->service->syncUser($decrypted['access'],$decrypted);
            settype($result,'string');
            switch ($result)
            {
                case '-1':
                    $mainResult = -1;
                    $message = 'فرایند با خطا مواجه شد (خطای سیستمی)';
                    break;

                case '0':
                    $mainResult = 0;
                    $message = "پارامتر های ارسالی نا معتبر است";
                    break;

                case '1':
                    $mainResult = 1;
                    $message = 'فرایند با موفقیت اجرا شد';
                    break;
            }
        }else
        {
            $mainResult = 0;
            $message = "پارامتر های ارسالی نا معتبر است";
        }

        return $this->calculateData($data,$mainResult,$message);
    }

}