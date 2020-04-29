<?php
namespace Providers;
use Core\Model;

class Auth extends Model
{
	private static $Model;
	// table fields: user_id(string:20), api_token(string:60), remember_token(string:60)

	public function __construct()
	{
		self::$Model = parent::__construct("authentication_tbl");
	}

	// methods...

	public static function addAuth(string $userId, string $role)
	{
		self::$Model->create([
			"user_id"=>$userId,
			"role"=>$role,
			"api_token"=>"",
			"remember_token"=>""
		]);
	}

	private static function token(string $field)
	{
		$token = bin2hex(openssl_random_pseudo_bytes(30));
		while (self::$Model->where("$field = '$token'")->exist() == true) {
			$token = bin2hex(openssl_random_pseudo_bytes(30));
		}

		return $token;
	}

	public static function login(string $userId, string $role, bool $remember)
	{
		// if remember user is set to true
		if ($remember == true)
		{
			// set session
			$_SESSION[session_id().\Config["APP"]]["USER"] = $userId;
			$_SESSION[session_id().\Config["APP"]]["ROLE"] = $role;

			// generate token for cookie
			$token = self::token("remember_token");

			// store token in database
			self::$Model->where("user_id = '$userId'")
			->update([
				"remember_token"=>$token
			]);

			// set timeout for cookie
			$timeout = time() + (\Config["AUTH_TIMEOUT"] * 3600);

			// set cookie
			\setcookie(\Config["APP"],$token,$timeout,"/");
		}
		
		// if remember user is set to false
		elseif ($remember == false)
		{
			// set session
			$_SESSION[session_id().\Config["APP"]]["USER"] = $userId;
			$_SESSION[session_id().\Config["APP"]]["ROLE"] = $role;
		}
	}

	public static function loginApi(string $userId)
	{
		// generate token for api
		$token = self::token("api_token");

		// store token in database
		self::$Model->where("user_id = '$userId'")
		->update([
			"api_token"=>self::token("api_token")
		]);
	}

	public static function check(string $token = "") : bool
	{
		// if a session has been set
		if (isset($_SESSION[session_id().\Config["APP"]]["USER"]))
		{
			// user has already been authenticated before now
			return true;
		}
		
		// else if a cookie has been set for remember me token
		elseif (isset($_COOKIE[\Config["APP"]]))
		{
			// user used the remember me to remain authenticated

			// get token from cookie
			$token = $_COOKIE[\Config["APP"]];

			// verify the token gotten
			$authData = self::$Model->where("remember_token = '$token'")
			->read("user_id, role");

			// check if token exists
			if ($authData["flag"] == true) {
				// token exists

				// create session for the user
				$_SESSION[session_id().\Config["APP"]]["USER"] = $authData["data"][0]["user_id"];
				$_SESSION[session_id().\Config["APP"]]["ROLE"] = $authData["data"][0]["role"];
				return true;
			} else {
				// cookie token doesn't exist in the database
				// therefore it must have been modified
				return false;
			}
		}

		// else if a token was sent from an api request
		elseif ($token != "")
		{
			// verify the token gotten
			$authData = self::$Model->where("api_token = '$token'")
			->read("user_id, role");

			// check if token exists
			if ($authData["flag"] == true) {
				// token exists

				// create session for the user
				$_SESSION[session_id().\Config["APP"]]["USER"] = $authData["data"][0]["user_id"];
				$_SESSION[session_id().\Config["APP"]]["ROLE"] = $authData["data"][0]["role"];
				return true;
			} else {
				// token doesn't exist in the database
				// therefore it must have been modified
				return false;
			}
		}

		// else
		else
		{
			// cookie must have expired
			// token must not have been sent
			// the user must have closed the browser before to expire the session
			return false;
		}
	}

	public static function guard(string $role) : bool
	{
		if ($_SESSION[session_id().\Config["APP"]]["ROLE"] == $role) {
			return true;
		} else {
			return false;
		}
	}

	public static function user()
	{
		if (isset($_SESSION[session_id().\Config["APP"]]["USER"])) {
			return $_SESSION[session_id().\Config["APP"]]["USER"];
		}
	}

	public static function logout()
	{
		// if user is being remembered
		if (isset($_COOKIE[\Config["APP"]]))
		{
			// session have been set
			$userId = $_SESSION[session_id().\Config["APP"]]["USER"];
			// remove token from the database
			self::$Model->where("user_id = '$userId'")
			->update([
				"remember_token"=>""
			]);

			// set expiration to an hour ago for cookie
			$expiration = time() - 3600;

			// set cookie
			\setcookie(\Config["APP"],"",$expiration,"/");
		}

		// if session exists which must exist
		if (isset($_SESSION[session_id().\Config["APP"]]["USER"])) {
			// remove all session variables
			\session_unset();
			
			// destroy the session
			\session_destroy();
		}

	}

	public static function logoutApi(string $userId)
	{
		self::$Model->with("authentication_tbl");

		// remove token from the database
		self::$Model->where("user_id = '$userId'")
		->update([
			"api_token"=>""
		]);
	}

}

?>
