<?php
namespace Controllers;
use Core\Controller;
use Core\Route;
use Core\View;

class HomeController extends Controller
{
   public function __construct()
   {
      parent::__construct();
   }

   public function index(array $data)
   {
      View::show('welcome.html');
   }

   public function users(array $data)
   {
      View::show('users.html')
      ->with([
         "users"=>json_encode(['John Doe','Jane Doe','David Doe','Janet Doe'])
      ]);
   }

}

?>