<?php
namespace App\Services;

class Validate
{

   private $field = "";
   private $errors = [];

   // numbers
   public function numbers(string $field, $value, string $errmsg = "invalid number!")
   {
      if (filter_var($value, FILTER_VALIDATE_INT, [FILTER_FLAG_ALLOW_OCTAL, FILTER_FLAG_ALLOW_HEX]) == false) {
         $this->errors[$field] = $errmsg;
      }
   }

   // amount
   public function amount(string $field, $value, string $errmsg = "invalid amount!")
   {
      if (filter_var($value, FILTER_VALIDATE_FLOAT, [FILTER_FLAG_ALLOW_THOUSAND]) == false) {
         $this->errors[$field] = $errmsg;
      }
   }


   
   private static $any_param_type = "/[0-9A-Za-z -_]+/";

   private static $digits_param_type  = "/[0-9]+/";                          //:d

   private static $letters_param_type = "/[A-Za-z]+/";                       //:a

   private static $letters_plus_param_type = "/[A-Za-z -_]+/";               //:a+

   private static $alpha_numeric_param_type = "/[0-9A-Za-z]+/";              //:x

   private static $alpha_numeric_plus_param_type = "/[0-9A-Za-z -_]+/";      //:x+

   // letters
   public function letters(string $field, $value, string $errmsg = "invalid letters!")
   {
      if (preg_match("/[A-Za-z]+/", $value) == false) {
         $this->errors[$field] = $errmsg;
      }
   }

   // alphanumeric
   public function alphanumeric(string $field, $value, string $errmsg = "invalid alphanumeric!")
   {
      if (preg_match("/[A-Z0-9a-z]+/", $value) == false) {
         $this->errors[$field] = $errmsg;
      }
   }

   // alphanumenricwildcards | password
   public function password(string $field, $value, string $errmsg = "invalid password!")
   {
      if (preg_match("/[A-Z0-9a-z$%&@#£=_\.]+/", $value) == false) {
         $this->errors[$field] = $errmsg;
      }
   }

   // email
   public function email(string $field, string $errmsg = "invalid email!")
   {
      if (filter_var($value, FILTER_VALIDATE_EMAIL, [FILTER_FLAG_EMAIL_UNICODE]) == false) {
         $this->errors[$field] = $errmsg;
      }
   }

   // telephone
   public function telephone(string $field, $value, string $errmsg = "invalid telephone!")
   {
      if ( preg_match("/^[0-9-:\/WT]+$/", $value) == false ){
         $this->errors[$field][] = $errmsg;
      }
   }

   // ip
   public function ip(string $field, $value, string $errmsg = "invalid ip address!")
   {
      if (filter_var($value, FILTER_VALIDATE_IP, [FILTER_FLAG_IPV4, FILTER_FLAG_IPV6, FILTER_FLAG_NO_PRIV_RANGE, FILTER_FLAG_NO_RES_RANGE]) == false) {
         $this->errors[$field] = $errmsg;
      }
   }

   // mac
   public function mac(string $field, $value, string $errmsg = "invalid mac address!")
   {
      if (filter_var($value, FILTER_VALIDATE_MAC) == false) {
         $this->errors[$field] = $errmsg;
      }
   }

   // url
   public function url(string $field, $value, string $errmsg = "invalid password!")
   {
      if (filter_var($value, FILTER_VALIDATE_URL, [FILTER_FLAG_SCHEME_REQUIRED, FILTER_FLAG_HOST_REQUIRED, FILTER_FLAG_PATH_REQUIRED, FILTER_FLAG_QUERY_REQUIRED]) == false) {
         $this->errors[$field] = $errmsg;
      }
   }

   // domain
   public function domain(string $field, $value, string $errmsg = "invalid password!")
   {
      if (filter_var($value, FILTER_VALIDATE_DOMAIN, [FILTER_FLAG_HOSTNAME]) == false) {
         $this->errors[$field] = $errmsg;
      }
   }

   // date
   public function date()
   {
      if ( preg_match("/^[0-9-:\/WT]+$/", $value) == false ){
         $this->errors[$field][] = $errMsg;
      }
   }

   // -----------

   // exact

   // max

   // min

   // required

}