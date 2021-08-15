<?php


namespace AppStore\model;


class AppRepository extends Repository
{
    private $mysqli = null;

    public function __construct()
    {
        parent::__construct();
        $this->mysqli = $this->getConnection();
    }
}