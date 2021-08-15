<?php


namespace AppStore\model;
require_once (__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'utils'.DIRECTORY_SEPARATOR.'SecurityManager.php');
use AppStore\utils as utils;

class Repository
{
    private $mySqli = null;
    private $securityManager = null;

    public function __construct()
    {
        try
        {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $this->mySqli = new \mysqli('localhost','bermoo_store','com.amirhosseinemadi.store','bermoo_store');
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


    protected function initToken(string $uid,string $token) : bool
    {
        $deleteStmt = new \mysqli_stmt($this->mySqli,'delete from push where uid = ?');
        $deleteStmt->bind_param('s',$uid);
        $deleteStmt->execute();
        $deleteStmt->close();

        $insertStmt = new \mysqli_stmt($this->mySqli,"insert into push(uid,token) values(?,?)");
        $insertStmt->bind_param('ss',$uid,$token);
        $iBool = $insertStmt->execute();
        $insertStmt->close();

        return $iBool;
    }


    protected function syncToken(string $uid,string $token) : bool
    {
        $success = false;
        $stmt = new \mysqli_stmt($this->mySqli,'select * from push where uid = ?');
        $stmt->bind_param('s',$uid);
        $bool = $stmt->execute();
        if ($bool)
        {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc())
            {
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
                break;
            }
        }

        $stmt->close();
        return $success;
    }


    protected function signUpUser(string $mail,string $password,string $username,string $token) : string
    {
        $mainResult = -1;
        $selectStmt = new \mysqli_stmt($this->mySqli,'select * from user where mail = ?');
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
                $insertStmt = new \mysqli_stmt($this->mySqli,'insert into user(mail,password,username,token,access) values(?,?,?,?,?)');
                $hashPassword = $this->securityManager->encryptHash($this->securityManager->encryptHash($password));
                $access = $this->securityManager->getRandomToken();
                $insertStmt->bind_param('sssss',$mail,$hashPassword,$username,$token,$access);
                $success = $insertStmt->execute();
                $insertStmt->close();
                if ($success)
                {
                    $array = array('username' => $username, 'access' => $access);
                    $mainResult = json_encode($array);
                }
            }
        }

        $selectStmt->close();
        return $mainResult;
    }


    protected function signInUser(string $mail,string $password)
    {
        $mainResult = -1;
        $stmt = new \mysqli_stmt($this->mySqli,'select * from user where mail = ? and password = ?;');
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
                $mainResult = json_encode($array);
            }else
            {
                $mainResult = 0;
            }
        }

        $stmt->close();
        return $mainResult;
    }


    protected function syncUser(string $access,string $token)
    {
        $mainResult = -1;
        $stmt = new \mysqli_stmt($this->mySqli,'select * from user where access = ?;');
        $stmt->bind_param('s',$access);
        $success = $stmt->execute();
        if ($success)
        {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc())
            {
                if ($row['token'] != $token)
                {
                    $updateStmt = new \mysqli_stmt($this->mySqli, 'update user set token = ? where access = ?;');
                    $updateStmt->bind_param('ss', $token,$access);
                    if ($updateStmt->execute())
                    {
                        $mainResult = 0;
                    }
                }else
                    {
                        $mainResult = 0;
                    }
                $updateStmt->close();
            }
        }

        $stmt->close();
        return $mainResult;
    }


    protected function closeDb()
    {
        $this->mySqli->close();
    }
    

}