<?php
namespace App;

class Request
{
   
   private $request;

   private $ip;

   private $path;

   private $scheme;

   private $host;

   private $query;

   private $uri;

   private $method;

   private $body;

   private $files;

   public function __construct()
   {
      // get the request data
      $this->request = $_SERVER;

      // et the ip address
      $this->_ip();

      // set the request scheme
      $this->_scheme();

      // set the request host
      $this->_host();

      // set the url path
      $this->_path();

      // set the request method
      $this->_method();

      // set the request query parameters
      $this->_query();

      // set the request body
      $this->_body();

      // set the request uri
      $this->_uri();

      // set the files submitted
      $this->_files();

   }

   public function ip()
   { return $this->ip; }

   public function path()
   { return $this->path; }

   public function scheme()
   { return $this->scheme; }
   
   public function host()
   { return $this->host; }

   public function uri()
   { return $this->uri; }

   public function method()
   { return $this->method; }

   public function files()
   { return $this->files; }

   public function files_exists()
   { return is_object($this->files); }

   public function body()
   { return $this->body; }

   public function body_exists()
   { return is_object($this->body); }

   public function query()
   { return $this->query; }

   public function query_exists()
   { return is_object($this->query); }

   // --------------------------------------------------------- //
   // --------------------------------------------------------- //

   private function _ip()
   {
      $this->ip = $this->request['REMOTE_ADDR'];
   }
   
   private function _path()
   {
      /* 
         ----------------------------------------------------------------
         Setting the request path
         ----------------------------------------------------------------

         The request path is a combination of the request scheme, the 
         hostname and the request uri which also contains the query 
         string.

         ----------------------------------------------------------------
      */

      $uri = $this->request['REQUEST_URI'];
      $this->path = urldecode($this->scheme . "://" . $this->host . $uri);
   }

   private function _host()
   {
      $this->host = $this->request['SERVER_NAME'];
   }

   private function _method()
   {
      $this->method = $this->request['REQUEST_METHOD'];
   }

   private function _scheme()
   {
      /* 
         ----------------------------------------------------------------
         Setting the request scheme
         ----------------------------------------------------------------

         The request scheme is the part before the :// in the url.
         The most common request schemes are http and https.

         ----------------------------------------------------------------
      */
      $this->scheme = $this->request['REQUEST_SCHEME'];
   }

   private function _uri()
   {
      /* 
         ----------------------------------------------------------------
         Setting the real request uri
         ----------------------------------------------------------------

         When on localhost, the exact request uri cannot be retrieved.
         Hence we retrieve it by replacing the APP_URL (from Config.php) 
         in the full request path with an empty string; and also replacing
         the query string with an empty string.

         ----------------------------------------------------------------
      */

      $uri = str_replace(\Config["APP_URL"], "", $this->path);
      
      // get the query string
      $queryString = $this->request['QUERY_STRING'];

      $this->uri = str_replace("?" . $queryString, "", $uri);
   }

   private function _query()
   {
      /* 
         ----------------------------------------------------------------
         Setting the query parameters
         ----------------------------------------------------------------

         The query parameters can be gotten from the query string or from 
         the _GET global variable.
         It is then converted into an object.

         ----------------------------------------------------------------
      */

      $this->query = $this->objectify($_GET ?? []);
   }

   private function _body()
   {
      $this->body = $this->objectify($_POST ?? []);
   }

   private function _files()
   {
      $this->files = $this->objectify($_FILES ?? []);
   }

   private function objectify(array $array)
   {
      if (is_array($array) && $array !== []) {
         $obj = new \stdClass();
         foreach ($array as $key => $value) {
            // convert the value into an object if value is also an array
            if (is_array($value)) {
               $value = $this->objectify($value);
            }
            // assign the value to the key
            $obj->$key = $value;
         }

         $value = null;
         return $obj;
      }

      return null;
   }

   public function filterRoute(String $route)
   {
      
   }

   public function __desctruct()
   {
      $this->request = null;
   }

}