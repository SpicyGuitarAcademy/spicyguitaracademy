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
   public function check(Request $request, Response $response, string $route_auth_type)
   {

      switch ($route_auth_type) {
         
         // the default for web applications on the browser
         case 'Session':
            return true;
         break;

         // set of authentication types for APIs
         case 'Basic':

            // this route requires the auth type basic
            // then you submitted a request with a uri that matches this route.

            // this route expects your request to come with the basic auth type and nothing else

            // so, there two things involved,
               // if your request came with a diff auth type => you would be requested to authenticate with the right auth type.

               // if your request came in with the basic auth type, validate the credentials
                  // if the credentials are valid => return true
                  // else => request re-authentication and return false


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

}