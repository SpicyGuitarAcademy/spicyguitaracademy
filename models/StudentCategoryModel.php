<?php

namespace Models;

use Framework\Database\Model;

class StudentCategoryModel extends Model
{
   public function __construct()
   {
      parent::__construct('student_category_tbl');
   }

   // write wonderful model codes...
   public function addStudentCategory($studentId, $categoryId, $status = 1)
   {
      if ($status == 0) {
         // don't repeat entry for new category when one is open already
         if ($this->where("status = 0 AND student_id = '$studentId' AND category_id = $categoryId ")->exist()) {
            return true;
         }
      }
      return $this->create([
         'student_id' => $studentId,
         'category_id' => $categoryId,
         'status' => $status
      ]);
   }

   public function makeCategoryActive($email, $categoryId)
   {
      return $this->where("student_id = '$email' AND category_id = $categoryId")->update([
         'status' => 1
      ]);
   }

   public function listStudentCategory($email)
   {
      return $this->where("student_id = '$email'")->read("*, DATE_FORMAT(date_started,'%d/%m/%y %l:%i %p') as date_started");
   }

   public function getActiveCategory($email)
   {
      return $this->where("student_id = '$email' AND status = 1")->misc("ORDER BY id DESC LIMIT 1")->read("*");
   }
}
