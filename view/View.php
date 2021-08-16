<?php


namespace AppStore\view;
require_once (__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR.'Controller.php');
use AppStore\controller as controller;

class View
{
    private $controller = null;

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

}