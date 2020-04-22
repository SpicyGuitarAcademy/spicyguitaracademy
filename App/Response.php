<?php
namespace App;

class Response
{

   public function __construct()
   {
      
   }

   private function default_response_header()
   {

   }

   public static function html()
   {
      
   }

   public static function xml()
   {
      
   }

   public static function json()
   {
      
   }

   public static function send(int $resp_code)
   {

   }

   public function not_found()
   {
      \http_response_code(404);
   }

   // continue with the remaining response codes

}