<?php
namespace App;

use App\Request;
use App\Response;
use App\Routing;

class Http
{
   
   private $request;

   private $response;

   private $uri;

   private $method;

   public function __construct()
   {
      $this->request = new Request();
      $this->response = new Response();

      $this->method = $this->request->method();
      $this->uri = $this->request->uri();
   }

   // $this->
   // self::$

   public function get(string $route, string $next)
   {
      self::__construct();
      // restrict to only GET requests
      if ($this->method != "GET") {
         return;
      }

      // compare the uri with the route
      list($status, $route_params) = Routing::compare($route, $this->uri);

      // if comparison fails
      if ($status == false) {
         return;
      }
   
      // set the route parameter property of Request
      $this->request->set_route_params($route_params);

      // set a default response header for the request

      // then call the controller method -> next
      Routing::route($next, $this->request, $this->response);
   }

   public static function post()
   {
      
   }

   public function put(string $route, $next)
   {
      // restrict to only GET requests
      if ($this->method != "PUT") {
         return;
      }

      // compare the uri with the route
      list($status, $route_params) = Routing::compare($route, $this->uri);
      // list($status, $route_params) = Routing::compare($route, self::$uri);

      // if comparison fails
      if ($status == false) {
         return;
      }
   
      // set the route parameter property of Request
      $this->request->set_route_params($route_params);

      // set a default response header for the request

      // check if next is a function, and execute it
      if (\is_callable($next)) {
         $next($this->request, $this->response);
      }
      // else if it is a string
      elseif (\is_string($next)) {
         // then call the controller method -> next
         Routing::route($next, $this->request, $this->response);
      }
   }

   public static function patch()
   {
      
   }

   public static function delete()
   {
      
   }

   public function end()
   {
      $this->response->not_found();
   }

   public static function middleware()
   {
      return self;
   }

   public static function auth()
   {
      return self;
   }

   public static function guard()
   {
      
   }

   public function __desctruct()
   {
      $this->request = null;
   }

}