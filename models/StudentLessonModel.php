<?php
namespace Models;
use Framework\Database\Model;

class StudentLessonModel extends Model
{
   public function __construct()
   {
      parent::__construct('student_lesson_tbl');
   }

   // write wonderful model codes...

   public function addLessonForStudent($lessonId, $email, $medium, $courseId) {

      return $this->create([
         "course_id" => $courseId,
         "lesson_id" => $lessonId,
         "student_id" => $email,
         "medium" => $medium,
         "status" => 1
      ]);

   }

   public function getNextLesson($email, $currentcourse, $currentlesson) {
      return $this->where("course_id = $currentcourse AND lesson_id > $currentlesson AND student_id = '$email' AND (status = 0 OR status = 1) AND medium = 'NORMAL'")->misc("LIMIT 1")->read("lesson_id");
   }

   public function getPreviousLesson($email, $currentcourse, $currentlesson) {
      return $this->where("course_id = $currentcourse AND lesson_id < $currentlesson AND student_id = '$email' AND (status = 0 OR status = 1) AND medium = 'NORMAL'")->misc("LIMIT 1")->read("lesson_id");
   }

   public function updateLessonStatus($lessonId, $email, $status) {
      return $this->where("lesson_id = $lessonId AND student_id = '$email' AND medium = 'NORMAL' AND status = 0")->update([
         'status' => $status
      ]);
   }

   public function getStats($email, $course) {
      return [
         $this->where("student_id = '$email' AND course_id = '$course' AND status = 1 AND medium = 'NORMAL'")->read("COUNT(*) as done")[0]['done'],
         $this->where("student_id = '$email' AND course_id = '$course' AND medium = 'NORMAL'")->read("COUNT(*) as total")[0]['total']
      ];
   }
   
   public function countNormalLessonsTakenByStudent($email, $course) {
       return $this->where("student_id = '$email' AND course_id = '$course' AND medium = 'NORMAL'")->read("COUNT(*) as count")[0]['count'];
   }

}
