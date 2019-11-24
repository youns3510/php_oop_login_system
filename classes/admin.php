<?php

include_once(__DIR__ . '/user.php');

class Admin extends User
{

  public function showUsers()
  {
    $q = "SELECT  `id` ,  `firstName`,  `lastName` ,  `email` ,  `contactNumber` ,  `is_admin` , `active` ,  `created` FROM `{$this->db_name}`.`{$this->table_name}`;";
    // echo $q;
    $stmt = $this->pdo->query($q);
    return $stmt;
  }
}
