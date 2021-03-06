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


    public function getHome() : string
    {
        $stmt = new \mysqli_stmt($this->mysqli,'select * from home');
        $success = $stmt->execute();
        if ($success)
        {
            $result = $stmt->get_result();
            $slider = array();
            $r = array();
            while ($row = $result->fetch_assoc())
            {
                if ($row['type'] == 'row')
                {
                    array_push($r,array('category'=>$row['category'],'categoryName'=>$row['category_name'],'categoryNameEn'=>$row['category_name_en']));

                }else if ($row['type'] == 'slider')
                {
                    array_push($slider,$row['package_name']);
                }
            }
            $mainResult = json_encode(array('row'=>$r,'slider'=>$slider),JSON_UNESCAPED_UNICODE);
        }
        else
        {
            $mainResult = -1;
        }

        $stmt->close();
        return $mainResult;
    }


    public function getCategories() : string
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
            $mainResult = json_encode($array,JSON_UNESCAPED_UNICODE);
        }
        else
        {
            $mainResult = -1;
        }

        $stmt->close();
        return $mainResult;
    }


    public function getApps(int $offset) : string
    {
        $stmt = new \mysqli_stmt($this->mysqli,'select package_name,name_fa,name_en,dev_fa,dev_en from app limit 10 offset '.$offset);
        $success = $stmt->execute();
        if ($success)
        {
            $array = array();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc())
            {
                array_push($array,$row);
            }
            $mainResult = json_encode($array,JSON_UNESCAPED_UNICODE);
        }
        else
        {
            $mainResult = -1;
        }

        $stmt->close();
        return $mainResult;
    }


    public function getAppsByCategory(int $offset,string $category) : string
    {
        if ($category == 'all')
        {
            $stmt = new \mysqli_stmt($this->mysqli,'select package_name,name_fa,name_en,dev_fa,dev_en from app limit 10 offset '.$offset);
        }else
        {
            $stmt = new \mysqli_stmt($this->mysqli,'select package_name,name_fa,name_en,dev_fa,dev_en from app where category = ? limit 10 offset '.$offset);
        }

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
            $mainResult = json_encode($array,JSON_UNESCAPED_UNICODE);
        }
        else
        {
            $mainResult = -1;
        }

        $stmt->close();
        return $mainResult;
    }


     public function getApp(string $packageName) : string
    {
        $stmt = new \mysqli_stmt($this->mysqli,'select * from app where package_name = ? limit 1');
        $stmt->bind_param('s',$packageName);
        $success = $stmt->execute();
        if ($success)
        {
            $result = $stmt->get_result();
            if ($result->num_rows>0)
            {
                $row = $result->fetch_assoc();
                $rateStr = $this->getRatings($packageName);
                if ($rateStr == '-1')
                {
                    $mainResult = -1;
                }else
                {
                    $row['rate'] = $rateStr;
                    $mainResult = json_encode($row,JSON_UNESCAPED_UNICODE);
                }
            }else
            {
                $mainResult = array();
            }
        }
        else
        {
            $mainResult = -1;
        }

        $stmt->close();
        return $mainResult;
    }


    public function getTitlesSearch(string $query) : string
    {
        if (preg_match("/[^\x{0600}-\x{06FF}\s]+$/u",$query) > 0)
        {
            $stmt = new \mysqli_stmt($this->mysqli,'select name_fa from app where name_fa like ? or name_en like ? or tag like ? limit 7');
        }else
        {
            $stmt = new \mysqli_stmt($this->mysqli,'select name_en from app where name_fa like ? or name_en like ? or tag like ? limit 7');
        }

        $query = '%'.$query.'%';

        $stmt->bind_param('sss',$query,$query,$query);
        $success = $stmt->execute();
        if ($success)
        {
            $array = array();
            $result = $stmt->get_result();
            while ($row = $result->fetch_row())
            {
                array_push($array,$row[0]);
            }
            $mainResult = json_encode($array,JSON_UNESCAPED_UNICODE);
        }
        else
        {
            $mainResult = -1;
        }

        $stmt->close();
        return $mainResult;
    }


    public function getAppsSearch(int $offset, string $query) : string
    {
        $stmt = new \mysqli_stmt($this->mysqli,'select package_name,name_fa,name_en,dev_fa,dev_en from app where name_fa like ? or name_en like ? or tag like ? limit 10 offset '.$offset);

        $query = '%'.$query.'%';

        $stmt->bind_param('sss',$query,$query,$query);
        $success = $stmt->execute();
        if ($success)
        {
            $array = array();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc())
            {
                array_push($array,$row);
            }
            $mainResult = json_encode($array,JSON_UNESCAPED_UNICODE);
        }
        else
        {
            $mainResult = -1;
        }

        $stmt->close();
        return $mainResult;
    }


    public function getUpdates(int $offset, array $packageNames) : string
    {
        $packages = '';
        for ($i = 0;$i < count($packageNames);$i++)
        {
            if ($i < count($packageNames)-1)
            {
                $packages .= $packageNames[$i]['packageName'].',';
            }else
            {
                $packages .= $packageNames[$i]['packageName'];
            }
        }

        $stmt = new \mysqli_stmt($this->mysqli,'select package_name,name_fa,name_en,ver_code,ver_name from app where package_name in (?) limit 10 offset '.$offset);
        $stmt->bind_param('s',$packages);
        $success = $stmt->execute();
        if ($success)
        {
            $array = array();
            $result = $stmt->get_result();
            if ($result->num_rows>0)
            {
                while ($row = $result->fetch_assoc())
                {
                    $key = array_search($row['packageName'],array_column($packageNames,'packageName'));
                    $value = $packageNames[$key];
                    if ($row['ver_code'] > $value['verCode'])
                    {
                        array_push($array,$row);
                    }
                }
            }
            $mainResult = json_encode($array,JSON_UNESCAPED_UNICODE);
        }
        else
        {
            $mainResult = -1;
        }

        $stmt->close();
        return $mainResult;
    }


    private function getRatings(string $packageName) : string
    {
        $stmt = new \mysqli_stmt($this->mysqli,'select rate from comment where package_name = ?');
        $stmt->bind_param('s',$packageName);
        $success = $stmt->execute();
        if ($success)
        {
            $result = $stmt->get_result();
            $rate = 0.0;

            if ($result->num_rows > 0)
            {

                while ($row = $result->fetch_assoc())
                {
                    $rateRow = $row['rate'];
                    settype($rate,'float');
                    $rate += $rateRow;
                }

                $rating = $rate/$result->num_rows;

            }else
            {
               $rating = 0.0;
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

}