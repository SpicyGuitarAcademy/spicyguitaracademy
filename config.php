<?php


CONST Config = [
   // APP
   "APP" => "TESTFRAMEWORK",
//    "DOMAIN" => "/testframework",
   "DOMAIN" => "http://test.initframework.com",
   
   // DATABASE CONFIGURATION
   "DB_CONNECTION" => "mysql",
   "DB_HOST" => "localhost",
   "DB_PORT" => "3306",
   "DB_DATABASE" => "",
   "DB_USERNAME" => "",
   "DB_PASSWORD" => "",
   
   // AUTH
   "AUTH_TIMEOUT" => 1, # 1 = 1 hour
];

// DISPLAY ERROR
($_SERVER['HTTP_HOST'] == 'localhost') ? ini_set('display_errors', 1) : ini_set('display_errors', 0);

?>
