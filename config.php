<?php


CONST Config = [

   // APPLICATION
   "APP_NAME" => "Initframework",
   "APP_URL" => "http://localhost/testframework",
   // "APP_URL" => "http://test.initframework.com",

   // DEVELOPMENT
   "APP_DEBUG" => false,
   "APP_MAINTAINANCE" => true,

   // set the ip addresses that can access the application while on maintenance
   "APP_MAINTENANCE_WHITELIST_IP" => [
      "::1"
   ],
   
   // DATABASE CONFIGURATION
   "DB_DRIVER" => "mysql",
   "DB_HOST" => "localhost",
   "DB_PORT" => "3306",
   "DB_DATABASE" => "testframework_db",
   "DB_USERNAME" => "root",
   "DB_PASSWORD" => "",

   // MAIL CONFIGURATION
   "MAIL_DRIVER" => "smtp",
   "MAIL_HOST" => "smtp.mailtrap.io",
   "MAIL_PORT" => "2525",
   "MAIL_USERNAME" => "null",
   "MAIL_PASSWORD" => "null",
   "MAIL_ENCRYPTION" => "null",
   
   // AUTHENTICATION

   // Basic Auth
   "AUTH_BASIC_REALM" => "Initframework",

   // Digest Auth
   "AUTH_DIGEST_REALM" => "Initframework",

   // Session Auth
   "AUTH_SESSION_NAME" => "Initframework",
   "AUTH_SESSION_DOMAIN"=>"http://localhost/testframework",

   // $lifetime = time() + 300;
   // $path = '/';
   // $domain = "http://localhost/initframeworktests/";
   // $secure = true;
   // $httponly = true;
   // session_set_cookie_params([
   //    'lifetime' => $lifetime,
   //    'path' => $path,
   //    'domain' => $domain,
   //    'secure' => $secure,
   //    'httponly' => $httponly,
   //    'samesite' => 'Lax'
   // ]);
   // session_name("HB");
   // session_start();

   // Session is accessed by $_SESSION['AUTH']['USERNAME'], $_SESSION['AUTH']['PASSWORD'], $_SESSION['AUTH']['ROLE'], $_SESSION['AUTH']['PRIVILEGES']

   "AUTH_TIMEOUT" => 1, # 1 = 1 hour

   "SERVER_ADMIN" => "webmaster@test.initframework.com", // postmaster@localhost

];

// DISPLAY ERROR
($_SERVER['HTTP_HOST'] == 'localhost') ? ini_set('display_errors', 1) : ini_set('display_errors', 0);

?>
