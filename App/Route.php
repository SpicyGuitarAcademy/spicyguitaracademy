<?php
namespace App;
use App\HttpRequest;
use App\Request;
use App\Error;

class Route
{
   private $requestUrl;
   private static $allRoutes = array();
   public static $appUrl;

   public function __construct()
   {
      \session_start();
      $this->setRequestURL();
   }

   /**
    * Set the request URL
    */
   private function setRequestURL()
   {
      // Get the server uri
      $uri = $_SERVER["REQUEST_URI"];
      // when URLs have URL parameters like after the ? sign, still get the exact url before the ?
      if ($_GET != []) {
         $offset = strrpos($uri,"?");
         $uri = substr($uri, 0, $offset);
      }
      // Get the domain
      $domain = \Config["DOMAIN"];
      if($_SERVER["HTTP_HOST"] == "localhost") {
         $this->requestUrl = str_replace($domain, "", $uri);
      } else {
         $this->requestUrl = $uri;
      }
      // Set the app directory for the resource function to make use of
      self::$appUrl = \Config["DOMAIN"];
   }
 
   /**
    * Set the route details
    * 
    * @param string $uri the url to be matched with
    * @param string $name the unique name of the route
    * @param string $controller the controller to be called when url is matched
    */
    
   public static function get(string $uri, string $name, string $controller)
   {
      if ( isset($uri) && isset($name) && isset($controller) && (self::checkUnique($uri,$name) == true) ){
         self::$allRoutes[] = [
            "verb"=>"get",
            "uri"=>$uri,
            "name"=>$name,
            "controller"=>$controller
         ];
      }
   }

   /**
    * Set the route details
    * 
    * @param string $uri the url to be matched with
    * @param string $name the unique name of the route
    * @param string $controller the controller to be called when url is matched
    */
   public static function post(string $uri, string $name, string $controller)
   {
      if ( isset($uri) && isset($name) && isset($controller) && (self::checkUnique($uri,$name) == true) ){
         self::$allRoutes[] = [
            "verb"=>"post",
            "uri"=>$uri,
            "name"=>$name,
            "controller"=>$controller
         ];
      }
   }

   /**
    * A method to check if a route name is unique
    * 
    * @return bool to indicate if the name is unique
    */
   private static function checkUnique(string $uri, string $name) : bool
   {
      foreach (self::$allRoutes as $route ) {
         if ($route['name'] == $name || $route['uri'] == $uri){
            // handle error
            $stack = array_reverse(\debug_backtrace());
            $errfile = $stack[2]['file'];
            $errline = $stack[2]['line'];
            Error::internalError("Route <i><b>'$uri'</b></i> with name <i><b>'$name'</b></i> already exists in <b>$errfile</b> on line <b>$errline</b> <!--");
         } else {
            continue;
         }
      }

      return true;
   }

   /**
    * A method to match all the registered route with the current uri, and calls its controller
    */
   public function Init(Request $Request)
   {

      $HRequest = new HttpRequest();

      // Loop through all routes
      foreach (self::$allRoutes as $route ) {
         // If a particular route is met ...
         
         // break the url
         $routeUriChunks = explode("/", $route["uri"]);
         $requestUriChunks = explode("/", $Request->uri());
         
         $httpVerb = $route["verb"]; // get the http verb

         $routed = false; // to know if route was completed successfully
         
         if (count($routeUriChunks) == count($requestUriChunks)) {
            // start comparing verbs

            if ($httpVerb == "get") {
               // get the status of the matching and the parameters if any exists
               list($status, $httpParams) = $HRequest->Get($routeUriChunks, $requestUriChunks);
            }

            elseif ($httpVerb == "post") {
               // get the status of the matching and the parameters if any exists
               list($status, $httpParams) = $HRequest->Post($routeUriChunks, $requestUriChunks);
            }

            // if at the end of the matching the bool is true, then it can route
            if ( $status == true ){
               // get the controller
               $controller = $route["controller"];
               // break the controller
               $break = explode('@', $controller);
               // Get the Controller Class and the Method
               $controllerName = $break[0];
               $methodName = $break[1];

               // include the controller's namespace
               $namespace = "Controllers\\";
               $controller = $namespace.$controllerName;

               // Create a new instance of the Controller
               if (class_exists($controller)) {
                  $controller = new $controller();
                  // if the requested method exists
                  if (method_exists($controller, $methodName)){
                     // send the result from the controller to the HttpResponse class to return response to the user
                     // NB: httpParams would always be sent to a controller method
                     $routed = true;
                     $controller->$methodName($httpParams);
                  }else{
                     // handle error
                     Error::notFound("Requested Controller Method <i><b>'$methodName'</b></i> not found in controller <i><b>'$controllerName'</b></i> <!--");
                  }
               } else{
                  // handle error
                  Error::notFound("Requested Controller <i><b>'$controller'</b></i> not found <!--");
               }
               break;
            } else {
               // both route don't match, check next route
               continue;
            }
         }else{
            // both route don't match - they're unequal, check next route
            continue;
         }

      }

      if ($routed == false) {
         // handle error
         Error::notFound("Route <i><b>'$this->requestUrl'</b></i> not found <!--");   
      }

   }

   
   /**
    * Depreciated
    * This method calls a particular controller from within another controller
    *
    * @param string controller
    * @return Controller
    */
   public function call(string $controller = "", array $data = [])
   {
      // If controller is not given, return nothing
      if ($controller == ""){
         return;
      }

      // Get the Route Controller and the Requested Method
      if (is_string($controller)){
         $break = explode('@', $controller);
         $controller = $break[0];
         $method = $break[1];

         // include the controller's namespace
         $namespace = "Controllers\\";
         $controller = $namespace.$controllerName;

         // Create a new instance of the Controller
         $controller = new $controller();
         
         if (method_exists($controller, $methodName)){
            // send the result from the controller to the HttpResponse class to return response to the user
            // NB: httpParams would always be sent to a controller method
            return $controller->$methodName($data);
         }else{
            // handle error
            Error::notFound("Requested method <i><b>'$methodName'</b></i> not found in controller <i><b>'$controllerName'</b></i> <!--");
         }
         
      }
   }

   public static function redirect(string $name, array $data = null)
   {
      $url = self::getUri($name, $data);
      if (!\headers_sent()) {
         header("Location: ". $url);
         exit;
      }
   }

   /**
    * This method returns the route url to the view
    *
    * @param string $name
    * @param array $urlParams
    * @return string $routeUrl
    */
   public static function getUri(string $name, array $urlParams = null)
   {
      if ( isset($name) ) {
         $domain = \Config["DOMAIN"];
         // url to be returned
         $routeUrl = "";

         foreach (self::$allRoutes as $route ) {
            if ( $route['name'] == $name ) {

               // get the url
               $uri = $route['uri'];

               // if urlParams is set, then
               if (isset($urlParams)) {
                  // break the url
                  $break = explode("/", $uri);
                  // assign the matched url parameter placeholder to an array
                  $got = preg_grep("/{[0-9a-zA-Z_+-@#]+}/", $break);

                  foreach ($got as $key => $value) {
                     // strip the curly braces and get the urlParam array position
                     $value = str_replace("{", "", str_replace("}", "", $value));
                     // replace any forward slash with an underscore (_) character
                     $urlParams[$value] = str_replace("/","_",$urlParams[$value]);
                     // assign the value of the urlparam to the broken url
                     $break[$key] = $urlParams[$value];
                  }

                  // implode the url together
                  $uri = implode("/", $break);
               }
               
               $routeUrl = $domain . $uri;
               return $routeUrl;
            }
         }
      }
   }

   public static function back()
   {
      if (isset($_SERVER["HTTP_REFERER"])) {
         header("Location: ". $_SERVER["HTTP_REFERER"]);
      } else {
         header("Location: " . self::getUri('home'));
      }
   }

}

?>