<?php


namespace AppStore\model;
require_once (__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');


class AppRepository extends Repository
{
    private $mysqli;

    public function __construct()
    {
        parent::__construct();
        $this->mysqli = $this->getConnection();
    }
}