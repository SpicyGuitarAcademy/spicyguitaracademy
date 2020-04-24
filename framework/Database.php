<?php
namespace Framework;
use PDO;
use PDOException;
use Framework\Error;

class Database
{

   private $driver = \Config["DB_CONNECTION"];
   private $host = \Config["DB_HOST"];
   private $port = \Config["DB_PORT"];
   private $database = \Config["DB_DATABASE"];
   private $username = \Config["DB_USERNAME"];
   private $password = \Config["DB_PASSWORD"];

   protected function __construct(){
      $conn;

      try {
         $conn = new PDO("$this->driver:host=$this->host;dbname=$this->database;port=$this->port", $this->username, $this->password);
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }
      catch(PDOException $e)
      {
         // handle error
         Error::internalError($e->getMessage());
      }

      return $conn;
   }

}

?>