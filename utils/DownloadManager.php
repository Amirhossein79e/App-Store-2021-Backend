<?php

namespace AppStore\utils;
require_once (__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');


class DownloadManager
{
    private $securityManager;

    public function __construct()
    {
        $this->securityManager = SecurityManager::getInstance();
    }

    public function download(string $data)
    {
        if ($data != null && strlen($data) > 0 && strpos($data,'@') != false)
        {
            $decrypted = json_decode($this->securityManager->decryptAes($data), true);

            if ($decrypted != null && strlen($decrypted['packageName']) > 0)
            {

                $path = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'apk'.DIRECTORY_SEPARATOR.$decrypted['packageName'].'.apk';

                if (file_exists($path))
                {
                    header('Content-Type: application/octet-stream');
                    header('Content-Length: ' . filesize($path));

                    $file = fopen($path, 'rb');

                    while (feof($file))
                    {
                        $buffer = fread($file, 1048576);
                        $b64 = base64_encode($this->securityManager->encryptAes($data,$buffer));
                        echo $b64;
                        ob_flush();
                        flush();
                    }

                }
            }
        }
    }

}