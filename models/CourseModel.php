<?php
namespace Models;
use Framework\Database\Model;

class CourseModel extends Model
{
   public function __construct()
   {
      parent::__construct('course_tbl');
   }

   // write wonderful model codes...

   public function getCourses()
   {
      return $this->where("active = true")->read("*");
   }

   public function addCourse($category, $course, $description, $thumbnail, $tutor, $order)
   {
      $add = $this->create([
         'category' => $category,
         'course' => $course,
         'description' => $description,
         'thumbnail' => $thumbnail,
         'tutor' => $tutor,
         'ord' => $order
      ]);

      if ($add == true) {
         return $this->lastId();
      } else {
         return false;
      }
   }

   public function updateCourse($id, $category, $course, $description, $tutor, $order)
   {
      return $this->where("id = $id")->update([
         'category' => $category,
         'course' => $course,
         'description' => $description,
         'tutor' => $tutor,
         'ord' => $order
      ]);
   }

   public function updateThumbnail($id, $thumbnail)
   {
      return $this->where("id = $id")->update([
         'thumbnail' => $thumbnail
      ]);
   }

   public function getCourse(int $id)
   {
      return $this->where("id = $id AND active = true")->read("*");
   }

   public function getCoursesByCategory(int $category)
   {
      return $this->where("category = $category AND active = true")->misc("ORDER BY ord")->read("*");
   }

   public function removeCourse(int $id)
   {
      // return $this->where("id = $id")->delete();
      return $this->where("id = $id")->update([
         'active' => false
      ]);
   }

   public function search($query)
   {
      return $this->where("course LIKE '%$query%' OR description LIKE '%$query%'")->read("*");
   }

}
