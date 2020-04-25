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

               // generate authentication parameters
               // realm, a revelation to the client as to which username and password to provide
               $realm = 'Authorized Users of ' . $request->host() ;

               // set the response header
               $response->add_header('WWW-Authenticate', sprintf('Basic realm="%s"', $realm));
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
               // nonce, to make each request unique
               $nonce = md5(uniqid("", true));
               // opaque, must be returned by the client unaltered
               $opaque = md5(uniqid());
               // realm, a revelation to the client as to which username and password to provide
               $realm = 'Authorized Users of ' . $request->host() ;

               // set the response header
               $response->remove_all_headers();
               $response->add_header('WWW-Authenticate', sprintf('Digest realm="%s", nonce="%s", opaque="%s"', $realm, $nonce, $opaque));
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