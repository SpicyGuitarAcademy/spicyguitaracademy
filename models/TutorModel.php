<?php
namespace Models;
use Framework\Database\Model;

class TutorModel extends Model
{
   public function __construct()
   {
      parent::__construct('admin_tbl');
   }

   // write wonderful model codes...
   public function addTutor($firstname, $lastname, $email, $telephone, $experience) {
      return $this->create([
         'firstname'=>$firstname,
         'lastname'=>$lastname,
         'email'=>$email,
         'telephone'=>$telephone,
         'experience'=>$experience
      ]);
   }

   public function getTutor($email) {
      return $this->where("email = '$email'")->read("*");
   }

   public function getTutorId($email) {
      return $this->where("email = '$email'")->read("id");
   }

   public function getTutors() {
      return $this->where('admin_tbl.email = auth_tbl.email')->readJoin('admin_tbl, auth_tbl', 'admin_tbl.*, auth_tbl.role as role, auth_tbl.status as status');
   }

}
