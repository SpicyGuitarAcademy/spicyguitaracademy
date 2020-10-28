<?php
namespace Models;
use Framework\Database\Model;

class AssignmentModel extends Model
{
   public function __construct()
   {
      parent::__construct('assignment_tbl');
   }

   // write wonderful model codes...

   public function getAssignment($courseId) {
      return $this->where("course_id = $courseId")->misc("LIMIT 1")->read("*");
   }

   public function addAssignment($courseId, $note, $video, $tutor) {
      return $this->create([
         "course_id" => $courseId,
         "tutor_id" => $tutor,
         "note" => $note,
         "video" => $video
      ]);
   }

   public function updateAssignmentNote($courseId, $note) {
      return $this->where("course_id = $courseId")->update([
         "note" => $note
      ]);
   }
   
   public function updateAssignmentVideo($courseId, $video) {
      return $this->where("course_id = $courseId")->update([
         "video" => $video
      ]);
   }

   public function removeAssignment($courseId) {
      return $this->where("course_id = $courseId")->delete();
   }
}
