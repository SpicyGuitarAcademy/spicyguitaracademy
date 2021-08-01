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
   public function getAnswers($courseId)
   {
      return $this->where("course_id = $courseId AND status = 1 AND rating < 3")->read("*");
   }

   public function addAssignmentForStudent($studentId, $courseId, $assignmentId, $tutorId)
   {
      return $this->create([
         "student_id" => $studentId,
         "course_id" => $courseId,
         "assignment_id" => $assignmentId,
         "tutor_id" => $tutorId
      ]);
   }

   public function getAvailableAssignment($email, $course)
   {
      return $this->custom("SELECT a.note as questionNote, a.video as questionVideo, b.note as answerNote, b.video as answerVideo, b.review, b.rating, b.date_added as answerDate, a.id, b.id as answerId, b.status, b.tutor_id, CONCAT(c.firstname, ' ', c.lastname) as tutor FROM assignment_tbl a, student_assignment_tbl b, admin_tbl c WHERE b.assignment_id = a.id AND b.student_id = '$email' AND b.course_id = $course AND b.tutor_id = c.id LIMIT 1", true);
   }

   public function answerAsVideo($email, $assignmentId, $answerId, $path)
   {
      return $this->where("student_id = '$email' AND assignment_id = $assignmentId AND id = $answerId")
         ->update([
            'video' => $path,
            'status' => 1
         ]);
   }

   public function answerAsNote($email, $assignmentId, $answerId, $note)
   {
      return $this->where("student_id = '$email' AND assignment_id = $assignmentId AND id = $answerId")
         ->update([
            'note' => $note,
            'status' => 1,
            'rating' => 0
         ]);
   }

   public function updateRating($answerId, $review, $rating)
   {
      return $this->where("id = $answerId")
         ->update([
            'review' => $review,
            'rating' => $rating,
            'status' => ($rating >= 3) ? 1 : 0
         ]);
   }
}
