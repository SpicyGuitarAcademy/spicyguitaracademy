<?php
namespace App;
use Framework\Http\Http;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Handler\IException;
use App\Services\Auth;
use App\Services\Upload;
use App\Services\Validate;
use App\Services\Sanitize;
use Models\UserModel;

// Include autoload for composer packages
include_once '../vendor/autoload.php';
// Setup Configurations
include_once '../app/config.php';

// Start Application 😉
$http = new Http();

// Now let's Route 🚀🚀🚀

$http->auth('web')->get("/", function ($req, Response $res) {
   $res->send($res->render('home.html', [
      "app" => APPLICATION
   ]), 200);
});

$http->auth('web')->get('/new-user', function ($req, Response $res) {
   $res->send(
      $res->render('add-user.html'), 200);
});

$http->auth('web')->csrf()->post('/add-user', function($req, $res) {
   $username = $req->body()->username;
   $v = new Validate();
   $v->letters('username', $username)->max(20);
   $errors = $v->errors();

   if (!$errors) {
      $username = (new Sanitize)->string($username);
      $mdl = new UserModel();
      if ($mdl->addUser($username) == true) {
         $users = $mdl->getUsers();

         // if ($users) {
            $res->send(
            $res->render('users.html', [
               "users" => json_encode($users)
            ]), 200);
         // } else {

         // }
         
      } else {
         die("User is not added");
      }
   }

});

$http->get('/user/{username:a}', function (Request $req, $res) {
   if ($req->params_exists()) {
      $params = $req->params();
      $username = $params->username;
      $username = (new Sanitize)->string($username);
      $mdl = new UserModel();

      $user = $mdl->getUser($username);

      echo $user != [] ? json_encode($user) : "Not a user";
      // echo json_encode($user) ?? "Not a user";
      die;

   }
   
});

$http->ip_allow('::1')->get('/test-ip', function ($req, $res) {
   $res->send('Allowed IP ' . $req->ip());
});

$http->csrf()->post('/csrf', function ($req, $res) {
   $res->send('Token Sent ' . $req->csrftoken());
});

$http->post('/formupload', function ($req, $res) {
   // echo "File E : " . json_encode($req->files()->filee);
   // echo "File O : " . json_encode($req->files()->fileo);

   // $array = ["a"=>"A", "b"=>"B"];
   
   $up = new Upload(); $files = $req->files();
   $up->image("File E", $files->filee)->upload('tutorials/', \Framework\Cipher\Encrypt::hash());
   // $up->image("File O", $files->fileo)->upload();
   echo json_encode($up->errors());
   echo $up->uri('File E');
   // $up->uri('tutorials/File O'));
   die;
});

$http->get('/test-zip', function ($req,$res) {
   \Framework\File\Zip::zip('imgs/', 'archive/', 'archive1.zip');
   die('Zipped whole storage!');
});

$http->get('/test-mail', function ($req,Response $res) {
   if (\Framework\Mail\Mail::asText('Hola Dom!')->send("ebukaodini@gmail.com:Ebuka Odini", "lerryjay45@gmail.com:Dominic Olajire", "Testing InitFramework Mail Service", "ebukaodini@gmail.com:Ebuka-Reply"))
      $res->send("Mail Sent");
   else
      $res->send("Mail not Sent");
});

$http->get('/login', function ($req, Response $res) {
   $res->send($res->render('login.html'));
});

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

// $http->auth("None")->get('/auth-none', function ( Request $req, Response $res ) {
//    $res->send("No Auth");
// });

// $http->auth("Basic")->get('/auth-basic', function ( Request $req, Response $res ) {
//    $res->send("Basic Auth");
// });

// $http->auth("Digest")->get('/auth-digest', function ( Request $req, Response $res ) {
//    $res->send("Digest Auth");
// });

$http->auth('Session')->get('/auth-session', function ( Request $req, Response $res) {
   $res->send("Session Auth");
});

$http->post('/auth', function (Request $req, Response $res) {
   
   if ((new Auth())->session_login($req, $res) == true) {

      $res->redirect("dashboard");

   } else {
      $res->send($res->render('login.html'), 400);
   }
   
});

$http->auth('web')->guard('staff','admin')->get('/dashboard', function (Request $req, Response $res) {
   $res->send($res->render('dashboard.html'));
});

$http->post('/logout', function ($req, $res) {
   (new Auth())->session_logout($req, $res);
});

$http->end();
// Initialize Application 😉
new App();
