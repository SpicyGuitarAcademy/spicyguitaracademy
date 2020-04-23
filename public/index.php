<?php
namespace App;
use App\Http;

// use Initframework\TestAutoloader;
// use ESAPI;

class App
{
   public function __construct()
   {
      // Include autoload for composer packages
      include_once '../vendor/autoload.php';

      // Setup Configurations
      include_once '../config.php';

      $http = new Http();

      // // set the route for the application
      $http->get('/','HomeController@new');

      $http->get('/users','HomeController@bar');

      $http->put('/users','HomeController@putty');

      // // application can handle functions right here
      $http->put('/users/{id:d}', function(Request $req, Response $res) {
         die ($req->uri(). " - " .$req->params()->id. " - " .$req->query()->range);
      });

      // http::middleware('web')
      // ->get('/route','HomeController@index');

      $http->end();
   }

   protected function web()
   {
      /*
         ----------------------------------------------------------------
         Web Routes
         ----------------------------------------------------------------
         
         These are the entry points into your Web Applications.

         ----------------------------------------------------------------
      */ 

      Route::get('/','home','HomeController@index');
      Route::get('/users','users','HomeController@users');
   }

   protected function api()
   {
      /*
         ----------------------------------------------------------------
         Web Routes
         ----------------------------------------------------------------
         
         These are the entry points into your APIs.

         ----------------------------------------------------------------
      */
      Http::get('/route', function() {

      });

      Routing::auth()->get('/route','HomeController::index()');

   }
}

// Initialize Application 😉
new App();
