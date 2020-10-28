<?php
namespace App\Services;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Database\Model;
use Framework\Handler\IException;
use Framework\Cipher\Encrypt;
use Framework\Cipher\AES;
use Framework\Cipher\JWT;
use App\Services\User;

class Auth
{

   // web login
   public static function session_login(Request $request, Response $response)
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
            ];

            // consider remember
            if ($remember) {
               // extend the session lifetime
               $lifetime = (REMEMBER_ME_LIFETIME * 60);
               session_set_cookie_params($lifetime);
            }

            // add auth to session
            $_SESSION['AUTH'] = $credentials;
            // as best practice, regenerate session id
            \session_regenerate_id();

            // set the users details
            User::$auth = true;
            User::$username = $credentials['username'];
            User::$role = $credentials['role'];

            return true;

         } else {
            return false;
         }

      } else {
         // die("Username & Password is empty");
         return false;
      }

   }

   // web logout
   public static function session_logout(Request $request, Response $response)
   {
      \session_unset();
      \session_destroy();
      if (User::$group == 'admin') {
         $response->route("/admin/login");
      } elseif (User::$group == 'user') {
         $response->route("/login");
      }
   }

   // jwt login
   public static function jwt_login(Request $request, Response $response)
   {
      
      $username = trim($request->body()->username);
      $password = trim($request->body()->password);
      $remember = isset($request->body()->remember) ? true : false ;

      if (!empty($username) && !empty($password)) {

         if ($username == "admin" && $password == "admin") {
      
            /** 
             * Create some payload data with user data we would normally retrieve from a
               * database with users credentials. Then when the client sends back the token,
               * this payload data is available for us to use to retrieve other data 
               * if necessary.
               */
            // $credentials = [
            //    "username" => $username,
            //    "role" => "admin",
            // ];
            // $userId = 'USER123456';

            /**
             * Uncomment the following line and add an appropriate date to enable the 
               * "not before" feature.
               */
            // $nbf = strtotime('2021-01-01 00:00:01');

            /**
             * Uncomment the following line and add an appropriate date and time to enable the 
               * "expire" feature.
               */
            if ($remember) {
               $lifetime = REMEMBER_ME_LIFETIME * 60;
            } else {
               $lifetime = 60;
            }
            $exp = strtotime("$lifetime minutes");
            
            // create a token
            $payloadArray = array();
            $payloadArray['username'] = $username;
            $payloadArray['role'] = "admin";
            if (isset($nbf)) {$payloadArray['nbf'] = $nbf;}
            if (isset($exp)) {$payloadArray['exp'] = $exp;}
            $token = JWT::encode($payloadArray, SECRET_KEY);

            // set the users details
            User::$auth = true;
            User::$username = $username;
            User::$role = "admin";
            User::$token = $token;

            return true;

         } else {
            return false;
         }

      } else {
         // die("Username & Password is empty");
         return false;
      }
   }
   
   // jwt logout
   public static function jwt_logout(Request $request, Response $response)
   {

   }

   // Middleware Auths

   // Session Auth
   public static function session(Request $request, Response $response)
   {
      // retrieve credentials
      $email = $request->auth_credentials()->email;
      $role = $request->auth_credentials()->role;
      $fullname = $request->auth_credentials()->fullname ?? '';

      // these are credentials coming from the session
      if ( isset($email) && !empty($email) && isset($role) && !empty($role) ) {
         // set the users details
         User::$auth = true;
         User::$email = $email;
         User::$role = $role;
         User::$fullname = $fullname;
      } else {
         $response->remove_all_headers();
         if (User::$group == 'admin') {
            $response->route("/admin/login?redirect=" . $request->uri(), 401);
         } elseif (User::$group == 'user') {
            $response->route("/login?redirect=" . $request->uri(), 401);
         }
      }
      
   }

   // Basic Auth
   public static function basic(Request $request, Response $response)
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

      } else {
         $response->remove_all_headers();
         $response->auth_basic(BASIC_REALM);
      }

   }

   // Digest Auth
   public static function digest(Request $request, Response $response)
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
         $response->auth_digest(DIGEST_REALM);
         return false;
      }
      
   }

   // JWT Auth
   public static function jwt(Request $request, Response $response)
   {
      try {
         // retrieve the JSON Web Token
         $token = $request->auth_credentials()->JWToken ?? null;

         $payload = JWT::decode($token, SECRET_KEY, array('HS256'));
         User::$auth = true;
         User::$email = $payload->email ?? '';
         User::$username = $payload->username ?? '';
         User::$fullname = $payload->fullname ?? '';
         User::$role = $payload->role;
      }
      catch(IException $ex) {
         $ex->handle('api');
         $response->send("Unauthorized Accesss", 401);
      }
   }

   // OAuth Auth
   public static function oauth()
   { }

   // OAuth2 Auth
   public static function oauth2()
   { }

   // Middleware Handlers
   // Guard
   public static function guard(Request $request, Response $response, $roles)
   {
      $role = User::$role;

      if (!\in_array($role, $roles)) {
         $response->remove_all_headers();
         if (User::$group == 'admin' || User::$group == 'user') {
            $response->redirect($request->referer());
         } elseif (User::$group == 'api') {
            $response->send("Unauthorized Access", 401);
         }
      }
   }

   // Ip
   public static function ip_allow(Request $request, Response $response, $ips)
   {
      $ip = $request->ip();
      if (!\in_array($ip, $ips)) {
         $response->remove_all_headers();
         $response->redirect($request->referer());
      }
   }

   // Anti CSRF
   public static function antiCsrf(Request $request, Response $response)
   {
      try {
         if ( $request->csrftoken() == '' 
         || empty(@AES::decrypt(SECRET_KEY, $request->csrftoken())) == true) {
            // log possible csrf attack
            // throw new IException("SECURITY: Possible CSRF attack from IP: " . $request->ip());
            // log user out
            self::session_logout($request, $response);
         }
      } catch (IException $ex) {
         $ex->handle();
      }
      
   }

   // Generating CSRF Token for View
   public static function csrfToken()
   {
      // return AES::encrypt(SECRET_KEY, session_id() . time());
      return AES::encrypt( SECRET_KEY, session_id() );
   }

}
