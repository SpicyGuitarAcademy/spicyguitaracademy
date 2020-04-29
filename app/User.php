<?php
namespace App;

class User
{

   private static $credentials;

   public function __construct(array $credentials)
   {

      // make the credentials properties of the user
      $credentials = self::objectify($credentials);

      self::$credentials = $credentials;

      // foreach ($credentials as $key => $value) {
      //    self::$key = $value;
      // }

   }

   public function user()
   {
      return self::$credentials;
   }


   // method for turning arrays to objects
   private static function objectify(array $array)
   {
      if (is_array($array) && $array !== []) {
         $obj = new \stdClass();
         foreach ($array as $key => $value) {
            // convert the value into an object if value is also an array
            if (is_array($value)) {
               $value = self::objectify($value);
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