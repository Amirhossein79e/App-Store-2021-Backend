<?php


namespace AppStore\model;
require_once (__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');
use AppStore\utils;

class Repository
{
    private $mySqli;
    private $securityManager;

    public function __construct()
    {
        try
        {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $this->mySqli = new \mysqli('localhost','*','*','*');
            $this->mySqli->set_charset('utf8mb4');

            if ($this->mySqli->connect_error)
            {
                die('Connection failed between server and database');
            }
        }catch (\Exception $exception)
        {
            echo $exception->getMessage();
            die('Connection failed between server and database');
        }

        $this->securityManager = utils\SecurityManager::getInstance();
    }


    protected function getConnection()
    {
        if ($this->mySqli != null)
        {
            return $this->mySqli;
        }
    }


    public function initToken(string $uid,string $token) : bool
    {
        $deleteStmt = new \mysqli_stmt($this->mySqli,'delete from push where uid = ?');
        $deleteStmt->bind_param('s',$uid);
        $success = $deleteStmt->execute();
        $deleteStmt->close();

        if ($success)
        {
            $insertStmt = new \mysqli_stmt($this->mySqli,'insert into push values(?,?)');
            $insertStmt->bind_param('ss',$uid,$token);
            $success = $insertStmt->execute();
            $insertStmt->close();
        }

        return $success;
    }


    public function syncToken(string $uid,string $token) : bool
    {
        $stmt = new \mysqli_stmt($this->mySqli,'select token from push where uid = ? limit 1');
        $stmt->bind_param('s',$uid);
        $success = $stmt->execute();
        if ($success)
        {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if ($row['token'] != $token)
            {
                $updateStmt = new \mysqli_stmt($this->mySqli,'update push set token = ? where uid = ?');
                $updateStmt->bind_param('ss',$token,$uid);
                $success = $updateStmt->execute();
                $updateStmt->close();
            }else
            {
                $success = true;
            }
        }

        $stmt->close();
        return $success;
    }


    public function signUpUser(string $mail,string $password,string $username,string $token) : string
    {
        $selectStmt = new \mysqli_stmt($this->mySqli,'select mail from user where mail = ?');
        $selectStmt->bind_param('s',$mail);
        $success = $selectStmt->execute();
        if ($success)
        {
            $result = $selectStmt->get_result();
            if ($result->num_rows > 0)
            {
                $mainResult = 0;
            }else
            {
                $insertStmt = new \mysqli_stmt($this->mySqli,'insert into user values(?,?,?,?,?)');
                $hashPassword = $this->securityManager->encryptHash($this->securityManager->encryptHash($password));
                $access = $this->securityManager->getRandomToken();
                $insertStmt->bind_param('sssss',$mail,$hashPassword,$username,$token,$access);
                $success = $insertStmt->execute();
                $insertStmt->close();
                if ($success)
                {
                    $array = array('username' => $username, 'access' => $access);
                    $mainResult = json_encode($array,JSON_UNESCAPED_UNICODE);
                }
            }
        }else
        {
            $mainResult = -1;
        }

        $selectStmt->close();
        return $mainResult;
    }


    public function signInUser(string $mail,string $password) : string
    {
        $stmt = new \mysqli_stmt($this->mySqli,'select username,access from user where mail = ? and password = ? limit 1');
        $hashPassword = $this->securityManager->encryptHash($this->securityManager->encryptHash($password));
        $stmt->bind_param('ss',$mail,$hashPassword);
        $success = $stmt->execute();

        if ($success)
        {
            $result = $stmt->get_result();
            if ($result->num_rows>0)
            {
                $row = $result->fetch_assoc();
                $array = array('username' => $row['username'], 'access' => $row['access']);
                $mainResult = json_encode($array,JSON_UNESCAPED_UNICODE);
            }else
            {
                $mainResult = 0;
            }
        }else
        {
            $mainResult = -1;
        }

        $stmt->close();
        return $mainResult;
    }


    public function syncUser(string $access,string $token) : string
    {
        $stmt = new \mysqli_stmt($this->mySqli,'select token from user where access = ? limit 1');
        $stmt->bind_param('s',$access);
        $success = $stmt->execute();
        if ($success)
        {
            $result = $stmt->get_result();
            if ($result->num_rows > 0)
            {
                $row = $result->fetch_assoc();
                if ($row['token'] != $token)
                {
                    $updateStmt = new \mysqli_stmt($this->mySqli, 'update user set token = ? where access = ?');
                    $updateStmt->bind_param('ss', $token,$access);
                    $success = $updateStmt->execute();
                    if ($success)
                    {
                        $mainResult = 1;
                    }else
                    {
                        $mainResult = -1;
                    }
                    $updateStmt->close();
                }else
                {
                    $mainResult = 1;
                }
            }else
            {
                $mainResult = 1;
            }
        }else
        {
            $mainResult = -1;
        }

        $stmt->close();
        return $mainResult;
    }


    public function validateUser(string $access) : string
    {
        $stmt = new \mysqli_stmt($this->mySqli,"select is_valid from user where access = ? limit 1");
        $stmt->bind_param("s",$access);
        $success = $stmt->execute();

        if ($success)
        {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $mainResult = $row['is_valid'];
        }else
        {
            $mainResult = -1;
        }

        $stmt->close();
        return $mainResult;
    }


    public function closeDb()
    {
        $this->mySqli->close();
    }
    

}
