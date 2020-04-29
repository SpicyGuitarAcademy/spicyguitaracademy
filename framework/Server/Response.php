<?php
namespace Framework;
use Framework\FrameworkException;

class Response
{

   private $response_content_type;

   public function __construct()
   {
      
   }

   private function default_response_header()
   {

   }

   public static function html()
   {
      
   }

   public static function xml()
   {
      
   }

   public static function json()
   {
      
   }

   public function send(string $response_message = "", int $resp_code = 200)
   {
      // Content from http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
      $valid_response_codes = [
         // Information response codes
         100, // Continue
         101, // Switching Protocols
         103, // Processing // WebDAV; RFC 2518

         // Success response codes
         200, // Ok
         201, // Created
         202, // Accepted
         203, // Non-Authoritative Information
         204, // No Content
         205, // Reset Content
         206, // Partial Content
         // -----
         // 207, // Multi-Status // WebDAV; RFC 4918
         // 208, // IM Used // WebDAV; RFC 5842
         // 226, // IM Used // RFC 3229

         // Redirect response codes
         300, // Multiple Choices
         301, // Moved Permanently
         302, // Found
         303, // See Other
         304, // Not Modified
         306, // Switch Proxy
         307, // Temporary Redirect
         308, // Permanent Redirect // approved as experimental RFC
         // -----
         // 305, // Use Proxy

         // Client response codes
         400, // Bad Request
         401, // Unauthorized
         402, // Payment Required
         403, // Forbidden
         404, // Not Found
         405, // Method Not Allowed
         406, // Not Acceptable
         407, // Proxy Authentication Required
         408, // Request Timeout
         409, // Conflict
         410, // Gone
         411, // Length Required
         412, // Precondition Failed
         413, // Request Entity Too Large
         414, // Request-URI Too Long
         415, // Unsupported Media Type
         416, // Requested Range Not Satisfiable
         417, // Expectation Failed
         // -----
         // 418, // I'm a teapot // RFC 2324
         // 419, // Authentication Timeout Redirect // not in RFC 2616
         // 420, // Enhance Your Calm // Twitter
         // 420, // Method Failure // Spring Framework
         // 422, // Unprocessable Entity // WebDAV; RFC 4918
         // 423, // Locked // WebDAV; RFC 4918
         // 424, // Failed Dependency // WebDAV; RFC 4918
         // 424, // Method Failure // WebDAV
         // 425, // Unordered Collection // Internet draft
         // 426, // Upgrade Required // RFC 2817
         // 428, // Precondition Required // RFC 6585
         // 429, // Too Many Requests // RFC 6585
         // 431, // Request Header Fields Too Large // RFC 6585
         // 444, // No Response // Nginx
         // 449, // Retry With // Microsoft
         // 450, // Blocked by Windows Parental Controls // Microsoft
         // 451, // Redirect // Microsoft
         // 451, // Unavailable For Legal Reasons // Internet draft
         // 494, // Request Header Too Large // Nginx
         // 495, // Cert Error // Nginx
         // 496, // No Cert // Nginx
         // 497, // HTTP to HTTPS // Nginx
         // 499, // Client Closed Request // Nginx

         // Server response codes
         500, // Internal Server Error
         501, // Not Implemented
         502, // Bad Gateway
         503, // Service Unavailable
         504, // Gateway Timeout
         505, // HTTP Version Not Supported
         511, // Network Authentication Required // RFC 6585
         // ------
         // 506, // Variant Also Negotiates // RFC 2295
         // 507, // Insufficient Storage // WebDAV; RFC 4918
         // 508, // Loop Detected // WebDAV; RFC 5842
         // 509, // Bandwidth Limit Exceeded // Apache bw/limited extension
         // 510, // Not Extended // RFC 2774
         // 598, // Network read timeout error // Unknown
         // 599, // Network connect timeout error // Unknown
      ];

      try {
         if (in_array($resp_code, $valid_response_codes)) {
            // set the response code
            \http_response_code($resp_code);

            // send the response message
            exit($response_message);
         } else {
            throw new FrameworkException($this);
         }
      } catch (FrameworkException $ex) {
         $ex->log_exception(); $ex->email_developer(); $ex->show_developer();
      }
      
   }

   // handle the remaining response codes


   // header control

   public function remove_all_headers()
   {
      if (!\headers_sent()) {
         header_remove();
      }
   }

   public function remove_header(string $header)
   {
      if (!\headers_sent()) {
         \header_remove($header);
      }
   }

   public function remove_headers(array $headers)
   {
      if (!\headers_sent()) {
         foreach ($headers as $header) {
            header_remove($header);
         }
      }
   }

   public function add_header(string $header, string $value, bool $replace = true)
   {
      if (!\headers_sent()) {
         header(sprintf('%s: %s', $header, $value), $replace);
      }
   }

   public function add_headers(array $headers, bool $replace = true)
   {
      if (!\headers_sent()) {
         foreach ($headers as $header => $value) {
            \header(\sprintf('%s: %s', $header, $value), $replace);
         }
      }
   }

   // redirection
   public function redirect(string $location)
   {
      $this->add_header("Location", $location);
      $this->send("", 302);
   }

   // authentication headers
   public function auth_basic($realm)
   {
      // generate authentication parameters

      // realm, a revelation to the client as to which username and password to provide

      // set the response header
      $this->remove_all_headers();
      $this->add_header('WWW-Authenticate', sprintf('Basic realm="%s"', $realm));
      $this->send('Access Denied', 401);
   }

   public function auth_digest($realm)
   {
      // generate authentication parameters
      // nonce, to make each request unique
      $nonce = md5(uniqid("", true));
      // opaque, must be returned by the client unaltered
      $opaque = md5(uniqid());
      // qop, the quality of protection of the request
      $qop = "auth";

      // realm, a revelation to the client as to which username and password to provide

      // set the response header
      $this->remove_all_headers();
      $this->add_header('WWW-Authenticate', sprintf('Digest realm="%s", qop="%s", nonce="%s", opaque="%s"', $realm, $qop, $nonce, $opaque));
      $this->send('Access Denied', 401);
   }

   public function auth_oauth()
   {

   }

   public function auth_oauth2()
   {

   }

   public function auth_jwt()
   {

   }

}