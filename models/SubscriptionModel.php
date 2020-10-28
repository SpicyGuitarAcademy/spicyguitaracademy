<?php
namespace Models;
use Framework\Database\Model;

class SubscriptionModel extends Model
{
   public function __construct()
   {
      parent::__construct('subscription_tbl');
   }

   // write wonderful model codes...
   public function getSubscriptions() {
      return $this->read("*");
   }

   public function getThisSubscription(int $plan)
   {
      return $this->where("plan_id = $plan")->read('*');
   }

}
