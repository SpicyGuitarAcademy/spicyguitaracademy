<?php
namespace App;
use App\TemplateEngine;
use App\HttpResponse;
use App\Error;

class View
{
   private static $fileRoot = "../public/views/";

   public static function show(string $filename)
   {
      $view = "";
      $file = self::$fileRoot . $filename;

      // if file exists
      if (file_exists($file)) {
         // Convert PHP Snippets
         $view = (new TemplateEngine)->convertToHTML($file);
      } else {
         $stack = \debug_backtrace();
         $errfile = $stack[0]['file'];
         $errline = $stack[0]['line'];
         Error::internalError("File <i><b>'$file'</b></i> doesn't exist in <b>$errfile</b> on line <b>$errline</b><!--");
      }
      
      // call the Http Response to return the view to the user
      (new HttpResponse)->respondHTML($view);
   }

   public static function with(array $data = [])
   {
      foreach ($data as $key => $value) {
         $_SESSION["INIT_VIEW"][$key] = $value;
      }

      return new View;
   }

   public static function log(array $data = [])
   {
      foreach ($data as $key => $value) {
         $_SESSION["INIT_VIEW_LOG"][$key] = $value;
      }
   }

   public static function asJson($data)
   {
      // call the Http Response to return the view to the user
      (new HttpResponse)->respondJSON($data);
   }

}

?>