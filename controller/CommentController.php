<?php


namespace AppStore\controller;
require_once (__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');
use AppStore\model, AppStore\utils;

class CommentController
{
    private $service;
    private $securityManager;

    public function __construct()
    {
        $this->service = new model\CommentService();
        $this->securityManager = utils\SecurityManager::getInstance();
    }


    private function calculateData(string $keyData, string $responseCode, string $data,bool $valid)
    {
        $array = array('responseCode' => $responseCode);

        if (json_decode($data,true) == null)
        {
            $array['message'] = $data;
            $array['data'] = null;
        }else
        {
            $array['message'] = null;
            $array['data'] = json_decode($data,true);
        }

        if ($valid)
        {
            return $this->securityManager->encryptAes($keyData, json_encode($array, JSON_UNESCAPED_UNICODE));
        }
        else
        {
            return json_encode($array,JSON_UNESCAPED_UNICODE);
        }
    }


    public function getComments(string $data) : string
    {
        if ($data != null && strlen($data) > 0 && strpos($data,'@') != false)
        {
            $decrypted = json_decode($this->securityManager->decryptAes($data),true);

            if ($decrypted != null && strlen($decrypted['access']) > 80 && strlen($decrypted['access']) < 96 && strlen($decrypted['packageName']) > 0 && $decrypted['offset'] !== null )
            {
                $offset = $decrypted['offset'];
                settype($offset,'int');
                $result = $this->service->getComments($decrypted['access'],$decrypted['packageName'],$offset);
                switch ($result)
                {
                    case "-1":
                        $mainResult = -1;
                        $message = 'فرایند با خطا مواجه شد (خطای سیستمی)';
                        break;

                    default:
                        $mainResult = 1;
                        $message = $result;
                        break;
                }
            }else
            {
                $mainResult = 0;
                $message = 'پارامتر های ارسالی نا معتبر است';
            }

            $response = $this->calculateData($data,$mainResult,$message,true);

        }else
        {
            $mainResult = 0;
            $message = 'پارامتر های ارسالی نا معتبر است';
            $response = $this->calculateData($data,$mainResult,$message,false);
        }

        return $response;
    }


    public function getRating(string $data) : string
    {
        if ($data != null && strlen($data) > 0 && strpos($data,'@') != false)
        {
            $decrypted = json_decode($this->securityManager->decryptAes($data),true);

            if ($decrypted != null && strlen($decrypted['packageName']) > 0)
            {
                $result = $this->service->getRatings($decrypted['packageName']);
                switch ($result)
                {
                    case "-1":
                        $mainResult = -1;
                        $message = 'فرایند با خطا مواجه شد (خطای سیستمی)';
                        break;

                    default:
                        $mainResult = 1;
                        $message = $result;
                        break;
                }
            }else
            {
                $mainResult = 0;
                $message = 'پارامتر های ارسالی نا معتبر است';
            }

            $response = $this->calculateData($data,$mainResult,$message,true);

        }else
        {
            $mainResult = 0;
            $message = 'پارامتر های ارسالی نا معتبر است';
            $response = $this->calculateData($data,$mainResult,$message,false);
        }

        return $response;
    }


    public function submitComment(string $data) : string
    {
        if ($data != null && strlen($data) > 0 && strpos($data,'@') != false)
        {
        $decrypted = json_decode($this->securityManager->decryptAes($data),true);

        if ($decrypted != null && strlen($decrypted['access']) > 80 && strlen($decrypted['access']) < 96 && $decrypted['rate'] > 0 && strlen($decrypted['packageName']) > 0)
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

            $response = $this->calculateData($data,$mainResult,$message,true);

        }else
            {
                $mainResult = 0;
                $message = 'پارامتر های ارسالی نا معتبر است';
                $response = $this->calculateData($data,$mainResult,$message,false);
            }

        return $response;
    }


    public function deleteComment(string $data) : string
    {
        if ($data != null && strlen($data) > 0 && strpos($data,'@') != false)
        {
        $decrypted = json_decode($this->securityManager->decryptAes($data),true);

        if ($decrypted != null && strlen($decrypted['access']) > 80 && strlen($decrypted['access']) < 96 && strlen($decrypted['packageName']) > 0)
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

        $response = $this->calculateData($data,$mainResult,$message,true);

        }else
        {
            $mainResult = 0;
            $message = 'پارامتر های ارسالی نا معتبر است';
            $response = $this->calculateData($data,$mainResult,$message,false);
        }

        return $response;
    }
}