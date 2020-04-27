<?php
namespace App;
use Framework\Http;
use Framework\Request;
use Framework\Response;
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

      // set the route for the application

      // set route for controller methods
      // $http->get('/','HomeController@index');
      // $http->get('/users','HomeController@users');

      // set route that are handled here
      // test put from the html
      // $http->put('/test-put-from-html', function ( Request $req, Response $res ) {
      //    $res->send($req->body()->putvar);
      // });
      // // test put from an api client
      // $http->put('/users','HomeController@users');

      // // test the route parameter datatype
      // $http->put('/users/{id:d}', function ( Request $req, Response $res ) {
      //    $res->send($req->uri(). " - " .$req->params()->id);
      // });

      $http->auth("None")->get('/auth-none', function ( Request $req, Response $res ) {
         $res->send("No Auth");
      });

      $http->auth("Basic")->get('/auth-basic', function ( Request $req, Response $res ) {
         $res->send("Basic Auth");
      });

      $http->auth("Digest")->get('/auth-digest', function ( Request $req, Response $res ) {
         $res->send("Digest Auth");
      });

      $http->auth('Session')->get('/auth-session', function ( Request $req, Response $res) {
         $res->send("Session Auth");
      });

      $http->get('/login', function ($req, $res) {
         $res->send(View::render('login.html'));
      });

      $http->post('/auth', function (Request $req, Response $res) {
         
         // as best practice, regenerate session id
         \session_regenerate_id();

         $credentials = [
            // Session is accessed by $_SESSION['AUTH']['USERNAME'], $_SESSION['AUTH']['PASSWORD'], $_SESSION['AUTH']['ROLE'], $_SESSION['AUTH']['PRIVILEGES']
            "USERNAME" => $req->body()->username,
            "PASSWORD" => $req->body()->username,
            "ROLE" => $req->body()->username,
            "PRIVILEGES" => $req->body()->username,
         ];
         $_SESSION['AUTH'] = $credentials;

         $res->redirect("dashboard");
      });

      $http->auth('Session')->get('/dashboard', function ($req, $res) {
         $res->send("You're Logged In.");
      });

      $http->end();
   }

}

// foreach ($_SERVER as $key => $value) {
//    echo sprintf("%s =======> %s <br><br>", $key, $value);
// }

// echo uniqid() . "<br>";
// echo uniqid("", true) . "<br>";
// echo uniqid("Init") . "<br>";
// echo uniqid("Init", true) . "<br>";
// echo json_encode($_SESSION);

// echo isset($_SESSION['AUTH']) ? 'Yes' : 'No';
// die;

// Start Application 😉
new App();
