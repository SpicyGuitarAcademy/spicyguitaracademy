<?php

namespace Models;

use Framework\Database\Model;

class StudentCommentsModel extends Model
{
   public function __construct()
   {
      parent::__construct('studentcomments');
   }

   // write wonderful model codes...

   public function addComment($lessonId, $comment, $sender, $receiver = '')
   {

      return $this->create([
         "lesson_id" => $lessonId,
         "sender" => $sender,
         "receiver" => $receiver,
         "comment" => $comment
      ]);
   }

   public function getCommentsForAdmin($lessonId, $email)
   {
      return $this->where("lesson_id = $lessonId AND (sender = '$email' OR receiver = '$email')")->read("sender, receiver, comment, DATE_FORMAT(date_added,'%d/%m/%y %l:%i %p') as date_added ");
   }

   public function getComments($lessonId, $email)
   {
      return $this->where("lesson_id = $lessonId AND (sender = '$email' OR receiver = '$email')")->read("sender, receiver, comment, DATE_FORMAT(date_added,'%d/%m/%y %l:%i %p') as date_added ");
   }

   public function getCommentGroup()
   {
      return $this->misc("GROUP BY lesson_id ORDER BY id DESC")->read("sender, lesson_id");
   }

   public function getAllCommentsFromStudentForMe($email, $student)
   {
      //   return $this->where("(sender = '$email' OR receiver = '$student') OR (sender = '$student' OR receiver = '$email')")->read("lesson_id, sender, receiver, comment, DATE_FORMAT(date_added,'%d/%m/%y %l:%i %p') as date_added");

      return $this->custom("SELECT lesson_id, sender, receiver, comment, DATE_FORMAT(date_added,'%d/%m/%y %l:%i %p') as date_added FROM studentcomments WHERE (sender = '$email' OR receiver = '$student') OR (sender = '$student' OR receiver = '$email') ORDER BY lesson_id", true);

      // return $this->where("sender = '$student'")->read("sender, receiver, comment, DATE_FORMAT(date_added,'%d/%m/%y %l:%i %p') as date_added");
   }
}
