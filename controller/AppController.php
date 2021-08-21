<?php


namespace AppStore\controller;
require_once (__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');
use AppStore\model as model , AppStore\utils as utils;

class AppController
{
    private $service;
    private $securityManager;

    public function __construct()
    {
        $this->service = new model\AppService();
        $this->securityManager = utils\SecurityManager::getInstance();
    }


    private function calculateData(string $keyData, string $responseCode, string $data)
    {
        $array = array('responseCode' => $responseCode, 'data' => $data);
        return $this->securityManager->encryptAes($keyData,json_encode($array,JSON_UNESCAPED_UNICODE));
    }


    public function getCategories() : string
    {

    }


    public function getApps(int $offset) : string
    {
        $result = $this->repository->getApps($offset);
        $this->repository->closeDb();
        return $result;
    }


    public function getAppsByCategory(int $offset,string $category) : string
    {
        $result = $this->repository->getAppsByCategory($offset,$category);
        $this->repository->closeDb();
        return $result;
    }


    public function getApp(string $packageName) : string
    {
        $result = $this->repository->getApp($packageName);
        $this->repository->closeDb();
        return $result;
    }


    public function getTitlesSearch(string $query) : string
    {
        $result = $this->repository->getTitlesSearch($query);
        $this->repository->closeDb();
        return $result;
    }


    public function getAppsSearch(string $query) : string
    {
        $result = $this->repository->getAppsSearch($query);
        $this->repository->closeDb();
        return $result;
    }


    public function getUpdates(array $packageNames) : string
    {
        $result = $this->repository->getUpdates($packageNames);
        $this->repository->closeDb();
        return $result;
    }
}