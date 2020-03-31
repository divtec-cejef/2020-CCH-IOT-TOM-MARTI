<?php


namespace App\Application\Object;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


/**
 * Class Callback
 * Class métier de la route callback
 * @package App\Application\Object
 */
class Callback
{
    /**
     * Métier de la route /api/callback
     * @param $request Request éléments de la requète
     * @param $response Response éléments de la réponse
     * @param $args array arguments de la route
     * @return Response slim response
     */
    public function process (Request $request, Response $response, $args) {
        $id = $_POST['id'];
        $time = $_POST['time'];
        $temphum = $_POST['data'];

        // extraction et transformation des température
        $temp = hexdec(substr($temphum, 0, 2));
        $hum = hexdec(substr($temphum, -2, 2));

        if (isset($id) && isset($time) && isset($temp) && isset($hum)) {
            // transformation de timestamp en datetime
            $time = date('Y-m-d H:m:s', $time);
            if ($this->insert($id, $time, $temp, $hum)) {

                // envoie de la réponse valide
                $data = array(
                    "device" => $id,
                    "temp" => $temp,
                    "humidity" => $hum
                );
                $payload = json_encode($data);
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
            }
        }

        // envoie de la réponse d'erreur
        $data = array(
            "error" => [
                "code" => 500,
                "message" => "Erreur lors de l'insertion dans la base",
                "name" => "CallbackInsertFail"
            ]
        );
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }


    /**
     * Insère une mesure dans la base
     * @param $id string nom du device
     * @param $time string instant ou la mesure é été prise
     * @param $temp int température à insérer
     * @param $hum int humidité à insérer
     * @return bool un booléan si l'insertion a fonctionné ou pas
     */
    private function insert ($id, $time, $temp, $hum) {
        try {
            $db = new Database();
            $connection = $db->getConnection();

            // insert du device
            $query = "INSERT IGNORE INTO device VALUES (null, :device, null)";
            $req = $connection->prepare($query);
            $req->bindParam(":device", $id);
            $req->execute();

            // insert de la mesure
            $query = 'INSERT INTO measure VALUES (null, :tt, :temperature, :humidity, (SELECT id FROM device WHERE name = :id))';
            $req = $connection->prepare($query);
            $req->bindParam(":tt", $time);
            $req->bindParam(":temperature", $temp);
            $req->bindParam(":humidity", $hum);
            $req->bindParam(":id", $id);
            $req->execute();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
