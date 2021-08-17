<?php


namespace AppStore\model;


class CommentRepository extends Repository
{
    private $mySqli = null;

    public function __construct()
    {
        parent::__construct();
        $this->repository = $this->getConnection();
    }


    protected function submitComment(string $username,string $packageName,float $rate,string $detail) : int
    {

    }

}