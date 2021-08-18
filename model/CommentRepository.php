<?php


namespace AppStore\model;
require_once (__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');


class CommentRepository extends Repository
{
    private $mySqli;

    public function __construct()
    {
        parent::__construct();
        $this->mySqli = $this->getConnection();
    }


    public function submitComment(string $access,string $detail,float $rate,string $packageName) : string
    {
        $selectStmt = new \mysqli_stmt($this->mySqli,'select access from comment where access = ? and package_name = ?');
        $selectStmt->bind_param('ss',$access,$packageName);
        $success = $selectStmt->execute();
        if ($success)
        {
            $result = $selectStmt->get_result();
            if ($result->num_rows > 0)
            {
                $success = $this->updateComment($access, $detail, $rate, $packageName);
                if ($success)
                {
                    $mainResult = 2;
                }else
                {
                    $mainResult = -1;
                }
            } else
                {
                $success = $this->insertComment($access, $detail, $rate, $packageName);
                if ($success)
                {
                    $mainResult = 1;
                }else
                {
                    $mainResult = -1;
                }
                }
        }else
            {
                $mainResult = -1;
            }

        $selectStmt->close();
        return $mainResult;
    }


    public function deleteComment(string $access,string $packageName) : bool
    {
        $stmt = new \mysqli_stmt($this->mySqli,'delete from comment where access = ? and package_name = ?');
        $stmt->bind_param('ss',$access,$packageName);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }


    private function insertComment(string $access,string $detail,float $rate,string $packageName) : bool
    {
        $selectStmt = new \mysqli_stmt($this->mySqli,'select username from user where access = ? limit 1');
        $selectStmt->bind_param('s',$access);
        $success = $selectStmt->execute();

        if ($success)
        {
            $result = $selectStmt->get_result();
            $row  = $result->fetch_assoc();
            $username = $row['username'];
            $stmt = new \mysqli_stmt($this->mySqli,'insert into comment values(?,?,?,?,?)');
            $stmt->bind_param('ssdss',$username,$detail,$rate,$packageName,$access);
            $success = $stmt->execute();
            $stmt->close();
        }

        $selectStmt->close();
        return $success;
    }


    private function updateComment(string $access,string $detail,float $rate,string $packageName) : bool
    {
        $stmt = new \mysqli_stmt($this->mySqli,'update comment set detail = ? and rate = ? where access = ? and package_name = ?');
        $stmt->bind_param('sdss',$detail,$rate,$access,$packageName);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

}