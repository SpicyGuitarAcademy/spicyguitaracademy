<?php
namespace Models;
use Framework\Database\Model;

class QuickLessonModel extends Model
{
   public function __construct()
   {
      parent::__construct('quick_lessons_tbl');
   }

   // write wonderful model codes...

   public function getQLessons() {
      return $this->where("quick_lessons_tbl.status = 1 AND quick_lessons_tbl.lesson_id = lesson_tbl.id")->readJoin("quick_lessons_tbl, lesson_tbl", "quick_lessons_tbl.*, lesson_tbl.lesson");
   }

   public function getFLessons() {
      return $this->where("quick_lessons_tbl.free = 1 AND quick_lessons_tbl.lesson_id = lesson_tbl.id")->readJoin("quick_lessons_tbl, lesson_tbl", "quick_lessons_tbl.*, lesson_tbl.lesson");
   }

   public function getQLesson($id) {
      return $this->where("lesson_id = $id")->misc("LIMIT 1")->read("price, status, free");
   }

   public function addQLesson($lessonId) {
      return $this->create([
         "lesson_id"=>$lessonId
      ]);
   }

   public function updateFeatured($lessonId, $status, $price, $free) {
      $this->where("lesson_id = $lessonId")->update([
         "price"=>$price,
         "status"=>$status,
         "free"=>$free
      ]);
   }

   public function updateFree($free) {

   }

   public function removeQuickLesson($lessonId) {
      return $this->where("lesson_id = $id")->delete();
   }

}
