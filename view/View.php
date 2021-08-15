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
            if (strlen($b64) > 0)
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
            if (strlen($b64) > 0)
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


    public function signUpUser(string $mail,string $password,string $username,string $token)
    {
        $result = $this->repository->signUpUser($mail,$password,$username,$token);
        $this->repository->closeDb();
        return $result;
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