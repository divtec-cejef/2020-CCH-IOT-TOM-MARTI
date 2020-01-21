<?php


namespace App\Application\Object;


class Device
{
    public function getAll ($request, $response, $args) {
        $db = new Database();
        $connection  = $db->getConnection();

        $query = "SELECT device.id, device.name as dname, room.name as rname FROM device INNER JOIN room ON device.id_room = room.id";

        $req = $connection->prepare($query);

        $req->execute();

        $data = [];

        while ($row = $req->fetch(\PDO::FETCH_ASSOC)) {
            $data[] = array(
                "id" => $row['id'],
                "name" => $row['dname'],
                "room" => $row['rname']
            );
        }
        $payload = json_encode($data);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function get ($request, $response, $args) {
        $db = new Database();
        $connection  = $db->getConnection();

        $query = "SELECT device.id, device.name as dname, room.name as rname FROM device INNER JOIN room ON device.id_room = room.id WHERE device.name = :name";

        $req = $connection->prepare($query);

        $req->bindParam(':name', $args['id']);

        $req->execute();
        $data = [];
        if ($req->rowCount() > 0) {
            $result = $req->fetchAll(\PDO::FETCH_ASSOC);

            $data = [
                "id" => $result[0]['id'],
                "name" => $result[0]['dname'],
                "room" => $result[0]['rname']
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

    public function getMessage ($request, $response, $args) {
        $db = new Database();
        $connection  = $db->getConnection();

        $query = "SELECT measure.id, time, temperature, humidity FROM measure WHERE ";

        $req = $connection->prepare($query);

        $req->bindParam(':name', $args['id']);

        $req->execute();
        $data = [];
        if ($req->rowCount() > 0) {
            $result = $req->fetchAll(\PDO::FETCH_ASSOC);

            $data = [
                "id" => $result[0]['id'],
                "name" => $result[0]['dname'],
                "room" => $result[0]['rname']
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
}
