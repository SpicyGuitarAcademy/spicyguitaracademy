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

   private $params;

   public function __construct()
   {
      // get the request data
      $this->request = $_SERVER;

      // et the ip address
      $this->get_ip();

      // set the request scheme
      $this->get_scheme();

      // set the request host
      $this->get_host();

      // set the url path
      $this->get_path();

      // set the request method
      $this->get_method();

      // set the request query parameters
      $this->get_query();

      // set the request body
      $this->get_body();

      // set the request uri
      $this->get_uri();

      // set the files submitted
      $this->get_files();

      // accomodating request methods from html
      $this->get_html_request_methods();
   }

   // --------------------------------------------------------- //

   private function get_ip()
   {
      $this->ip = $this->request['REMOTE_ADDR'];
   }
   
   private function get_path()
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

   private function get_host()
   {
      $this->host = $this->request['SERVER_NAME'];
   }

   private function get_method()
   {
      $this->method = $this->request['REQUEST_METHOD'];
   }

   private function get_scheme()
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

   private function get_uri()
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

   private function get_query()
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

   private function get_body()
   {
      $this->body = $this->objectify($_POST ?? []);
   }

   private function get_files()
   {
      $this->files = $this->objectify($_FILES ?? []);
   }

   private function get_html_request_methods()
   {  
      // accomodating methods from html
      if ($this->method == "POST" && $this->body_exists() == true && isset($this->body()->REQUEST_METHOD) && in_array($this->body()->REQUEST_METHOD, ["PUT", "PATCH", "DELETE"]))
      {
         $this->method = $this->body()->REQUEST_METHOD;
      }

      // echo $this->method;
      // echo $this->body_exists() ? "true" : "false";
      // echo $this->body()->HTTP_METHOD;
      // die;
   }

   // Called from the Http class //
   // -------------------------- //
   public function set_route_params(array $params)
   {
      $this->params = $this->objectify($params ?? []);
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

   // Publicly accessible methods for getting the request parameters //
   // -------------------------------------------------------------- //

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

   public function params()
   { return $this->params; }

   public function params_exists()
   { return is_object($this->params); }
   
   // --------------------------------------------------------- //

   public function __desctruct()
   {
      $this->request = null;
   }

}