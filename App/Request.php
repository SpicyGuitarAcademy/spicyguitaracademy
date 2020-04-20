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

   public function __construct()
   {
      
      // get the request data
      $request = $_SERVER;

      // set the ip address
      $this->ip = $request['REMOTE_ADDR'];

      // set the request scheme
      $this->scheme = $request['REQUEST_SCHEME'];

      // set the request host
      $this->host = $request['SERVER_NAME'];

      // get the request uri
      $uri = $request['REQUEST_URI'];

      // set the applications full path
      $this->path = $this->scheme . "://" . $this->host . $uri;

      // set the query string
      $this->query = $request['QUERY_STRING'];

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
      
      if (!empty($this->query))
      $this->uri = str_replace("?" . $this->query, "", $uri);

      // set the request method
      $this->method = $request['REQUEST_METHOD'];

      // Clean up
      $uri = null;
      $request = null;
      $_SERVER = null;

   }

   public function ipAddr()
   {
      return $this->request["REMOTE_ADDR"];
   }

   public function path()
   {
      return $this->path;
   }

   public function scheme()
   {
      return $this->scheme;
   }
   
   public function host()
   {
      return $this->host;
   }

   public function query()
   {
      return $this->query;
   }

   public function uri()
   {
      return $this->uri;
   }


}