<?php
require_once (__DIR__.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'View.php');
use Appstore\view as view;
error_reporting(E_ALL);

$method = $_POST['requestCode'];
$data = str_replace(' ','+',$_POST['data']);

if ($method != null && strlen($method) > 0 && $data != null && strlen($data) > 0)
{
    $view = new view\View();
    settype($method,'int');

    switch ($method)
    {
        case 100:
            $view->initToken($data);
            break;

        case 101:
            $view->syncToken($data);
            break;

        case 102:
            $view->signUpUser($data);
            break;

        case 103:
            $view->signInUser($data);
            break;

        case 104:
            $view->syncUser($data);
            break;

        default:
            echo 'Incorrect request code';
    }
}


?>