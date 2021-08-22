<?php


namespace AppStore\view;
require_once (__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');
use AppStore\controller;

class AppView
{
    private $controller;

    public function __construct()
    {
        $this->controller = new controller\AppController();
    }


    public function getCategories(string $data)
    {
        try
        {
            $result = $this->controller->getCategories($data);
            $b64 = base64_encode($result);
            if (strlen($b64) > 3)
            {
                echo $b64;
            }else
            {
                $this->onError();
            }
        }catch (\Exception $exception)
        {
            $this->onError();
        }
    }


    public function getApps(string $data)
    {
        try
        {
            $result = $this->controller->getApps($data);
            $b64 = base64_encode($result);
            if (strlen($b64) > 3)
            {
                echo $b64;
            }else
            {
                $this->onError();
            }
        }catch (\Exception $exception)
        {
            $this->onError();
        }
    }


    public function getAppsByCategory(string $data)
    {
        try
        {
            $result = $this->controller->getAppsByCategory($data);
            $b64 = base64_encode($result);
            if (strlen($b64) > 3)
            {
                echo $b64;
            }else
            {
                $this->onError();
            }
        }catch (\Exception $exception)
        {
            $this->onError();
        }
    }


    public function getApp(string $data)
    {
        try
        {
            $result = $this->controller->getApp($data);
            $b64 = base64_encode($result);
            if (strlen($b64) > 3)
            {
                echo $b64;
            }else
            {
                $this->onError();
            }
        }catch (\Exception $exception)
        {
            $this->onError();
        }
    }


    public function getTitlesSearch(string $data)
    {
        try
        {
            $result = $this->controller->getTitlesSearch($data);
            $b64 = base64_encode($result);
            if (strlen($b64) > 3)
            {
                echo $b64;
            }else
            {
                $this->onError();
            }
        }catch (\Exception $exception)
        {
            $this->onError();
        }
    }


    public function getAppsSearch(string $data)
    {
        try
        {
            $result = $this->controller->getAppsSearch($data);
            $b64 = base64_encode($result);
            if (strlen($b64) > 3)
            {
                echo $b64;
            }else
            {
                $this->onError();
            }
        }catch (\Exception $exception)
        {
            $this->onError();
        }
    }


    public function getUpdates(string $data)
    {
        try
        {
            $result = $this->controller->getUpdates($data);
            $b64 = base64_encode($result);
            if (strlen($b64) > 3)
            {
                echo $b64;
            }else
            {
                $this->onError();
            }
        }catch (\Exception $exception)
        {
            $this->onError();
        }
    }
}