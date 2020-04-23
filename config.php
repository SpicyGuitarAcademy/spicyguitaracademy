<?php


CONST Config = [
   // APPLICATION
   "APP" => "TESTFRAMEWORK",
   // "DOMAIN" => "/testframework",
   // "DOMAIN" => "http://test.initframework.com",

   "APP_NAME" => "Initframework",
   "APP_URL" => "http://localhost/testframework",
   "APP_DEBUG" => true,
   "APP_MAINTAINANCE" => true,

   // set the ip addresses that can access the application while on maintenance
   "APP_MAINTENANCE_WHITELIST_IP" => [
      "::1"
   ],
   
   // DATABASE CONFIGURATION
   "DB_DRIVER" => "mysql",
   "DB_HOST" => "localhost",
   "DB_PORT" => "3306",
   "DB_DATABASE" => "",
   "DB_USERNAME" => "",
   "DB_PASSWORD" => "",

   "MAIL_DRIVER" => "smtp",
   "MAIL_HOST" => "smtp.mailtrap.io",
   "MAIL_PORT" => "2525",
   "MAIL_USERNAME" => "null",
   "MAIL_PASSWORD" => "null",
   "MAIL_ENCRYPTION" => "null",
   
   // AUTH
   "AUTH_TIMEOUT" => 1, # 1 = 1 hour

   "SERVER_ADMIN" => "webmaster@test.initframework.com", // postmaster@localhost

];

// DISPLAY ERROR
($_SERVER['HTTP_HOST'] == 'localhost') ? ini_set('display_errors', 1) : ini_set('display_errors', 0);

?>
