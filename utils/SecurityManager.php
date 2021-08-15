<?php


namespace AppStore\utils;


class SecurityManager
{
    private function __construct(){}
    private static $securityManager;


    public static function getInstance() : SecurityManager
    {
        if (self::$securityManager == null)
        {
            self::$securityManager = new SecurityManager();
        }
        return self::$securityManager;
    }


    private function getPrivateKey()
    {
        $path = str_replace("utils","asset",__DIR__).DIRECTORY_SEPARATOR."bermooda.pfx";
        $file = fopen($path,"r");
        $input = fread($file,filesize($path));
        fclose($file);
        $certificate = null;
        openssl_pkcs12_read($input,$certificate,"com.amirhosseinemadi.store");
        return openssl_get_privatekey($certificate['pkey'],'com.amirhosseinemadi.store');
    }


    private function getPublicKey()
    {
        $path = str_replace("utils","asset",__DIR__)."\\"."bermooda.pfx";
        $file = fopen($path,"r");
        $input = fread($file,filesize($path));
        fclose($file);
        $certificate = null;
        openssl_pkcs12_read($input,$certificate,'com.amirhosseinemadi.store');
        return openssl_get_publickey($certificate['cert']);
    }


    private function getAesKey(string $data)
    {
        $array = explode("@",$data);
        $aesDetail = array();

        array_push($aesDetail,
            $this->decryptRsa(base64_decode($array[0])),
            $this->decryptRsa(base64_decode($array[1])));

        return $aesDetail;
    }


    public function getRandomToken() : string
    {
        return base64_encode(openssl_random_pseudo_bytes(64));
    }


    public function encryptHash(string $rawData)
    {
        return openssl_digest($rawData,'sha1',false);
    }


    public function encryptAes(string $keyData,string $rawData)
    {
        $aesDetail = $this->getAesKey($keyData);
        return openssl_encrypt($rawData,'aes-128-cbc',$aesDetail[0],OPENSSL_RAW_DATA,$aesDetail[1]);
    }


    private function decryptRsa(string $data)
    {
        $decryptedData = null;
        openssl_private_decrypt($data,$decryptedData,$this->getPrivateKey());
        return $decryptedData;
    }


    public function decryptAes(string $data)
    {
        $array = explode("@",$data);
        $aesDetail = $this->getAesKey($data);
        return openssl_decrypt(base64_decode($array[2]),'aes-128-cbc',$aesDetail[0],OPENSSL_RAW_DATA,$aesDetail[1]);
    }


}