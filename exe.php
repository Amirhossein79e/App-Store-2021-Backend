<?php
require_once (__DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');
use AppStore\view;
error_reporting(E_ALL);

$method = $_POST['requestCode'];
$data = str_replace(' ','+',$_POST['data']);

if ($method != null && strlen($method) > 0 && $data != null && strlen($data) > 0)
{
    settype($method,'int');

    switch ($method)
    {
        case 100:
            $view = new view\View();
            $view->initToken($data);
            break;

        case 101:
            $view = new view\View();
            $view->syncToken($data);
            break;

        case 102:
            $view = new view\View();
            $view->signUpUser($data);
            break;

        case 103:
            $view = new view\View();
            $view->signInUser($data);
            break;

        case 104:
            $view = new view\View();
            $view->syncUser($data);
            break;

        case 300:
            $view = new view\CommentView();
            $view->submitComment($data);
            break;

        case 301:
            $view = new view\CommentView();
            $view->deleteComment($data);
            break;

        default:
            $array = array('responseCode'=>-1,'message'=>'Incorrect requestCode');
    }
}


?>