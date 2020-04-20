<?php
use Core\Route;

class App
{
   public function __construct()
   {

      // Include the Autoload file
      include_once '../autoload.php';

      // Setup Configurations
      include_once '../config.php';

      // Set the Routes
      $this->setWebRoutes();
      $this->setApiRoutes();

      // Start Routing
      (new Route)->Init();

   }

   protected function setWebRoutes()
   {

      /// Web Route Endpoints
      ///
      /// Your route endpoints are the entry point to your applications
      /// 

      Route::get('/','home','HomeController@index');
      Route::get('/users','users','HomeController@users');
   }

   protected function setApiRoutes()
   {

   }
}

// Initialize Application 😉
new App();

?>