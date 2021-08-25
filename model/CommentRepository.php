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


    public function getComments(string $access,string $packageName,int $offset) : string
    {
        $stmt = new \mysqli_stmt($this->mySqli,'select * from comment where package_name = ? limit 25 offset '.$offset);
        $stmt->bind_param('s',$packageName);
        $success = $stmt->execute();
        if ($success)
        {
            $result = $stmt->get_result();
            $array = array();

            if ($result->num_rows > 0)
            {
                while ($row = $result->fetch_assoc())
                {
                    if ($row['access'] == $access)
                    {
                        $row['isAccess'] = 1;
                    }else
                    {
                        $row['isAccess'] = 0;
                    }
                    array_push($array,$row);
                }
            }

            $mainResult = json_encode($array);

        }else
        {
            $mainResult = -1;
        }

        $stmt->close();
        return $mainResult;
    }


    public function getRatings(string $packageName) : string
    {
        $stmt = new \mysqli_stmt($this->mySqli,'select rate from comment where package_name = ?');
        $stmt->bind_param('s',$packageName);
        $success = $stmt->execute();
        if ($success)
        {
            $result = $stmt->get_result();
            $rate = 0.0;

            if ($result->num_rows > 0)
            {
                $i = 0;

                while ($row = $result->fetch_assoc())
                {
                    $rate = $row['rate'];
                    settype($rate,'float');
                    $rate += $rate;
                    $i++;
                }

                $rating = $rate/$i;

            }
            settype($rating,'string');
            $mainResult = $rating;

        }else
        {
            $mainResult = -1;
        }

        $stmt->close();
        return $mainResult;
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