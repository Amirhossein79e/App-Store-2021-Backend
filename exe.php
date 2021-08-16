<?php
require_once (__DIR__.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'View.php');
use Appstore\view as view;
error_reporting(E_ALL);

$method = $_POST['requestCode'];
$data = str_replace(' ','+',$_POST['data']);

echo strlen(base64_decode(base64_encode(openssl_random_pseudo_bytes(64))));

if ($method != null && strlen($method) > 0 && $data != null && strlen($data) > 0)
{
    $view = new view\View();
    settype($method,'int');

    switch ($method)
    {
        case 1:
            $view->initToken($data);
            break;

        case 2:
            $view->syncToken($data);
            break;

        case 3:
            $view->signUpUser($data);
            break;

        case 4:
            $view->signInUser($data);
            break;

        case 5:
            $view->syncUser($data);
            break;

        default:
            echo 'Incorrect request code';
    }
}


?>