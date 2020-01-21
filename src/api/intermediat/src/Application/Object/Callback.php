<?php


namespace App\Application\Object;


use PDO;

class Callback
{
    public function process ($request, $response, $args) {
        $id = $_POST['id'];
        $time = $_POST['time'];
        $room = $_POST['room'];
        $temphum = $_POST['data'];

        $temp = substr($temphum, 0, 2);
        $hum = substr($temphum, -2, 2);

        if (isset($id) && isset($time) && isset($temp) && isset($hum) && isset($room)) {
            $this->insert($id, $time, $temp, $hum, $room);
        }
        $data = array(
            "device" => $id,
            "temp" => $temp,
            "humidity" => $hum
        );
        $payload = json_encode($data);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    private function insert ($id, $time, $temp, $hum, $room) {
        $db = new Database();
        $connection = $db->getConnection();

        $query = "INSERT IGNORE INTO device VALUES (null, :device, :room)";

        $req = $connection->prepare($query);

        $req->bindParam(":device", $id);
        $req->bindParam(":room", $room);

        $req->execute();

        $connection = $db->getConnection();
        $query = 'INSERT INTO measure VALUES (null, :tt, :temperature, :humidity, (SELECT id FROM device WHERE name = :id))';

        $req = $connection->prepare($query);

        $req->bindParam(":tt", $time);
        $req->bindParam(":temperature", $temp);
        $req->bindParam(":humidity", $hum);
        $req->bindParam(":id", $id, PDO::PARAM_STR);

        $req->execute();
    }
}
