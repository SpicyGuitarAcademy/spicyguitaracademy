<?php
namespace App;

class HttpRequest
{
   /**
    * A method that returns all the parameters in Get Request
    * 
    * @param array $routeUriChunks is an array of the route uri delimited by forward-slash (/)
    * @param array $requestUriChunks is an array of the currently requested uri delimited by forward-slash(/)
    * @return array $httpParams is an array of urlParameters
    */
   public function Get($routeUriChunks, $requestUriChunks) : array
   {
      header("Access-Control-Allow-Methods: GET");
      header("Access-Control-Max-Age: 3600");
      header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

      // empty array to collect the url parameters
      $httpParams = [];
      // set matched to true
      $uriMatch = true;

      // while the uriMatch is true, it keeps matching
      for ($i = 0; $i < count($routeUriChunks) && $uriMatch == true; $i++)
      {
         $routeUriChunk = $routeUriChunks[$i];
         $requestUriChunk = $requestUriChunks[$i];

         // if the route url preg_matches { key } 
         // it means the uri is expecting a parameter here
         if (preg_match("/{[0-9A-Za-z]+}/", $routeUriChunk)) {
            // it contains url params

            // match the key
            $routeUriChunk = preg_grep("/[0-9A-Za-z]+/",[str_replace("{","",str_replace("}","",$routeUriChunk))]);
            $paramKey = $routeUriChunk[0];
            // match the value
            $paramValue = $requestUriChunk;
            // matched
            $uriMatch = true;
            // add the key-value pair to the $httpParams array
            $httpParams[$paramKey] = $paramValue;
         }
         // and if it doesn't preg_match { value },
         else{
            // then compare the regular route directory
            if ($routeUriChunk == $requestUriChunk) {
               // matched
               $uriMatch = true;
            }else{
               // not matched
               $uriMatch = false;
            }
         }

      }

      // return [$uriMatch];
      return [$uriMatch, $httpParams];

   }

   /**
    * A method that returns all the parameters in Get & Post Request
    * 
    * @param array $routeUriChunks is an array of the route uri delimited by forward-slash (/)
    * @param array $requestUriChunks is an array of the currently requested uri delimited by forward-slash(/)
    * @return array $httpParams is an array of urlParameters
    */
   public function Post($routeUriChunks, $requestUriChunks) : array
   {
      header("Access-Control-Allow-Methods: POST");
      header("Access-Control-Max-Age: 3600");
      header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

      // all posted url parameters
      $Posted = isset($_REQUEST) ? $_REQUEST : [] ;

      // empty array to collect the url parameters
      $httpParams = [];
      // set matched to true
      $uriMatch = true;

      // while the uriMatch is true, it keeps matching
      for ($i = 0; $i < count($routeUriChunks) && $uriMatch == true; $i++)
      {
         $routeUriChunk = $routeUriChunks[$i];
         $requestUriChunk = $requestUriChunks[$i];

         // if the route url preg_matches { key } 
         // it means the uri is expecting a parameter here
         if (preg_match("/{[0-9A-Za-z]+}/", $routeUriChunk)) {
            // it contains url params

            // match the key
            $routeUriChunk = preg_grep("/[0-9A-Za-z]+/",[str_replace("{","",str_replace("}","",$routeUriChunk))]);
            $paramKey = $routeUriChunk[0];
            // match the value
            $paramValue = $requestUriChunk;
            // matched
            $uriMatch = true;
            // add the key-value pair to the $httpParams array
            $httpParams[$paramKey] = $paramValue;
         }
         // and if it doesn't preg_match { value },
         else{
            // then compare the regular route directory
            if ($routeUriChunk == $requestUriChunk) {
               // matched
               $uriMatch = true;
            }else{
               // not matched
               $uriMatch = false;
            }
         }

      }

      // merge the posted url parameters and the get url parameters
      $httpParams = array_merge($httpParams, $Posted);

      return [$uriMatch, $httpParams];

   }

}

?>