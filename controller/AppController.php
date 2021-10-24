<?php


namespace AppStore\controller;
require_once (__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');
use AppStore\model, AppStore\utils;

class AppController
{
    private $service;
    private $securityManager;

    public function __construct()
    {
        $this->service = new model\AppService();
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


    public function getHome(string $data) : string
    {
        if ($data != null && strlen($data) > 0 && strpos($data,'@') != false)
        {
            $result = $this->service->getHome();
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

            $response = $this->calculateData($data,$mainResult,$message,true);

        }else
        {
            $mainResult = 0;
            $message = 'پارامتر های ارسالی نا معتبر است';
            $response = $this->calculateData($data,$mainResult,$message,false);
        }

        return $response;
    }


    public function getCategories(string $data) : string
    {
        if ($data != null && strlen($data) > 0 && strpos($data,'@') != false)
        {
            $result = $this->service->getCategories();
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

            $response = $this->calculateData($data,$mainResult,$message,true);

        }else
        {
            $mainResult = 0;
            $message = 'پارامتر های ارسالی نا معتبر است';
            $response = $this->calculateData($data,$mainResult,$message,false);
        }

        return $response;
    }


    public function getApps(string $data) : string
    {
        if ($data != null && strlen($data) > 0 && strpos($data,'@') != false)
        {
            $decrypted = json_decode($this->securityManager->decryptAes($data),true);

            if ($decrypted != null && $decrypted['offset'] !== null)
            {
                $result = $this->service->getApps($decrypted['offset']);
                switch ($result) {
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


    public function getAppsByCategory(string $data) : string
    {
        if ($data != null && strlen($data) > 0 && strpos($data,'@') != false)
        {
            $decrypted = json_decode($this->securityManager->decryptAes($data),true);

            if ($decrypted != null && $decrypted['offset'] !== null && strlen($decrypted['category']) > 0)
            {
                $result = $this->service->getAppsByCategory($decrypted['offset'],$decrypted['category']);
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


    public function getApp(string $data) : string
    {
        if ($data != null && strlen($data) > 0 && strpos($data,'@') != false)
        {
            $decrypted = json_decode($this->securityManager->decryptAes($data),true);

            if ($decrypted != null && strlen($decrypted['packageName']) > 0)
            {
                $result = $this->service->getApp($decrypted['packageName']);
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


    public function getTitlesSearch(string $data) : string
    {
        if ($data != null && strlen($data) > 0 && strpos($data,'@') != false)
        {
            $decrypted = json_decode($this->securityManager->decryptAes($data),true);

            if ($decrypted != null && strlen($decrypted['query']) > 0)
            {
                $result = $this->service->getTitlesSearch($decrypted['query']);
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


    public function getAppsSearch(string $data) : string
    {
        if ($data != null && strlen($data) > 0 && strpos($data,'@') != false)
        {
            $decrypted = json_decode($this->securityManager->decryptAes($data),true);

            if ($decrypted != null && strlen($decrypted['query']) > 0 && $decrypted['offset'] !== null)
            {
                $result = $this->service->getTitlesSearch($decrypted['offset'].$decrypted['query']);
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


    public function getUpdates(string $data) : string
    {
        if ($data != null && strlen($data) > 0 && strpos($data,'@') != false)
        {
            $decrypted = json_decode($this->securityManager->decryptAes($data),true);

            if ($decrypted != null && $decrypted['offset'] !== null)
            {
                if (count($decrypted) > 0)
                {
                    $result = $this->service->getUpdates($decrypted['offset'],$decrypted['packages']);
                    switch ($result) {
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
                    $mainResult = 1;
                    $message = 'هیچ بروزرسانی موجود نیست';
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