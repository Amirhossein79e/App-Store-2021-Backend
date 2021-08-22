<?php
require_once (__DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');
use AppStore\view;
error_reporting(E_ALL);

$requestCode = $_POST['requestCode'];
$data = str_replace(' ','+',$_POST['data']);

if ($requestCode != null && strlen($requestCode) > 0 && $data != null && strlen($data) > 0)
{
    settype($requestCode,'int');

    switch ($requestCode)
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

        case 200:
            $view = new view\AppView();
            $view->getCategories($data);
            break;

        case 201:
            $view = new view\AppView();
            $view->getApps($data);
            break;

        case 202:
            $view = new view\AppView();
            $view->getAppsByCategory($data);
            break;

        case 203:
            $view = new view\AppView();
            $view->getApp($data);
            break;

        case 204:
            $view = new view\AppView();
            $view->getTitlesSearch($data);
            break;

        case 205:
            $view = new view\AppView();
            $view->getAppsSearch($data);
            break;

        case 206:
            $view = new view\AppView();
            $view->getUpdates($data);
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
            $array = array('responseCode'=>-1,'data'=>'Incorrect requestCode');
            echo json_encode($array);
    }
}

?>