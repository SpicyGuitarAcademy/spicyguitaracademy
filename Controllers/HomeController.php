<?php
namespace Controllers;
use App\Controller;
use App\Route;
use App\View;

use Framework\Request;
use Framework\Response;
use Framework\Http;

class HomeController
{
   public function index(Request $req, Response $res)
   {
      $res->send(View::internal_render('welcome.html'));
   }

   public function users(Request $req, Response $res)
   {
      $path = $req->path();
      $uri = $req->uri();
      $host = $req->host();
      $scheme = $req->scheme();
      $body = $req->body();
      $files = $req->files();
      $ip = $req->ip();
      $query = $req->query();
      $method = $req->method();

      View::with([
         "request"=>json_encode([
            "path"=>$path,
            "uri"=>$uri,
            "host"=>$host,
            "scheme"=>$scheme,
            "body"=>json_encode($body),
            "files"=>json_encode($files),
            "ip"=>$ip,
            "query"=>json_encode($query),
            "method"=>$method,
         ])
      ])->show('users.html');
   }

}

?>