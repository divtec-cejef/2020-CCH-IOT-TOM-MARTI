<?php


namespace App\Application\Object;


use DateTime;

class Room
{
    public function getAll ($request, $response, $args) {
        $db = new Database();
        $connection  = $db->getConnection();

        $query = "SELECT id, name FROM `room` WHERE id IN (SELECT id_room FROM `device` WHERE id_room > 0)";

        $req = $connection->prepare($query);

        $req->execute();

        $data = [];

        while ($row = $req->fetch(\PDO::FETCH_ASSOC)) {
            $data[] = array(
                "id" => $row['id'],
                "name" => $row['name']
            );
        }
        $payload = json_encode($data);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function get ($request, $response, $args) {
        $db = new Database();
        $connection  = $db->getConnection();

        $query = "SELECT id, name FROM room WHERE id = :id";

        $req = $connection->prepare($query);

        $req->bindParam(':id', $args['id']);

        $req->execute();
        $data = [];
        if ($req->rowCount() > 0) {
            $result = $req->fetchAll(\PDO::FETCH_ASSOC);

            $data = [
                "id" => $result[0]['id'],
                "name" => $result[0]['name']
            ];

        } else {
            $payload = json_encode(array(
                "error" => [
                    "code" => 404,
                    "message" => "device not found"
                ]
            ));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
        $payload = json_encode($data);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getMeasures ($request, $response, $args) {
        $db = new Database();
        $connection  = $db->getConnection();

        $query = "SELECT measure.id, time, temperature, humidity, room.name FROM measure INNER JOIN device ON measure.id_device = device.id INNER JOIN room ON device.id_room = room.id WHERE id_room = :id";

        $req = $connection->prepare($query);

        $req->bindParam(':id', $args['id']);

        $req->execute();
        $data = [];

        while ($row = $req->fetch(\PDO::FETCH_ASSOC)) {
            $data[] = array(
                "id" => $row['id'],
                "time" => date('Y-m-d H:i:s', $row['time']),
                "temperature" => $row['temperature'],
                "humidity" => $row['humidity'],
                "room" => $row['name']
            );
        }

        $payload = json_encode($data);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getMeasuresAtDate ($request, $response, $args) {
        $date = $args['date'];
        $test_arr  = explode('-', $date);
        if (checkdate($test_arr[1], $test_arr[2], $test_arr[0])) {
            $db = new Database();
            $connection = $db->getConnection();

            $query = "SELECT measure.id, time, temperature, humidity, room.name FROM measure INNER JOIN device ON measure.id_device = device.id INNER JOIN room ON device.id_room = room.id WHERE id_room = :id AND time >= :firstTime AND time <= :secondTime";

            $req = $connection->prepare($query);

            $firstDate = strtotime($args['date'] . " 00:00:00");
            $secondDate = strtotime($args['date'] . " 23:59:59");

            $req->bindParam(':id', $args['id']);
            $req->bindParam(':firstTime', $firstDate);
            $req->bindParam(':secondTime', $secondDate);

            $req->execute();
            $data = [];

            while ($row = $req->fetch(\PDO::FETCH_ASSOC)) {
                $data[] = array(
                    "id" => $row['id'],
                    "time" => date('Y-m-d H:i:s', $row['time']),
                    "temperature" => $row['temperature'],
                    "humidity" => $row['humidity'],
                    "room" => $row['name']
                );
            }

            $payload = json_encode($data);

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $payload = json_encode(array(
                "error" => [
                    "code" => 400,
                    "message" => "invalide date format"
                ]
            ));

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public function getMeasuresFromTo ($request, $response, $args) {
        $firstdate = $args['from'];
        $seconddate = $args['to'];
        $test_arr1  = explode('-', $firstdate);
        $test_arr2  = explode('-', $seconddate);
        if (checkdate($test_arr1[1], $test_arr1[2], $test_arr1[0]) && checkdate($test_arr2[1], $test_arr2[2], $test_arr2[0])) {
            $db = new Database();
            $connection = $db->getConnection();

            $query = "SELECT measure.id, time, temperature, humidity, room.name FROM measure INNER JOIN device ON measure.id_device = device.id INNER JOIN room ON device.id_room = room.id WHERE room.id = :id AND time >= :firstTime AND time <= :secondTime ORDER BY time";

            $req = $connection->prepare($query);

            $firstDate = strtotime($firstdate . " 00:00:00");
            $secondDate = strtotime($seconddate . " 23:59:59");

            $req->bindParam(':firstTime', $firstDate);
            $req->bindParam(':secondTime', $secondDate);
            $req->bindParam(':id', $args['id']);

            $req->execute();
            $data = UsefulFunction::getOnePerDay($req, $firstdate);

            $payload = json_encode($data);

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $payload = json_encode(array(
                "error" => [
                    "code" => 400,
                    "message" => "invalide date format"
                ]
            ));

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
}
