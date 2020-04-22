<?php
namespace App;
use App\ExceptionHandler;

class Response
{

   private $response_content_type;

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

   public function send(string $response_message = "", int $resp_code = 200)
   {
      $valid_response_codes = [200, 404, 300];
      if (in_array($resp_code, $valid_response_codes)) {
         // set the response code
         \http_response_code($resp_code);

         // send the response message
         exit($response_message);
      } else {
         \http_response_code(404);
         throw new ExceptionHandler();
         // ("Error Processing Request", 1);
      }
   }

   public function not_found(string $response_message = "")
   {
      \http_response_code(403);
      exit($response_message);
   }

   // continue with the remaining response codes

}