<?php

namespace Models;

use Framework\Database\Model;

class AssignmentAnswerModel extends Model
{
   public function __construct()
   {
      parent::__construct('assignment_answer');
   }

   public function getAnswers($courseId, $assignmentNumber, $student)
   {
      return $this->where("course_id = $courseId 
      AND assignment_number = $assignmentNumber AND student = '$student'")
         ->misc("ORDER BY date_added ASC")
         ->read("*");
   }

   public function addAnswer($courseId, $assignmentNumber, $type, $content, $student)
   {
      return $this->create([
         "course_id" => $courseId,
         "assignment_number" => $assignmentNumber,
         "type" => $type,
         "content" => $content,
         "student" => $student
      ]);
   }

   public function addAdminAnswer($courseId, $assignmentNumber, $type, $content, $student, $tutor)
   {
      return $this->create([
         "course_id" => $courseId,
         "assignment_number" => $assignmentNumber,
         "type" => $type,
         "content" => $content,
         "student" => $student,
         "tutor" => $tutor
      ]);
   }

}
