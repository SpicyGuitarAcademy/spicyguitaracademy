<?php
namespace App;
use App\FrameworkExceptionHandler;

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
      $valid_response_codes = [
         // Information response codes
         100, 101, 103, 
         // Success response codes
         200, 201, 202, 203, 204, 205, 206, 
         // Redirect response codes
         300, 301, 302, 303, 304, 306, 307, 308, 
         // Client response codes
         400, 401, 402, 403, 404, 405, 406, 407, 408, 
         409, 410, 411, 412, 413, 414, 415, 416, 417, 
         // Server response codes
         500, 501, 502, 503, 504, 505, 511
      ];

      try {
         if (in_array($resp_code, $valid_response_codes)) {
            // set the response code
            \http_response_code($resp_code);

            // send the response message
            exit($response_message);
         } else {
            throw new FrameworkException($this);
         }
      } catch (FrameworkException $ex) {
         $ex->log_exception(); $ex->email_developer(); $ex->show_developer();
      }
      
   }

   // handle the remaining response codes

}