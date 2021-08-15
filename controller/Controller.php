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


    private function calculateData(string $keyData, string $requestCode, string $data)
    {
        $array = array('responseCode' => $requestCode, 'data' => $data);
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

        filter_var('aa',FILTER_VALIDATE_EMAIL);
    }


    public function signInUser(string $mail,string $password)
    {
        $result = $this->repository->signInUser($mail,$password);
        $this->repository->closeDb();
        return $result;
    }


    public function syncUser(string $access,string $token)
    {
        $result = $this->repository->syncUser($access,$token);
        $this->repository->closeDb();
        return $result;
    }

}