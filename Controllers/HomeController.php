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
      // View::show('welcome.html');

      foreach ($_SERVER as $key => $value) {
         echo "$key ===========> $value <br>";
      }

      exit;
   }

   public function users(array $data)
   {
      // View::show('users.html')
      // ->with([
      //    "users"=>json_encode(['John Doe','Jane Doe','David Doe','Janet Doe'])
      // ]);

      // gettype();

      $users = json_decode(json_encode(['John Doe','Jane Doe','David Doe','Janet Doe']));

      // die(gettype($users));

      // foreach ($variable as $key => $value) {
      //    # code...
      // }

      View::with([
         "users"=>json_encode(['John Doe','Jane Doe','David Doe','Janet Doe'])
      ])->show('users.html');
   }

}

?>