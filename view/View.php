<?php


namespace AppStore\view;
require_once (__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');
use AppStore\controller;

class View
{
    private $controller;

    public function __construct()
    {
        $this->controller = new controller\Controller();
    }


    private function onError()
    {
        echo 'System error';
        die();
    }


    public function initToken(string $data)
    {
        try
        {
            $result = $this->controller->initToken($data);
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


    public function syncToken(string $data)
    {
        try
        {
            $result = $this->controller->syncToken($data);
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


    public function signUpUser(string $data)
    {
        try
        {
            $result = $this->controller->signUpUser($data);
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


    public function signInUser(string $data)
    {
        try
        {
            $result = $this->controller->signInUser($data);
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


    public function syncUser(string $data)
    {
        try
        {
            $result = $this->controller->syncUser($data);
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


    public function validateUser(string $data)
    {
        try
        {
            $result = $this->controller->validateUser($data);
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