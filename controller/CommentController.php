<?php


namespace AppStore\controller;
require_once (__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');
use AppStore\model as model , AppStore\utils as utils;

class CommentController
{
    private $service;
    private $securityManager;

    public function __construct()
    {
        $this->service = new model\CommentService();
        $this->securityManager = utils\SecurityManager::getInstance();
    }


    private function calculateData(string $keyData, string $responseCode, string $data)
    {
        $array = array('responseCode' => $responseCode, 'data' => $data);
        return $this->securityManager->encryptAes($keyData,json_encode($array,JSON_UNESCAPED_UNICODE));
    }


    public function submitComment(string $data) : string
    {
        $decrypted = json_decode($this->securityManager->decryptAes($data),true);

        if ($decrypted != null && strlen($decrypted['access']) > 80 && strlen($decrypted['access']) < 96 && $decrypted['rate'] > 0 && strlen($decrypted['packageName'] > 0))
        {
            $result = $this->service->submitComment($decrypted['access'],$decrypted['detail'],$decrypted['rate'],$decrypted['packageName']);
            switch ($result)
            {
                case "-1":
                    $mainResult = -1;
                    $message = 'فرایند با خطا مواجه شد (خطای سیستمی)';
                    break;

                case "1":
                    $mainResult = 1;
                    $message = 'دیدگاه شما با موفقیت ثبت شد';
                    break;

                case "2":
                    $mainResult = 2;
                    $message = 'دیدگاه شما با موفقیت بروز شد';
                    break;
            }
        }else
        {
            $mainResult = 0;
            $message = 'پارامتر های ارسالی نا معتبر است';
        }

        return $this->calculateData($data,$mainResult,$message);
    }


    public function deleteComment(string $data) : string
    {
        $decrypted = json_decode($this->securityManager->decryptAes($data),true);

        if ($decrypted != null && strlen($decrypted['access']) > 80 && strlen($decrypted['access']) < 96 && strlen($decrypted['packageName'] > 0))
        {
            $result = $this->service->deleteComment($decrypted['access'],$decrypted['packageName']);
            if ($result)
            {
                $mainResult = 1;
                $message = 'دیدگاه شما حذف شد';
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
}