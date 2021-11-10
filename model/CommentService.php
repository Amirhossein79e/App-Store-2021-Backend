<?php


namespace AppStore\model;
require_once (__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');

class CommentService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new CommentRepository();
    }


    public function getComments(string $access,string $packageName,int $offset)
    {
        if ($access == null)
        {
            $access = "";
        }
        $result = $this->repository->getComments($access,$packageName,$offset);
        $this->repository->closeDb();
        return $result;
    }


    public function getRatings(string $packageName)
    {
        $result = $this->repository->getRatings($packageName);
        $this->repository->closeDb();
        return $result;
    }


    public function submitComment(string $access,string $detail,float $rate,string $packageName) : string
    {
        if ($detail == null)
        {
            $detail = '';
        }
        $result = $this->repository->submitComment($access,$detail,$rate,$packageName);
        $this->repository->closeDb();
        return $result;
    }


    public function deleteComment(string $access,string $packageName) : bool
    {
        $result = $this->repository->deleteComment($access,$packageName);
        $this->repository->closeDb();
        return $result;
    }

}