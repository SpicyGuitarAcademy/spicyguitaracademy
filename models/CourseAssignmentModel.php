<?php

namespace Models;

use Framework\Database\Model;

class CourseAssignmentModel extends Model
{
   public function __construct()
   {
      parent::__construct('course_assignment');
   }

   public function getAssignments($courseId)
   {
      return $this->where("course_id = $courseId")
         ->misc("ORDER BY assignment_number ASC, assignment_order ASC")
         ->read("*");
   }

   public function getAssignmentNumbers($courseId)
   {
      return $this->where("course_id = $courseId")
         ->misc("GROUP BY assignment_number ORDER BY assignment_number ASC")
         ->read("assignment_number");
   }

   public function getAssignmentQuestions($courseId, $assignmentNumber)
   {
      return $this->where("course_id = $courseId AND assignment_number = $assignmentNumber")
         ->misc("ORDER BY assignment_number ASC, assignment_order ASC")
         ->read("*");
   }

   public function getAssignment($assignmentId)
   {
      return $this->where("id = $assignmentId")
         ->misc("LIMIT 1")
         ->read("*");
   }

   public function addAssignment($courseId, $assignmentNumber, $assignmentOrder, $type, $content)
   {
      return $this->create([
         "course_id" => $courseId,
         "assignment_number" => $assignmentNumber,
         "assignment_order" => $assignmentOrder,
         "type" => $type,
         "content" => $content
      ]);
   }

   public function removeAssignment($assignmentId)
   {
      return $this->where("id = $assignmentId")->delete();
   }
}
