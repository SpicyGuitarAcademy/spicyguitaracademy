<?php
namespace Controllers;
use App\Controller;
use App\Route;
use App\View;

use App\Request;
use App\Response;
use App\Http;

class HomeController// extends Controller
{
   
   public function index(array $data)
   {
      View::show('welcome.html');
   }

   public function users(array $data)
   {

      $Request = new Request();

      // $Request = new 
      // $query = (new Request())->files_exists() ? (new Request())->query() : "Files not sent." ;

      $path = $Request->path();
      $uri = $Request->uri();
      $host = $Request->host();
      $scheme = $Request->scheme();
      $body = $Request->body();
      $files = $Request->files();
      $ip = $Request->ip();
      $query = $Request->query();
      $method = $Request->method();

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

   public function new(Request $req, Response $res)
   {

      View::show('welcome.html');

      // $res::write();
      // $res::send();

   }

   public function bar(Request $req, Response $res)
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

   public function putty(Request $req, Response $res)
   {
      // View::show('');
      // die("Putty to the World!");

      // Http::put('/hla','');

      $res->send(
         View::render('welcome.html'), 300
      );
   }

}

?>