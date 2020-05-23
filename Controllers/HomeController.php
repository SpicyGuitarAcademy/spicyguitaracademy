<?php
namespace Controllers;
use Framework\Http\Http;
use Framework\Http\Request;
use Framework\Http\Response;
use App\Services\Auth;

class HomeController
{
   public function index(Request $req, Response $res)
   {
      $res->send($res->render("welcome.html", ['app'=>'Good']));
   }

   public function users(Request $req, Response $res)
   {
<<<<<<< HEAD
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
=======
      View::show('welcome.html');

//       foreach ($_SERVER as $key => $value) {
//          echo "$key ===========> $value <br>";
//       }

      exit;
   }

   public function users(array $data)
   {
      
//       foreach ($_SERVER as $key => $value) {
//          echo "$key ===========> $value <br>";
//       }

//       exit;

      View::with([
         "users"=>json_encode(['John Doe','Jane Doe','David Doe','Janet Doe'])
>>>>>>> d5a7428bcded5db5576242fa8e07dfb8a1e80244
      ])->show('users.html');
   }

}

?>
