<?php
use App;

class User
{

   public $username;
   public $role;
   public $privileges;
   
   public function __construct()
   {

   }

   public function set_credentials(object $credentials)
   {
      $this->username = $credentials->username;
      $this->role = $credentials->role;
      $this->privileges = $credentials->privileges;
   }

   // method for turning arrays to objects
   private function objectify(array $array)
   {
      if (is_array($array) && $array !== []) {
         $obj = new \stdClass();
         foreach ($array as $key => $value) {
            // convert the value into an object if value is also an array
            if (is_array($value)) {
               $value = $this->objectify($value);
            }
            // assign the value to the key
            $obj->$key = $value;
         }

         $value = null;
         return $obj;
      }

      return null;
   }

}