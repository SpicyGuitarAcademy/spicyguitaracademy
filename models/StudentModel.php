<?php

namespace Models;

use Framework\Database\Model;

class StudentModel extends Model
{
   public function __construct()
   {
      parent::__construct('student_tbl');
   }

   public function getAllStudents()
   {
      return $this->misc("ORDER BY date_added DESC")
         ->read('*');
   }

   public function searchAllStudents($query)
   {
      return $this->where("email LIKE '%$query%' OR firstname LIKE '%$query%' OR lastname LIKE '%$query%'")
         ->misc("ORDER BY date_added DESC")
         ->read('*');
   }

   // write wonderful model codes...
   public function addStudent($firstname, $lastname, $email, $telephone, $avatar, $referralCode, $referredBy)
   {
      return $this->create([
         'firstname' => $firstname,
         'lastname' => $lastname,
         'email' => $email,
         'telephone' => $telephone,
         'avatar' => $avatar,
         'referral_code' => $referralCode,
         'referred_by' => $referredBy
      ]);
   }

   public function getStudent($email)
   {
      return $this->where("email = '$email'")->read("*");
   }

   public function doesRefCodeExist($refCode)
   {
      return $this->where("referral_code = '$refCode'")->exist();
   }

   public function getStudentByRefCode($refCode)
   {
      return $this->where("referral_code = '$refCode'")->read("*")[0] ?? null;
   }

   public function updateRefCode($email, $refCode)
   {
      return $this->where("email = '$email'")->update([
         'referral_code' => $refCode
      ]);
   }

   public function updateRefUnits($email, $units)
   {
      return $this->where("email = '$email'")->update([
         'referral_units' => $units
      ]);
   }

   public function getStudentById($id)
   {
      return $this->where("id = '$id'")->read("*")[0] ?? null;
   }

   public function getStudentId($email)
   {
      return $this->where("email = '$email'")->read("id");
   }

   public function getStudents()
   {
      return $this->where('student_tbl.email = auth_tbl.email')->readJoin('student_tbl, auth_tbl', 'student_tbl.*, auth_tbl.role as role, auth_tbl.status as status');
   }

   public function updateAvatar($email, $path)
   {
      return $this->where("email = '$email'")->update([
         'avatar' => $path
      ]);
   }
}
