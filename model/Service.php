<?php


namespace AppStore\model;
require_once (__DIR__.DIRECTORY_SEPARATOR.'Repository.php');

class Service extends Repository
{
    private $repository = null;


    public function __construct()
    {
        $this->repository = new Repository();
    }


    public function initToken(string $uid,string $token) : bool
    {
        $result = $this->repository->initToken($uid,$token);
        $this->repository->closeDb();
        return $result;
    }


    public function syncToken(string $uid,string $token) : bool
    {
        $result = $this->repository->syncToken($uid,$token);
        $this->repository->closeDb();
        return $result;
    }


    public function signUpUser(string $mail,string $password,string $username,string $token) : string
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