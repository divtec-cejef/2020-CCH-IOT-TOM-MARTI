<?php


namespace App\Application\Object;


use PDO;
use PDOException;

class Database
{
    private $dbName = "db";
    private $host = "localhost";
    private $user = "user";
    private $password = "password";

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
