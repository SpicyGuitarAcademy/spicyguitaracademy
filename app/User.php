<?php
use App;

class User
{

   public function __construct(array $credentials)
   {

      // make the credentials properties of the user
      $credentials = $this->objectify($credentials);
      foreach ($credentials as $key => $value) {
         $this->$key = $value;
      }

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