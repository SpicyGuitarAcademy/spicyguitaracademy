<?php

namespace Models;

use Framework\Database\Model;

class LessonModel extends Model
{
   public function __construct()
   {
      parent::__construct('lesson_tbl');
   }

   // write wonderful model codes...

   public function getLessons()
   {
      return $this->where("active = true")->read("*");
   }

   public function getLimitedLessons()
   {
      return $this->where("active = true")->misc("LIMIT 400")->read("*");
   }

   public function getFreeLessons()
   {
      return $this->where("free = true AND active = true ORDER BY free_order ASC")->read("*");
   }

   public function updateFreeLessonOrder($id, $order)
   {
      return $this->where("id = $id")->update([
         'free_order' => $order
      ]);
   }

   public function getLesson($id)
   {
      return $this->where("id = $id AND active = true")->read("*");
   }

   public function addLesson($course, $lesson, $description, $order, $thumbnail, $tutor, $free)
   {
      $add = $this->create([
         'course' => $course,
         'lesson' => $lesson,
         'description' => $description,
         'ord' => $order,
         'thumbnail' => $thumbnail,
         'tutor' => $tutor,
         'free' => $free
      ]);

      if ($add == true) {
         return $this->lastId();
      } else {
         return false;
      }
   }

   public function updateLesson($id, $course, $lesson, $description, $order, $tutor, $free)
   {
      return $this->where("id = $id")->update([
         'course' => $course,
         'lesson' => $lesson,
         'description' => $description,
         'ord' => $order,
         'tutor' => $tutor,
         'free' => $free
      ]);
   }

   public function updateThumbnail($id, $thumbnail)
   {
      return $this->where("id = $id")->update([
         'thumbnail' => $thumbnail
      ]);
   }

   public function updateHighVideo($id, $path)
   {
      return $this->where("id = $id")->update([
         'high_video' => $path
      ]);
   }

   public function updateLowVideo($id, $path)
   {
      return $this->where("id = $id")->update([
         'low_video' => $path
      ]);
   }

   public function updateAudio($id, $path)
   {
      return $this->where("id = $id")->update([
         'audio' => $path
      ]);
   }

   public function updatePractice($id, $path)
   {
      return $this->where("id = $id")->update([
         'practice_audio' => $path
      ]);
   }

   public function updateTablature($id, $path)
   {
      return $this->where("id = $id")->update([
         'tablature' => $path
      ]);
   }

   public function updateNote($id, $note)
   {
      return $this->where("id = $id")->update([
         'note' => $note
      ]);
   }

   public function getLessonsByCourse(int $course)
   {
      return $this->where("course = $course AND active = true ORDER BY ord")->read("*");
   }

   public function removeLesson(int $id)
   {
      // return $this->where("id == $id")->delete();
      return $this->where("id = $id")->update([
         'active' => false
      ]);
   }

   public function removeLessonByCourse(int $id)
   {
      return $this->where("course = $id")->update([
         'active' => false
      ]);
   }
}
