<?php
include_once(__DIR__.'/../config/constants.php');
class Database
{
  private $host, $db_name, $user, $password, $pdo;
 
  public function __construct()
  {
    $this->host     =  DB_HOST;
    $this->db_name  =  DB_NAME;
    $this->user     =  DB_USER;
    $this->password =  DB_PASSWORD;
    $this->pdo = null;
  }

  function getConnection()
  {
    try {
      $dsn = "mysql:host={$this->host};db_name={$this->db_name};";
      $this->pdo = new PDO($dsn, $this->user, $this->password);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // echo 'success';

    } catch (PDOException $e) {
      echo "Connecton Error :" . $e->getMessage();
    }

    return  $this->pdo;
  }
}
