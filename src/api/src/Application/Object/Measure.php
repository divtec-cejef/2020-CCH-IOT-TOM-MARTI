<?php


namespace App\Application\Object;

use Cassandra\Date;
use DateTime;
use PDO;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Measure
{
    /**
     * Metier de la route GET /api/measures
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Exception
     */
    public function get (Request $request, Response $response, array $args) {
        $today = (new \DateTime())->format('Y-m-d');
        $todayMorning = $today . " 00:00:00";
        $todayEvening = $today . " 23:59:59";

        $req = null;
        try {
            // récupération des mesure
            $query = "SELECT measure.id, time, temperature, humidity, name FROM measure INNER JOIN device ON measure.id_device = device.id WHERE time > '$todayMorning' AND time < '$todayEvening' ORDER BY time ASC";
            $db = new Database();
            $conn = $db->getConnection();
            $req = $conn->prepare($query);
            $req->setFetchMode(PDO::FETCH_ASSOC);
            $req->execute();
        } catch (\Exception $e) {
            $payload = json_encode(array(
                "error" => [
                    "code" => 500,
                    "message" => "Request fail",
                    "name" => "getRequestFail"
                ]
            ));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }

        // récupération des données
        $maxT = -200;
        $minT = 200;
        $lastT = 0;
        $maxH = -200;
        $minH = 200;
        $lastH = 0;
        $lastDate = "";
        $lastId = 0;

        while ($row = $req->fetch()) {
            $maxT = $row["temperature"] > $maxT ? $row["temperature"] : $maxT;
            $maxH = $row["humidity"] > $maxH ? $row["humidity"] : $maxH;
            $minT = $row["temperature"] < $minT ? $row["temperature"] : $minT;
            $minH = $row["humidity"] < $minH ? $row["humidity"] : $minH;
            $lastT = $row["temperature"];
            $lastH = $row["humidity"];
            $lastDate = $row["time"];
            $lastId = $row["id"];
        }

        $payload = json_encode(array(
            "temperature" => [
                "max" => $maxT,
                "min" => $minT,
                "last" => $lastT
            ],
            "humidity" => [
                "max" => $maxH,
                "min" => $minH,
                "last" => $lastH
            ],
            "last" => [
                "id" => $lastId,
                "date" => $lastDate,
                "temperature" => $lastT,
                "humidity" => $lastH
            ]
        ));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function getFromTo (Request $request, Response $response, array $args) {
        // valider les dates Y-m-d
        $from = explode("-", $args["from"]);
        $fromIsValid = checkdate($from[1], $from[2], $from[0]);
        $to = explode("-", $args["to"]);
        $toIsValid = checkdate($to[1], $to[2], $to[0]);
        if ($fromIsValid && $toIsValid) {
            $from = $args["from"];
            $to = $args["to"];

            $fromMorning = $from . " 00:00:00";
            $fromEvening = $from . " 23:59:59";
            $toMorning = $to . " 00:00:00";
            $toEvening = $to . " 23:59:59";

            $req = null;

            try {
                $query = "SELECT measure.id, time, temperature, humidity, name FROM measure INNER JOIN device ON measure.id_device = device.id WHERE time > '$fromMorning' AND time < '$toEvening' ORDER BY time ASC";
                $db = new Database();
                $conn = $db->getConnection();
                $req = $conn->prepare($query);
                $req->setFetchMode(PDO::FETCH_ASSOC);
                $req->execute();
            } catch (\PDOException $e) {
                $payload = json_encode(array(
                    "error" => [
                        "code" => 500,
                        "message" => "Request fail",
                        "name" => "getRequestFail"
                    ]
                ));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
            }

            $day = new DateTime($fromEvening);
            $data = [];
            $maxT = -200;
            $minT = 200;
            $lastT = 0;
            $maxH = -200;
            $minH = 200;
            $lastH = 0;

            while ($row = $req->fetch()) {
                $dbtime = new DateTime($row['time']);
                if ($dbtime < $day) {
                    $maxT = $row["temperature"] > $maxT ? $row["temperature"] : $maxT;
                    $maxH = $row["humidity"] > $maxH ? $row["humidity"] : $maxH;
                    $minT = $row["temperature"] < $minT ? $row["temperature"] : $minT;
                    $minH = $row["humidity"] < $minH ? $row["humidity"] : $minH;
                    $lastT = $row["temperature"];
                    $lastH = $row["humidity"];
                } else {
                    // add to data
                    $data[] = array(
                        "temperature" => [
                            "max" => $maxT,
                            "min" => $minT,
                            "last" => $lastT
                        ],
                        "humidity" => [
                            "max" => $maxH,
                            "min" => $minH,
                            "last" => $lastH
                        ],
                        "date" => $day->format("Y-m-d")
                    );
                    $maxT = -200;
                    $minT = 200;
                    $lastT = 0;
                    $maxH = -200;
                    $minH = 200;
                    $lastH = 0;
                    $day->modify('+1 day');
                }
            }
            $data[] = array(
                "temperature" => [
                    "max" => $maxT,
                    "min" => $minT,
                    "last" => $lastT
                ],
                "humidity" => [
                    "max" => $maxH,
                    "min" => $minH,
                    "last" => $lastH
                ],
                "date" => $day->format("Y-m-d")
            );

            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
        $response->getBody()->write(json_encode(["error" => [
            "code" => 412,
            "Message" => "You dont have valid dates"
        ]]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(412);
    }

    public function update (Request $request, Response $response, array $args) {
        $id = $args['id'];
        parse_str(file_get_contents("php://input"),$_PUT);

        if (isset($_PUT['temperature']) && isset($_PUT['humidity'])) {
            try {
                $query = "UPDATE measure SET temperature = :temp, humidity = :hum WHERE id = :id";
                $db = new Database();
                $conn = $db->getConnection();
                $req = $conn->prepare($query);
                $req->bindParam(":temp", $_PUT['temperature']);
                $req->bindParam(":hum", $_PUT['humidity']);
                $req->bindParam(":id", $id);
                $req->setFetchMode(PDO::FETCH_ASSOC);
                $req->execute();
            } catch (\PDOException $e) {
                $payload = json_encode(array(
                    "error" => [
                        "code" => 500,
                        "message" => "Request fail",
                        "name" => "getRequestFail"
                    ]
                ));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
            }
            $response->getBody()->write(json_encode([
                "temperature" => $_PUT['temperature'],
                "humidity" => $_PUT['humidity'],
                "id" => $id
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
        $response->getBody()->write(json_encode(["error" => [
            "code" => 412,
            "Message" => "You dont have valid dates"
        ]]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(412);
    }


    public function delete (Request $request, Response $response, array $args) {
        $id = $args['id'];

            try {
                $query = "DELETE FROM measure WHERE id = :id";
                $db = new Database();
                $conn = $db->getConnection();
                $req = $conn->prepare($query);
                $req->bindParam(":id", $id);
                $req->setFetchMode(PDO::FETCH_ASSOC);
                $req->execute();
            } catch (\PDOException $e) {
                $payload = json_encode(array(
                    "error" => [
                        "code" => 500,
                        "message" => "Request fail",
                        "name" => "getRequestFail"
                    ]
                ));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
            }

            $response->getBody()->write(json_encode(["id" => $id]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
}
