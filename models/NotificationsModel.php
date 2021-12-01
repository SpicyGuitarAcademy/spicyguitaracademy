<?php

namespace Models;

use Framework\Database\Model;

class NotificationsModel extends Model
{
   public function __construct()
   {
      parent::__construct('notifications');
   }

   // write wonderful model codes...

   public function getNotifications($email)
   {
      return $this->where("email = '$email' AND status = 'unread'")
         ->misc("ORDER BY created_at DESC")
         ->read("*");
   }

   public function getAdminNotifications($email)
   {
      return $this->where("email = '$email' OR email = 'admin' AND status = 'unread'")
         ->misc('ORDER BY created_at DESC')
         ->read("*");
   }

   public function addNotification($email, $message, $route = '')
   {
      return $this->create([
         'email' => $email,
         'message' => $message,
         'route' => $route
      ]);
   }

   public function updateNotificationStatus($email, $id, $status = 'read')
   {
      return $this->where("email = '$email' AND id = $id")->update([
         'status' => $status
      ]);
   }
}
