<?php
namespace Controllers;
use Framework\Http\Http;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Mail\Mail;
use App\Services\Auth;
use App\Services\User;
use App\Services\Validate;
use App\Services\Sanitize;
use Models\AuthModel;
use Models\TutorModel;
use Models\StudentModel;
use Framework\Cipher\Encrypt;
use Framework\Cipher\JWT;

class AuthController
{

   public function authAdminLogin(Request $req, Response $res)
   {
      $email = trim($req->body()->email);
      $password = trim($req->body()->password);
      $remember = isset($req->body()->remember) ? true : false ;
      // redirect
      $redirect = isset($req->body()->redirect) ? $req->body()->redirect : null;

      // validate un-empty fields
      if (empty($email) || empty($password)) {
         $res->send(
            $res->render('admin/login.html', [
               "email" => $email,
               "password" => $password,
               "errors" => "Invalid Email/Password"
            ])
         );
      }

      // validate email
      $v = new Validate();
      $v->alphanumeric("Email", $email)->min(5)->max(40);
      $errors = $v->errors();

      if ($errors) {
         $res->send(
            $res->render('admin/login.html', [
               "email" => $email,
               "password" => $password,
               "errors" => "Invalid Email/Password"
            ])
         );
      }
      
      // get login data
      $mdl = new AuthModel();
      $details = $mdl->getLoginDetails($email);

      if (!$details) {
         $res->send(
            $res->render('admin/login.html', [
               "email" => $email,
               "password" => $password,
               "errors" => "Invalid Email/Password"
            ])
         );
      }

      $authId = $details[0]['id'];
      $email = $details[0]['email'];
      $dbpassword = $details[0]['password'];
      $role = $details[0]['role'];
      $status = $details[0]['status'];

      // verify password
      // if ($email != "admin" || $dbpassword != "admin") {
      if (Encrypt::verifyPassword($password, $dbpassword) == false) {
         $res->send(
            $res->render('admin/login.html', [
               "email" => $email,
               "password" => $password,
               "errors" => "Invalid Email/Password"
            ])
         );
      }

      // check status
      if ($status == 'blocked') {
         $res->send(
            $res->render('admin/login.html', [
               "email" => $email,
               "password" => $password,
               "errors" => "Your Account has been Blocked. Contact the Administrator."
            ])
         );
      }

      // get details
      $mdl = new TutorModel();
      $tutor = $mdl->getTutor($email);

      // set user credentials
      $credentials = [
         "email" => $email,
         "role" => $role,
         "status" => $status,
         "fullname" => User::$fullname = $tutor[0]['firstname'] . " " . $tutor[0]['lastname'],
         "avatar" => User::$avatar = $tutor[0]['avatar']
      ];

      // consider remember
      if ($remember) {
         // extend the session lifetime
         $lifetime = (REMEMBER_ME_LIFETIME * 60);
         session_set_cookie_params($lifetime);
      }

      // add credentials to session
      $_SESSION['AUTH'] = $credentials;
      // as best practice, regenerate session id
      \session_regenerate_id();

      // set the users details
      User::$auth = true;
      User::$email = $email;
      User::$role = $role;
      User::$fullname = $tutor[0]['firstname'] . " " . $tutor[0]['lastname'];
      User::$avatar = $tutor[0]['avatar'];

      // consider redirect to previous working page
      if ($redirect != null) {
         $res->route($redirect);   
      } else {
         // redirect to dashboard
         $res->route('/admin/dashboard');
      }

   }

   public function authStudentLogin(Request $req, Response $res)
   {
      $email = trim($req->body()->email);
      $password = trim($req->body()->password);
      $remember = isset($req->body()->remember) ? true : false ;
      // redirect
      $redirect = isset($req->body()->redirect) ? $req->body()->redirect : null;

      // validate un-empty fields
      if (empty($email) || empty($password)) {
         $res->send(
            $res->render('welcome.html', [
               "email" => $email,
               "password" => $password,
               "errors" => "Invalid Email/Password"
            ])
         );
      }

      // validate email
      $v = new Validate();
      $v->alphanumeric("Email", $email)->min(5)->max(40);
      $errors = $v->errors();

      if ($errors) {
         $res->send(
            $res->render('welcome.html', [
               "email" => $email,
               "password" => $password,
               "errors" => "Invalid Email/Password"
            ])
         );
      }
      
      // get login data
      $mdl = new AuthModel();
      $details = $mdl->getLoginDetails($email);

      if (!$details) {
         $res->send(
            $res->render('welcome.html', [
               "email" => $email,
               "password" => $password,
               "errors" => "Invalid Email/Password"
            ])
         );
      }

      $authId = $details[0]['id'];
      $email = $details[0]['email'];
      $dbpassword = $details[0]['password'];
      $role = $details[0]['role'];
      $status = $details[0]['status'];

      // verify password
      // if ($email != "admin" || $dbpassword != "admin") {
      if (Encrypt::verifyPassword($password, $dbpassword) == false) {
         $res->send(
            $res->render('welcome.html', [
               "email" => $email,
               "password" => $password,
               "errors" => "Invalid Email/Password"
            ])
         );
      }

      // check status
      if ($status == 'blocked') {
         $res->send(
            $res->render('welcome.html', [
               "email" => $email,
               "password" => $password,
               "errors" => "Your Account has been Blocked. Contact the Administrator."
            ])
         );
      }

      // get details
      $mdl = new TutorModel();
      $tutor = $mdl->getTutor($email);

      // set user credentials
      $credentials = [
         "email" => $email,
         "role" => $role,
         "status" => $status,
         "fullname" => User::$fullname = $tutor[0]['firstname'] . " " . $tutor[0]['lastname']
      ];

      // consider remember
      if ($remember) {
         // extend the session lifetime
         $lifetime = (REMEMBER_ME_LIFETIME * 60);
         session_set_cookie_params($lifetime);
      }

      // add credentials to session
      $_SESSION['AUTH'] = $credentials;
      // as best practice, regenerate session id
      \session_regenerate_id();

      // set the users details
      User::$auth = true;
      User::$email = $email;
      User::$role = $role;
      User::$fullname = $tutor[0]['firstname'] . " " . $tutor[0]['lastname'];

      // consider redirect to previous working page
      if ($redirect != null) {
         $res->route($redirect);   
      } else {
         // redirect to dashboard
         $res->route('/dashboard');
      }

   }

   public function authApiLogin(Request $req, Response $res)
   {
      $email = trim($req->body()->email);
      $password = trim($req->body()->password);
      // $remember = isset($req->body()->remember) ? true : false ;
      // redirect
      $redirect = isset($req->body()->redirect) ? $req->body()->redirect : null;

      // validate un-empty fields
      if (empty($email) || empty($password)) {
        $res->error('Invalid Email/Password');
      }

      // validate email
      $v = new Validate();
      $v->alphanumeric("Email", $email)->min(5)->max(40);
      $errors = $v->errors();

      if ($errors) {
         $res->error('Invalid Email/Password');
      }
      
      // get login data
      $mdl = new AuthModel();
      $details = $mdl->getLoginDetails($email);

      if (!$details) {
        $res->error('Invalid Email/Password');
      }

      $authId = $details[0]['id'];
      $email = $details[0]['email'];
      $dbpassword = $details[0]['password'];
      $role = $details[0]['role'];
      $status = $details[0]['status'];

      // verify password
      // if ($email != "admin" || $dbpassword != "admin") {
      if (Encrypt::verifyPassword($password, $dbpassword) == false) {
         $res->error('Invalid Email/Password');
      }

      // check status
      if ($status == 'blocked') {
         $res->error('Your Account has been Blocked. Contact the Administrator');
      }

      // get details
      $mdl = new StudentModel();
      $student = $mdl->getStudent($email)[0];
      $student['status'] = $status;

      // if ($remember) {
         $lifetime = REMEMBER_ME_LIFETIME * 60;
      // } else {
      //    $lifetime = 60;
      // }
      $exp = strtotime("$lifetime minutes");
      
      // create a token
      $payloadArray = array();
      $payloadArray['email'] = $email;
      $payloadArray['role'] = $role;
      $payloadArray['fullname'] = $student[0]['firstname'] . " " . $student[0]['lastname'];
      if (isset($nbf)) {$payloadArray['nbf'] = $nbf;}
      if (isset($exp)) {$payloadArray['exp'] = $exp;}
      $token = JWT::encode($payloadArray, SECRET_KEY);

      // set the users details
      User::$auth = true;
      User::$email = $email;
      User::$role = $role;
      User::$token = $token;
        $res->success('Login Successful', [
         "student"=>$student,
         "status"=>$status,
         "token"=>$token
      ]);

   }

   public function forgotPassword(Request $req, Response $res)
    {
      $email = trim($req->body()->email);
      
      // validate email
      $v = new Validate();
      $v->email("email", $email, "Invalid Email")->min(1)->max(100);
      $errors = $v->errors();

      if ($errors) {
         $res->error('Error', "Invalid email address");
      }
      
      $token = Encrypt::token(6);
      
    //   update token for user and send mail to the user
    $msg = <<<HTML
         <div>
            <p>Your verification token is $token.</p>
         </div>
HTML;
         $send = Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", $email, "Verification Token.", 'info@spicyguitaracademy.com:Spicy Guitar Academy');
      
      // get login data
      $mdl = new AuthModel();
      $result = $mdl->updateToken($email, $token);
      
      if ($result == true) {
          $res->success("Verification token was sent to $email");
      } else {
          $res->error("Verification token was not sent");
      }
   }
   
   public function verifyAccount(Request $req, Response $res)
    {
      $email = trim($req->body()->email);
      $token = trim($req->body()->token);
      
      // validate email
      $v = new Validate();
      $v->email("email", $email, "Invalid Email")->min(1)->max(100);
      $v->any("token", $token, "Invalid Token")->exact(6);
      $errors = $v->errors();

      if ($errors) {
         $res->error('Error', $errors);
      }
      
      // get login data
      $mdl = new AuthModel();
      $result = $mdl->verifyEmailToken($email, $token);
      
    $mdl->updateStatus($email, 'active');
    $res->success("Verified account successfully");
    
   }
   
   public function resetPassword(Request $req, Response $res) {
      
      // create a resource
      $email = trim($req->body()->email);
      $password = trim($req->body()->password);
      $cpassword = trim($req->body()->cpassword);
      
      $data = [];
      
      $v = new Validate();

      // validate
      $v->email("email", $email, "Invalid Email")->min(1)->max(100);
      $v->ucletters("password", $password, "Password field must contain lowercase and uppercase and numbers")->lcletters("password", $password, "Password field must contain lowercase and uppercase and numbers")->alphanumeric("password", $password, "Password field must contain lowercase and uppercase and numbers")->min(8);
      $errors = $v->errors();

      // check cpassword
      if ($cpassword !== $password) {
         $errors['cpassword'] = "Password and Confirm Password must be the same!";
      }

      if ($errors) {
         $res->error('Reset password failed', $errors);
      }

      // No errors, sanitize fields
      $s = new Sanitize();
      $email = $s->email($email);
      
      $amdl = new AuthModel();
      
      $result = $amdl->updatePassword($email, Encrypt::hashPassword($password));
      if ($result == false) {
          $res->error('Password was not updated. Try again!');
      } else {
          $res->success('Account was created.');
      }
   }

}
