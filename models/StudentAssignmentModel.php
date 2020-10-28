<?php
namespace Models;
use Framework\Database\Model;

class StudentAssignmentModel extends Model
{
   public function __construct()
   {
      parent::__construct('student_assignment_tbl');
   }

   // write wonderful model codes...
   public function getAnswers($courseId) {
      return $this->where("course_id = $courseId AND status = 1")->read("*");
   }

   public function addAssignmentForStudent($studentId, $courseId, $assignmentId, $tutorId) {
      return $this->create([
         "student_id" => $studentId,
         "course_id" => $courseId,
         "assignment_id" => $assignmentId,
         "tutor_id" => $tutorId
      ]);
   }

   public function getAvailableAssignment($email, $course) {
      return $this->where("status = 0 AND student_id = '$email' AND course_id = $course")->misc("LIMIT 1")->read("*");
   }

   public function answerAsVideo($email, $assignmentId, $path) {
      return $this->where("student_id = '$email' AND assignment_id = $assignmentId")
      ->update([
         'video'=>$path,
         'status'=>1
      ]);
   }

   public function answerAsNote($email, $assignmentId, $note) {
      return $this->where("student_id = '$email' AND assignment_id = $assignmentId")
      ->update([
         'note'=>$note,
         'status'=>1
      ]);
   }

}
