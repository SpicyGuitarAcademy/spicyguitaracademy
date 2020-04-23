<?php
namespace App;
use App\Http;
// use Initframework\TestAutoloader;
// use ESAPI;

// Include autoload for composer packages
include_once '../vendor/autoload.php';

// Setup Configurations
include_once '../config.php';

class App
{
   public function __construct()
   {
      
      $http = new Http();

      // // set the route for the application
      $http->get('/','HomeController@new');

      $http->get('/users','HomeController@bar');

      $http->put('/test-put-from-html', function(Request $req, Response $res) {
         $res->send($req->uri() . " - " . $req->body()->putvar);
      });

      $http->put('/users','HomeController@putty');

      // // application can handle functions right here
      $http->put('/users/{id:x}', function(Request $req, Response $res) {
         $res->send($req->uri(). " - " .$req->params()->id. " - " .$req->query()->range);
      });

      // http::middleware('web')
      // ->get('/route','HomeController@index');

      $http->end();
   }

}

// Start Application 😉
new App();
