<?php


namespace App\Application\Object;


use PDO;
use PDOException;

/**
 * Class Database
 * @package App\Application\Object
 * Cette class est nécessaire à l'instantiation de connection de base de données
 * Elle contient notemment les informations de connection
 */
class Database
{
    private $dbName = "db";
    private $host = "localhost";
    private $user = "user";
    private $password = "password";

    /**
     * Cette fonction créé une instance de base de donnée
     * @return PDO|null contient l'objet pdo nécessaire au requètes
     */
    public function getConnection () {
        $connection = null;

        try{
            $connection = new PDO('mysql:host='. $this->host .';dbname=' . $this->dbName, $this->user, $this->password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }

        return $connection;
    }
}
