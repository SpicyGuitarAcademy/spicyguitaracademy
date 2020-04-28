<?php
namespace Controllers;
use App\Controller;
use App\Route;
use App\View;

class HomeController extends Controller
{
   public function __construct()
   {
      parent::__construct();
   }

   public function index(array $data)
   {
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
      ])->show('users.html');
   }

}

?>
