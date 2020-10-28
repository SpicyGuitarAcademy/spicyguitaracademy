<?php
namespace Controllers;
use Framework\Http\Http;
use Framework\Http\Request;
use Framework\Http\Response;
use App\Services\Auth;
use App\Services\Validate;
use App\Services\Sanitize;
use App\Services\Upload;
use App\Services\User;
use Framework\Cipher\Encrypt;
use Models\TutorModel;
use Models\AuthModel;

class TutorController
{

   public function index(Request $req, Response $res)
   {
      $mdl = new TutorModel();
      $tutors = $mdl->getTutors();

      $res->send(
         $res->render('admin/tutors.html', [
            'tutors'=>json_encode($tutors)
         ])
      );
   }

   public function new(Request $req, Response $res)
   {
      $res->send(
         $res->render('admin/new-tutor.html')
      );
   }

   public function create(Request $req, Response $res)
   {
      $firstname = trim($req->body()->firstname);
      $lastname = trim($req->body()->lastname);
      $email = trim($req->body()->email);
      $telephone = trim($req->body()->telephone);
      $role = trim($req->body()->role);
      $password = trim($req->body()->password);
      $cpassword = trim($req->body()->cpassword);
      
      $data = [
         "firstname" => $firstname,
         "lastname" => $lastname,
         "email" => $email,
         "telephone" => $telephone,
         "role" => $role,
         "password" => $password,
         "cpassword" => $cpassword
      ];

      $v = new Validate();

      // validate
      $v->letters("firstname", $firstname, "Invalid Firstname")->max(20);
      $v->letters("lastname", $lastname, "Invalid Lastname")->max(20);
      $v->email("email", $email, "Invalid Email")->min(1)->max(100);
      $v->telephone("telephone", $telephone, "Invalid Telephone")->max(20);
      $v->letters("role", $role, "Invalid Role")->max(10);
      $v->password("password", $password, "Invalid Password")->min(8);
      $errors = $v->errors();

      // check cpassword
      if ($cpassword !== $password) {
         $errors['cpasswprd'] = "Password and Confirm Password must be the same!";
      }

      if ($errors) {
         $data['errors'] = json_encode($errors);
         $res->send(
            $res->render('admin/new-tutor.html', $data)
         );
      }

      // No errors, sanitize fields
      $s = new Sanitize();
      $firstname = $s->string($firstname);
      $lastname = $s->string($lastname);
      $email = $s->email($email);
      $telephone = $s->string($telephone);
      $role = $s->string($role);

      $amdl = new AuthModel();
      $mdl = new TutorModel();

      if ($amdl->emailExists($email) == true) {
         $data['errors'] = json_encode(['Email already exists. Try another email!']);
         $res->send(
            $res->render('admin/new-tutor.html', $data)
         );
      }

      $add = $amdl->addAuthDetails($email, Encrypt::hashPassword($password), $role);
      if ($add == false) {
         $data['errors'] = json_encode(['Account was not created. Try again!']);
         $res->send(
            $res->render('admin/new-tutor.html', $data)
         );
      }
      
      if ($mdl->addTutor($firstname, $lastname, $email, $telephone, date('Y')) == true) {
         $res->route("/admin/tutors?msg=Tutor was created successfully;Tutor's login credentials are:<br>Email: $email, Password: $password");
      } else {
         $data['errors'] = json_encode(['Account was not created. Try again!']);
         $res->send(
            $res->render('admin/new-tutor.html', $data)
         );
      }

   }

   public function read(Request $req, Response $res)
   {
      // return a resource
   }

   public function update(Request $req, Response $res)
   {
      // update a resource
   }

   public function updateStatus(Request $req, Response $res)
   {
      $id = $req->body()->id ?? '';
      $status = $req->body()->status ?? '';

      $v = new Validate();
      $v->numbers("id", $id, "Invalid Id!")->minvalue(1);
      $errors = $v->errors();

      if ($errors || !in_array($status, ["active", "blocked", "inactive"])) {
         $res->redirect($req->referer());
      } else {
         $mdl = new AuthModel();
         $updated = $mdl->updateStatus($id, $status);

         // 
         $res->route("/admin/tutors?msg=Account Updated Successfully");
      }
   }

   public function delete(Request $req, Response $res)
   {
      // remove a resouce
   }

}
