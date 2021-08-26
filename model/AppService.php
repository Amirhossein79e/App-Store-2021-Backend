<?php


namespace AppStore\model;
require_once (__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');

class AppService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new AppRepository();
    }


    public function getHome() : string
    {
        $result = $this->repository->getHome();
        $this->repository->closeDb();
        return $result;
    }


    public function getCategories() : string
    {
        $result = $this->repository->getCategories();
        $this->repository->closeDb();
        return $result;
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