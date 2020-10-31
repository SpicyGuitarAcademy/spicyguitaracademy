<?php
namespace Models;
use Framework\Database\Model;

class AuthModel extends Model
{
   public function __construct()
   {
      parent::__construct('auth_tbl');
   }

   // write wonderful model codes...

   public function emailExists($email) {
      return $this->where("email = '$email'")->exist();
   }

   public function addAuthDetails($email, $hpassword, $role) {
      $add = $this->create([
         'email' => $email,
         'password' => $hpassword,
         'role' => $role
      ]);

      if ($add == true) {
         return $this->lastId();
      } else {
         false;
      }
   }

   public function getLoginDetails(string $email) {
      return $this->where("email = '$email'")
      ->misc("LIMIT 1")
      ->read("id, email, password, role, status");
   }

   public function updateStatus($email, $status) {
      return $this->where("email = '$email'")->update([
         "status"=>$status
      ]);
   }
}
