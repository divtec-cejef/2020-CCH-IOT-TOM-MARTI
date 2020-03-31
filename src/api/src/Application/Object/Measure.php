<?php


namespace App\Application\Object;

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

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args contient from et to
     */
    public function getFromTo (Request $request, Response $response, array $args) {}

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args contient id
     */
    public function update (Request $request, Response $response, array $args) {}

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args contient id
     */
    public function delete (Request $request, Response $response, array $args) {}
}
