<?php
namespace Framework;
use App\Auth as AppAuth;
use OAuth;
use OAuthProvider;
use OAuthException;

class Auth
{

   private $app_auth;

   public function __construct() {
      $this->app_auth = new AppAuth();
   }

   // this method would only be called when the request uri matches the route
   // returns true if success, and exits if failure
   public function check(Request $request, Response $response, string $route_auth_type, bool $app_handles_failure)
   {

      // LOGIC
      // this route expects your request to come with the basic auth type and nothing else
      // if your request came with a diff auth type => you would be requested to authenticate with the right auth type.
      // if your request came in with the basic auth type, validate the credentials
      // if the credentials are valid => return true
      // else => (request re-authentication| send bad request) and return false

      switch ($route_auth_type) {
         
         // the default for web applications on the browser
         case 'Session':
            
            if ($request->auth_type() != "Session") {

               // request a Session Authentication from the client via login form
               $response->remove_all_headers();
               $response->redirect('login');

            } else {

               return $this->app_auth->auth_session($request->auth_credentials());

            }

         break;

         // set of authentication types for APIs
         case 'Basic':

            if ($request->auth_type() != "Basic") {

               // request a Basic Authentication from the client
               $response->auth_basic("Initframework");

            } else {

               // validate the username and password is correct.
               if ($this->app_auth->auth_basic($request->auth_credentials()->username, $request->auth_credentials()->password) == true) {
                  // success
                  return true;
               }
               // request a valid Basic Authentication from the client
               // or send bad request
               else {
                  $response->auth_basic("Initframework");
               }

            }
            
         break;

         case 'Digest':

            if ($request->auth_type() != "Digest") {
               
               // request a Digest Authentication from the client
               $response->auth_digest("Initframework");

            } else {

               // validate the username and password is correct.
               if ( $this->app_auth->auth_digest($request, $request->auth_credentials()) ) {
                  // success
                  return true;
               }
               // request a valid Basic Authentication from the client
               // or send bad request
               else {
                  $response->auth_digest("Initframework");
               }

            }

         break;

         case 'OAuth':
            return true;
         break;
               
         case 'OAuth2':
            return true ;
         break;
         
         // also valid for applications on the web browser
         case 'JWT':
            return true;
         break;

         // default when no auth type is indicated
         case 'None':
            return true;
         break;

         default:
            // None
            return true;
         break;

      }

   }

   public function session_login(object $credentials)
   {

      $credentials = [
         // Session is accessed by $_SESSION['AUTH']['USERNAME'], $_SESSION['AUTH']['PASSWORD'], $_SESSION['AUTH']['ROLE'], $_SESSION['AUTH']['PRIVILEGES']
         "USERNAME" => $req->body()->username,
         "PASSWORD" => $req->body()->username,
         "ROLE" => $req->body()->username,
         "PRIVILEGES" => $req->body()->username,
      ];
      $_SESSION['AUTH'] = $credentials;

   }

}