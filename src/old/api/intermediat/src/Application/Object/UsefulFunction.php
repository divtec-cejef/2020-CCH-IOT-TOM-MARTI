<?php


namespace App\Application\Object;


use DateTime;
use PhpParser\Node\Expr\Cast\Object_;

class UsefulFunction
{
    public static function getOnePerDay ($req, $firstdate) {
        $data = [];

        $ttemp = 0;
        $thum = 0;
        $index = 0;
        $previousTime = null;
        $previousRoom = null;
        $date2 = null;
        $minT = 200;
        $maxT = -200;
        $minH = 200;
        $maxH = -200;
        //$date1 = new DateTime($firstdate);
        try {
            $date2 = new DateTime($firstdate . " 23:59:59");
        } catch (\Exception $e) {
            echo $e;
        }

        $timestamp = strtotime($date2->format('Y-m-d H:i:s'));

        while ($row = $req->fetch(\PDO::FETCH_ASSOC)) {
            if (intval($row['time']) < $timestamp) {
                $ttemp += $row['temperature'];
                $thum += $row['humidity'];
                $minT = $minT > $row['temperature'] ? $row['temperature'] : $minT;
                $minH = $minH > $row['humidity'] ? $row['humidity'] : $minH;
                $maxT = $maxT < $row['temperature'] ? $row['temperature'] : $maxT;
                $maxH = $maxH < $row['humidity'] ? $row['humidity'] : $maxH;
                $previousTime = $row['time'];
                $previousRoom = $row['name'];
                $index++;
            } else {
                if ($index > 0) {
                    $data[] = array(
                        "temperature" => $ttemp / $index,
                        "humidity" => $thum / $index,
                        "date" => date('Y-m-d', $previousTime),
                        "room" => $previousRoom,
                        "minT" => $minT,
                        "maxT" => $maxT,
                        "minH" => $minH,
                        "maxH" => $maxH
                    );
                }
                $date2->modify('+1 day');
                $timestamp = strtotime($date2->format('Y-m-d H:i:s'));
                $ttemp = $row['temperature'];
                $thum = $row['humidity'];
                $previousTime = $row['time'];
                $index = 0;
                $minT = 200;
                $maxT = -200;
                $minH = 200;
                $maxH = -200;
            }
        }
        if ($req->rowCount() > 0) {
            $data[] = array(
                "temperature" => $ttemp / $index,
                "humidity" => $thum / $index,
                "date" => date('Y-m-d', $previousTime),
                "room" => $previousRoom,
                "minT" => $minT,
                "maxT" => $maxT,
                "minH" => $minH,
                "maxH" => $maxH
            );
        }
        return $data;
    }
}
