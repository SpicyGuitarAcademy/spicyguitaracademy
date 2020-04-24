<?php
namespace Framework;
use OAuth;
use OAuthProvider;
use OAuthException;

class Auth
{

   public function __construct() {

   }

   // this method would only be called when the request uri matches the route
   public function check(Request $request, Response $response, string $auth_type)
   {

      switch ($auth_type) {
         
         // the default for web applications on the browser
         case 'Session':
            return true;
            break;

         // set of authentication types for APIs
         case 'Basic':

            if ($request->auth_type() != "Basic") {
               $response->add_header('WWW-Authenticate', 'Basic realm="Authorized Users"');
               return false;
            } else {
               // validate the username and password is correct.
               // assume success
               return true;
            }
            
            break;

         case 'Digest':

            if ($request->auth_type() != "Digest") {

               // generate authentication parameters
               

               $response->add_header('WWW-Authenticate', 'Digest realm="Authorized Users"');
               return false;
            } else {
               // validate the username and password is correct.
               // assume success
               return true;
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