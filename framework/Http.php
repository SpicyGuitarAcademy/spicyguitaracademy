<?php
namespace Framework;
use Framework\Request;
use Framework\Response;
use Framework\Routing;
use Framework\View;

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

   public function get(string $route, string $next)
   {
      // restrict to only GET requests
      if ($this->method != "GET") {
         return;
      }

      $this->handle_web_request($route, $next);
   }

   public function post(string $route, $next)
   {
      // restrict to only POST requests
      if ($this->method != "POST") {
         return;
      }

      $this->handle_web_request($route, $next);
   }

   public function put(string $route, $next)
   {
      // restrict to only PUT requests
      if ($this->method != "PUT") {
         return;
      }

      $this->handle_web_request($route, $next);
   }

   public function patch(string $route, $next)
   {
      // restrict to only PATCH requests
      if ($this->method != "PATCH") {
         return;
      }

      $this->handle_web_request($route, $next);
   }

   public function delete(string $route, $next)
   {
      // restrict to only PATCH requests
      if ($this->method != "PATCH") {
         return;
      }

      $this->handle_web_request($route, $next);
   }

   private function handle_web_request(string $route, $next)
   {
      // If application is on maintenance mode and client IP is not whitelisted
      // block the access
      if (\Config['APP_MAINTAINANCE'] == true && !in_array($this->request->ip(), \Config['APP_MAINTENANCE_WHITELIST_IP'])) {
         $this->response->send(View::render('framework/maintenance.html'), 403);
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

   public function end()
   {
      $this->response->send(View::render('framework/404.html'), 404);
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