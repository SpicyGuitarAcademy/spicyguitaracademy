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

   public function emailExists($email)
   {
      return $this->where("email = '$email'")->exist();
   }

   public function addAuthDetails($email, $hpassword, $role, $token = "")
   {
      $add = $this->create([
         'email' => $email,
         'password' => $hpassword,
         'role' => $role,
         'token' => $token
      ]);

      if ($add == true) {
         return $this->lastId();
      } else {
         false;
      }
   }

   public function getAuthStatus(string $email)
   {
      return $this->where("email = '$email'")
         ->misc("LIMIT 1")
         ->read("status");
   }

   public function getAuthPrivileges(string $email)
   {
      return $this->where("email = '$email'")
         ->misc("LIMIT 1")
         ->read("privileges");
   }

   public function updatePrivileges($email, $privileges)
   {
      return $this->where("email = '$email'")->update([
         "privileges" => $privileges
      ]);
   }

   public function getLoginDetails(string $email)
   {
      return $this->where("email = '$email'")
         ->misc("LIMIT 1")
         ->read("id, email, password, role, status");
   }

   public function updateStatus($email, $status)
   {
      return $this->where("email = '$email'")->update([
         "status" => $status
      ]);
   }

   public function updatePassword($email, $password)
   {
      return $this->where("email = '$email'")->update([
         "password" => $password
      ]);
   }

   public function updateToken($email, $token)
   {
      return $this->where("email = '$email'")->update([
         "token" => $token
      ]);
   }

   public function updateDevice($email, $device)
   {
      return $this->where("email = '$email'")->update([
         "device" => $device
      ]);
   }

   public function verifyEmailToken($email, $token)
   {
      return $this->where("email = '$email' AND token = '$token'")->exist();
   }
}
