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
            'rating' => $rating,
            'status' => 'reviewed'
         ]);
   }

   public function updateStatus($courseId, $assignmentNumber, $student, $status)
   {
      return $this
         ->where("course_id = $courseId AND assignment_number = $assignmentNumber AND student = '$student'")
         ->update([
            'status' => $status
         ]);
   }

   public function courseAssignmentIsPending($courseId, $student)
   {
      return $this
         ->where("course_id = $courseId AND student = '$student' AND status = 'pending'")
         ->exist();
   }

   public function courseAssignmentIsAnswered($courseId, $student)
   {
      return $this
         ->where("course_id = $courseId AND student = '$student' AND status = 'answered'")
         ->exist();
   }

   public function courseAssignmentIsReviewed($courseId, $student)
   {
      return $this
         ->where("course_id = $courseId AND student = '$student' AND status = 'reviewed'")
         ->exist();
   }

   public function getAssignmentsForStudent($student, $courseId)
   {
      return $this->where("course_id = $courseId AND student = '$student'")
         ->read("id, assignment_number, rating");
   }

   public function getUnreviewedAssignments($courseId, $assignmentNumber)
   {
      return $this->where("course_id = $courseId AND assignment_number = $assignmentNumber AND status = 'answered'")
         ->read("*");
   }

   public function getReviewedAssignments($courseId, $assignmentNumber)
   {
      return $this->where("course_id = $courseId AND assignment_number = $assignmentNumber and status = 'reviewed'")
         ->read("*");
   }
}
