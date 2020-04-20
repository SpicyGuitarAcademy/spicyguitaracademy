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
      // (new Route)->Init();

      new Request();

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

// // Closures
// // working
// $runA = (function() {
//    echo "House A\n";
// });
// $runA();

// // working
// $runB = (function() {
//    return "House B\n";
// });
// echo $runB();

// // Not Working
// $arr = ['boy','is','white'];
// echo json_encode( function () {
//    return ['boy','is','white'];
// });

// // Working
// $runC = (function () {
//    return json_encode(['boy','is','white']);
// });
// echo $runC();
// // $run(['she','fine']);

// // Working
// $runD = function () {
//    return ['boy','is','white'];
// };
// echo json_encode($runD());

// // Working
// $runE = function ($arg) {
//    return json_encode($arg);
// };
// echo $runE(['she','fine']);

?>