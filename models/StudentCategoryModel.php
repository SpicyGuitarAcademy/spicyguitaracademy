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
   public function addStudentCategory($studentId, $categoryId) {
      return $this->create([
         'student_id' => $studentId,
         'category_id' => $categoryId,
         'status' => 1
      ]);
   }

   public function getActiveCategory($email) {
      return $this->where("student_id = '$email' AND status = 1")->misc("ORDER BY id DESC LIMIT 1")->read("*");
   }

}
