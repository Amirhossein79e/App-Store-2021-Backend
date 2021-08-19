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


    public function getCategory() : string
    {
        $stmt = new \mysqli_stmt($this->mysqli,'select * from category');
        $success = $stmt->execute();
        if ($success)
        {
            $array = array();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc())
            {
                array_push($array,$row);
            }
            $mainResult = json_encode($array);
        }
        else
        {
            $mainResult = -1;
        }

        $stmt->close();
        return $mainResult;
    }


    public function getApp(int $offset) : string
    {
        $stmt = new \mysqli_stmt($this->mysqli,'select * from app limit 25 offset '.$offset);
        $success = $stmt->execute();
        if ($success)
        {
            $array = array();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc())
            {
                array_push($array,$row);
            }
            $mainResult = json_encode($array);
        }
        else
        {
            $mainResult = -1;
        }

        $stmt->close();
        return $mainResult;
    }


    public function getAppByCategory(int $offset,string $category) : string
    {
        $stmt = new \mysqli_stmt($this->mysqli,'select * from app where category = ? limit 25 offset '.$offset);
        $stmt->bind_param('s',$category);
        $success = $stmt->execute();
        if ($success)
        {
            $array = array();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc())
            {
                array_push($array,$row);
            }
            $mainResult = json_encode($array);
        }
        else
        {
            $mainResult = -1;
        }

        $stmt->close();
        return $mainResult;
    }
}