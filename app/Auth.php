<?php
namespace App;
use App\User;
use Framework\Model;
use Framework\Request;
use Framework\Response;
use Framework\Auth as FrameworkAuth;

class Auth
{

   public function __construct()
   {}

   public function auth_session(Request $request, Response $response)
   {
      
      // retrieve credentials
      $username = $request->auth_credentials()->username;
      $role = $request->auth_credentials()->role;
      $privileges = $request->auth_credentials()->privileges;

      // these are credentials coming from the session
      if ( isset($username) && !empty($username) && isset($role) && !empty($role) && isset($privileges) && !empty($privileges) ) {

         $credentials = [
            "username" => $username,
            "role" => $role,
            "privileges" => $privileges
         ];
         // set the user credentials
         new User($credentials);
         return true;
         
      } else {

         $response->remove_all_headers();
         $response->redirect("login", 401);
         return false;
      }
      
   }

   public function auth_session_login(Request $request, Response $response)
   {

      $username = trim($request->body()->username);
      $password = trim($request->body()->password);
      $remember = isset($request->body()->remember) ? true : false ;

      if (!empty($username) && !empty($password)) {

         if ($username == "admin" && $password == "admin") {

            // set user credentials
            // Note: this credentials were used to authenticate the user in the ath_session method above
            $credentials = [
               "username" => $username,
               "role" => "admin",
               "privileges" => "*"
            ];

            // log the user in with the valid credentials
            FrameworkAuth::login($credentials, $remember);

            // set the users details
            new User($credentials);

            return true;

         } else {
            // $response->remove_all_headers();
            // $response->redirect("login");
            // die("Only Admin");
            return false;
         }

      } else {
         // $response->remove_all_headers();
         // $response->redirect("login");
         // die("Username & Password is empty");
         return false;
      }

   }

   public function auth_session_logout(Request $request, Response $response)
   {
      FrameworkAuth::logout();
      // return true;
      $response->redirect("login");
   }

   public function auth_basic(Request $request, Response $response)
   {
      // retrieve the credentials
      $username = $request->auth_credentials()->username;
      $password = $request->auth_credentials()->password;

      /*
         Use the username to retrieve the password from the database.
         Your Model Code Goes Here
      */

      // Default
      // username = admin, password = admin

      // Comment this when you have your password from the database
      $db_username = "admin"; $db_password = "admin";

      // You can replace this with your own authentication code
      if ($username == $db_username && $password == $db_password) {

         // set the user credentials

         return true;
      } else {
         $this->remove_all_headers();
         (new Response())->auth_basic("Initframework");
         return false;
      }

   }

   public function auth_digest(Request $request, Response $response)
   {
      
      $username = $request->auth_credentials()->username;

      // use the username to retrieve the password (A1) from the database.
      // the password should be computed as md5(username:realm:actual-password)

      /*
         Your Model Code Goes Here
      */

      // Default
      // username = admin, password = admin, realm = Initframework
      // the database password is 330902e4da960d4a7fd25c09c41ebb8c
      
      // Comment this when you have your password from the database
      $password = "330902e4da960d4a7fd25c09c41ebb8c";

      // $realm = $credentials->realm;
      $nonce = $credentials->nonce;
      $nc = $credentials->nc;
      $cnonce = $credentials->cnonce;
      $qop = $credentials->qop;
      $uri = $credentials->uri;
      $request_response = $credentials->response;

      $A1 = $password;
      $A2 = md5($request->method() . ":$uri");
      $valid_response = md5("$A1:$nonce:$nc:$cnonce:$qop:$A2");

      if ($request_response == $valid_response) {
         return true;
      } else {
         $response->remove_all_headers();
         $response->auth_digest("Initframework");
         return false;
      }
      
   }

   public function create_digest_password(string $username, string $password)
   {
      // md5(username:realm:password)
      return md5($username . ":" . "Initframework" . ":" . $password);
   }

   public function auth_oauth()
   {

   }

   public function auth_oauth2()
   {

   }

   public function auth_jwt()
   {

   }

}
