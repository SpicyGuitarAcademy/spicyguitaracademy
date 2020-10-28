<?php
namespace Models;
use Framework\Database\Model;

class StudentCourseModel extends Model
{
   public function __construct()
   {
      parent::__construct('student_course_tbl');
   }

   // write wonderful model codes...

   public function getStats($email, $category) {
      return [
         $this->where("student_id = '$email' AND category_id = $category AND (status = 1 OR status = 2)")->read("*"),
         $this->where("student_id = '$email' AND category_id = $category AND (status = 1 OR status = 0 OR status = 2)")->read("*")
      ];
   }

   public function addCourseForStudent($categoryId, $courseId, $email) {

      return $this->create([
         "category_id" => $categoryId,
         "course_id" => $courseId,
         "student_id" => $email
      ]);

   }

   public function updateCourseStatus($courseId, $email, $status) {
      return $this->where("course_id = $courseId AND student_id = '$email'")->update([
         'status' => $status
      ]);
   }

   public function getActiveCourse($email) {
      return $this->where("student_course_tbl.student_id = '$email' AND (student_course_tbl.status = 1 OR student_course_tbl.status = 0) AND student_course_tbl.course_id = course_tbl.id")->misc("ORDER BY course_tbl.ord")
         ->readJoin("course_tbl, student_course_tbl", "course_tbl.*, student_course_tbl.status");
   }

}
