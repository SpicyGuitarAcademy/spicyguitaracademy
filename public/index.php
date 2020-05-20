<?php
namespace App;
use Framework\Http\Http;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Handler\IException;
use App\Services\Auth;
use App\Services\User;

// Include autoload for composer packages
include_once '../vendor/autoload.php';
// Setup Configurations
include_once '../app/config.php';

// Start Application 😉
$http = new Http();

// Now let's Route 🚀🚀🚀

$http->get("/", function ($req, Response $res) {
   $res->send($res->render('home.html', [
      "app" => APPLICATION,
      "owner"=>\Framework\File\File::owner()
   ]), 200);
});

$http->ip_allow('::1')->get('/test-ip', function ($req, $res) {
   $res->send('Allowed IP ' . $req->ip());
});

$http->csrf()->post('/csrf', function ($req, $res) {
   $res->send('Token Sent ' . $req->csrftoken());
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
   $user = User::user();
   $res->send($res->render('dashboard.html'));
   // $res->send("Welcome " . ucfirst($user->username) . "<br>You have " . $user->privileges . " privileges." );
});

$http->post('/logout', function ($req, $res) {
   (new Auth())->session_logout($req, $res);
});

$http->end();