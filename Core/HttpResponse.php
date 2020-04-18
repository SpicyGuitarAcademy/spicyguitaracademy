<?php
namespace Core;
use Core\Model;

class HttpResponse
{
   public function respondHTML($data)
   {
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: text/html; charset=UTF-8");

      eval($data);
      exit;
   }

   public function respondJSON($data)
   {
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=UTF-8");

      echo json_encode($data);
      exit;
   }

}

?>