<?php


namespace AppStore\view;
require_once (__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');
use AppStore\controller as controller;

class CommentView
{
    private $controller;

    public function __construct()
    {
        $this->controller = new controller\CommentController();
    }

    private function onError()
    {
        echo 'System error';
        die();
    }


    public function submitComment(string $data)
    {
        try
        {
            $result = $this->controller->submitComment($data);
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


    public function deleteComment(string $data)
    {
        try
        {
            $result = $this->controller->deleteComment($data);
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