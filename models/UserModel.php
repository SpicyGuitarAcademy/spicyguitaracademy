<?php
namespace Models;
use Framework\Database\Model;

class UserModel extends Model
{
   public function __construct()
   {
      parent::__construct('init_user_tbl');
   }

   // write wonderful model codes...

   public function addUser($username) {
      $status = $this->create([
         "username" => $username
      ]);
      return $status;
   }

   public function getUsers() {
      return $this->read("*");
   }

}
