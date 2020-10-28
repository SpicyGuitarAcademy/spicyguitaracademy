<?php
namespace Models;
use Framework\Database\Model;

class StudentModel extends Model
{
   public function __construct()
   {
      parent::__construct('student_tbl');
   }

   // write wonderful model codes...
   public function addStudent($firstname, $lastname, $email, $telephone, $avatar) {
      return $this->create([
         'firstname'=>$firstname,
         'lastname'=>$lastname,
         'email'=>$email,
         'telephone'=>$telephone,
         'avatar'=>$avatar
      ]);
   }

   public function getStudent($email) {
      return $this->where("email = '$email'")->read("*");
   }

   public function getStudentId($email) {
      return $this->where("email = '$email'")->read("id");
   }

   public function getStudents() {
      return $this->where('student_tbl.email = auth_tbl.email')->readJoin('student_tbl, auth_tbl', 'student_tbl.*, auth_tbl.role as role, auth_tbl.status as status');
   }

   public function updateAvatar($email, $path) {
      return $this->where("email = '$email'")->update([
         'avatar'=>$path
      ]);
   }

}
