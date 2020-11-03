<?php
namespace App\Services;

class User
{

   public static $auth = false;
   public static $email = '';
   public static $username = '';
   public static $role = '';
   public static $group = '';
   public static $token = '';
   public static $fullname = null;
   public static $avatar = null;

   public static function user()
   {
      return json_encode([
         "auth"=>self::$auth,
         "email"=>self::$email,
         "username"=>self::$username,
         "role"=>self::$role,
         "group"=>self::$group,
         "token"=>self::$token,
         "fullname"=>self::$fullname,
         "avatar"=>self::$avatar
      ]);
   }

}