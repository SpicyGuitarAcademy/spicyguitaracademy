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

   public function __construct()
   {
      
      // get the server
      $server = $_SERVER;

      // get the ip address
      $this->ip = $server['REMOTE_ADDR'];

      // get the request scheme
      $this->scheme = $server['REQUEST_SCHEME'];

      // get the request host
      $this->host = $server['SERVER_NAME'];

      // get the request uri
      $uri = $server['REQUEST_URI'];

      // set the applications full path
      $this->path = $this->scheme . "://" . $this->host . $uri;

      // set the real request uri
      // remove localhost
      $this->uri = str_replace(\Config["APP_URL"], "", $uri);

      die($this->uri);

      $this->query = $server['QUERY_STRING'];

   }

   public function ipAddr()
   {
      return $this->request["REMOTE_ADDR"];
   }

   public function path()
   {
      return $this->request["REQUEST_SCHEME"];
   }

   public function scheme()
   {
      return $this->request["REQUEST_SCHEME"];
   }
   
   public function host()
   {
      return $this->request["REQUEST_SCHEME"];
   }

   public function query()
   {
      return $this->request["REQUEST_SCHEME"];
   }

   public function uri()
   {
      return $this->request["REQUEST_SCHEME"];
   }


}