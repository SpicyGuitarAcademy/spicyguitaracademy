<?php

namespace Models;

use Framework\Database\Model;

class ForumsModel extends Model
{
   public function __construct()
   {
      parent::__construct('forums');
   }

   // write wonderful model codes...

   public function addMessage($categoryId, $comment, $sender, $replyId = 0, $isAdmin = 0)
   {
      return $this->create([
         "category_id" => $categoryId,
         "sender" => $sender,
         "reply_id" => $replyId,
         "comment" => $comment,
         "is_admin" => $isAdmin
      ]);
   }

   public function getMessage($id)
   {
      return $this->where("id = $id")->read("id, sender, reply_id, comment, is_admin, DATE_FORMAT(date_added,'%d/%m/%y %l:%i %p') as date_added ");
   }

   public function getMessages($categoryId)
   {
      return $this->where("category_id = $categoryId")->read("id, sender, reply_id, comment, is_admin, DATE_FORMAT(date_added,'%d/%m/%y %l:%i %p') as date_added ");
   }

   public function getAllCommentsFromStudentForMe($email, $student)
   {
      return $this->custom("SELECT lesson_id, sender, receiver, comment, DATE_FORMAT(date_added,'%d/%m/%y %l:%i %p') as date_added FROM studentcomments WHERE (sender = '$email' OR receiver = '$student') OR (sender = '$student' OR receiver = '$email') ORDER BY lesson_id", true);
   }
}
