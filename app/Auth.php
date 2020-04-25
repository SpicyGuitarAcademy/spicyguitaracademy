<?php
namespace App;
use Framework\Model;
use Framework\Request;
use Framework\Response;

class Auth
{

   public function __construct()
   {

   }

   public function auth_session()
   {

   }

   public function auth_basic(string $username ,string $password)
   {
      if ($username == "ebuka" && $password == "odini") {
         return true;
      } else {
         (new Response())->auth_basic("Initframework");
         return false;
      }
   }

   public function encrypt_password_digest(string $username, string $password)
   {
      // md5(username:realm:password)
      return md5($username . ":" . "Initframework" . ":" . $password);
   }

   public function auth_digest(Request $request, object $credentials)
   {
      
      $username = $credentials->username;

      // use the username to retrieve the password (A1) from the database.
      // the password should be computed as md5(username:realm:actual-password)

      /*
         Your Model Code Goes Here
      */

      // Default
      // using username = admin, password = admin, realm = Initframework
      // the database password is 330902e4da960d4a7fd25c09c41ebb8c
      
      // Comment this when you have your password from the database
      $password = "330902e4da960d4a7fd25c09c41ebb8c";

      // $realm = $credentials->realm;
      $nonce = $credentials->nonce;
      $nc = $credentials->nc;
      $cnonce = $credentials->cnonce;
      $qop = $credentials->qop;
      $uri = $credentials->uri;
      $response = $credentials->response;

      $A1 = $password;
      $A2 = md5($request->method() . ":$uri");
      $request_response = md5("$A1:$nonce:$nc:$cnonce:$qop:$A2");

      if ($response == $request_response) {
         return true;
      } else {
         (new Response())->auth_digest("Initframework");
         return false;
      }
      
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
