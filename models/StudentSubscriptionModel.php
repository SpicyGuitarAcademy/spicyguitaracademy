<?php

namespace Models;

use Framework\Database\Model;

class StudentSubscriptionModel extends Model
{
   public function __construct()
   {
      parent::__construct('student_subscription_tbl');
   }

   // write wonderful model codes...

   public function getStudentActiveSubscriptionStatus($email)
   {
      return $this->where("student_id = '$email' AND plan > 0 AND status = 'ACTIVE'")->misc("LIMIT 1")->read("*");
   }

   public function getStudentActiveOrExpiredSubscriptionStatus($email)
   {
      return $this->where("student_id = '$email' AND plan > 0")->misc("LIMIT 1")->read("*");
   }

   public function expireStudentSubscription($email)
   {
      return $this->where("student_is = '$email' AND plan > 0 AND status = 'ACTIVE'")->update([
         'status' => 'EXPIRED'
      ]);
   }

   public function listStudentSubscriptionHistory($email)
   {
      return $this->where("student_id = '$email'")->read("*, DATE_FORMAT(sub_date,'%d/%m/%y %l:%i %p') as sub_date, DATE_FORMAT(sub_expire,'%d/%m/%y %l:%i %p') as sub_expire");
   }


   public function getSubscribedQuickLessons($email)
   {
      return $this->where("student_id = '$email' AND plan = 0")->read("*");
   }

   public function getSubscribedQuickLesson($email, $lesson)
   {
      return $this->where("student_id = '$email' AND plan = 0 AND quicklesson_id = $lesson")->misc("LIMIT 1")->read("*");
   }

   public function addStudentSubscription($email, $txnref, $plan, $qlesson_id, $start, $end)
   {
      return $this->create([
         "student_id" => $email,
         "txnref" => $txnref,
         "plan" => $plan,
         "quicklesson_id" => $qlesson_id,
         "sub_date" => $start,
         "sub_expire" => $end
      ]);
   }
}
