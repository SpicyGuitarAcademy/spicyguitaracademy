<?php
namespace App;
use App\Route;

use App\Request;
use Initframework\TestAutoloader;

class App
{
   public function __construct()
   {

      // Include the Autoload files
      include_once '../autoload.php';
      // include_once '../vendor/autoload.php';

      // Setup Configurations
      include_once '../config.php';

      // Set the Routes
      $this->setWebRoutes();
      $this->setApiRoutes();

      // Start Routing
      (new Route)->Init();

      // new HttpRequest();

   }

   protected function setWebRoutes()
   {
      // use App\Route;
      /*
         ----------------------------------------------------------------
         Web Routes
         ----------------------------------------------------------------
         
         These are the entry points into your Web Applications.
      */ 

      Route::get('/','home','HomeController@index');
      Route::get('/users','users','HomeController@users');
   }

   protected function setApiRoutes()
   {

   }
}

// Initialize Application 😉
new App();
