<?php

namespace Models;

use Framework\Database\Model;

class StudentAssignmentModel extends Model
{
   public function __construct()
   {
      parent::__construct('student_assignment');
   }

   public function addAssignmentForStudent($student, $courseId, $assignmentNumber)
   {
      return $this->create([
         "course_id" => $courseId,
         "assignment_number" => $assignmentNumber,
         "student" => $student
      ]);
   }

   public function getAssignment($student, $courseId, $assignmentNumber)
   {
      return $this->where("course_id = $courseId AND assignment_number = $assignmentNumber AND student = '$student'")
         ->misc("LIMIT 1")
         ->read("*");
   }

   public function updateRating($courseId, $assignmentNumber, $student, $rating)
   {
      return $this
         ->where("course_id = $courseId AND assignment_number = $assignmentNumber AND student = '$student'")
         ->update([
            'rating' => $rating
         ]);
   }

   public function getAssignmentsForStudent($student, $courseId)
   {
      return $this->where("course_id = $courseId AND student = '$student'")
         ->read("id, assignment_number, rating");
   }

   public function getUnratedAssignments($courseId, $assignmentNumber)
   {
      return $this->where("course_id = $courseId AND assignment_number = $assignmentNumber AND rating = 0")
         ->read("*");
   }

   public function getRatedAssignments($courseId, $assignmentNumber)
   {
      return $this->where("course_id = $courseId AND assignment_number = $assignmentNumber")
         ->read("*");
   }
}
