<?php
require_once (__DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');
use AppStore\view,AppStore\utils;
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
            $view->getHome($data);
            break;

        case 201:
            $view = new view\AppView();
            $view->getCategories($data);
            break;

        case 202:
            $view = new view\AppView();
            $view->getApps($data);
            break;

        case 203:
            $view = new view\AppView();
            $view->getAppsByCategory($data);
            break;

        case 204:
            $view = new view\AppView();
            $view->getApp($data);
            break;

        case 205:
            $view = new view\AppView();
            $view->getTitlesSearch($data);
            break;

        case 206:
            $view = new view\AppView();
            $view->getAppsSearch($data);
            break;

        case 207:
            $view = new view\AppView();
            $view->getUpdates($data);
            break;

        case 300:
            $view = new view\CommentView();
            $view->getComments($data);
            break;

        case 301:
            $view = new view\CommentView();
            $view->getRating($data);
            break;

        case 302:
            $view = new view\CommentView();
            $view->submitComment($data);
            break;

        case 303:
            $view = new view\CommentView();
            $view->deleteComment($data);
            break;

        case 500:
            $downloadManager = new utils\DownloadManager();
            $downloadManager->download($data);
            break;

        default:
            $array = array('responseCode'=>-1,'data'=>'Incorrect requestCode');
            echo json_encode($array);
    }
}

?>