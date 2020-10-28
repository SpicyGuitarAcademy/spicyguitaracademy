<?php
namespace Models;
use Framework\Database\Model;

class PaymentModel extends Model
{
   public function __construct()
   {
      parent::__construct('payment_tbl');
   }

   // write wonderful model codes...

   public function addPayment(string $reference, int $amount, string $paymentMedium) {
      $add = $this->create([
         "paystack_ref_id"=>"",
         "reference"=>$reference,
         "amount"=>$amount,
         "payment_medium"=>$paymentMedium,
         "payment_status"=>"pending",
      ]);

      if ($add == true) {
         return $this->lastId();
      } else {
         return 0;
      }
   }

   public function updatePaymentRecord(int $paymentId,$id,$domain,$reference,$product,$amount,$currency,$channel,$ipAddress,$paymentMedium,$log,$status,$gatewayResponse,$message,$createdAt,$paidAt) {
      return $this->where("payment_id = $paymentId")
      ->update([
         "paystack_ref_id"=>$id,
         "domain"=>$domain,
         "reference"=>$reference,
         "product"=>$product,
         "amount"=>$amount,
         "currency"=>$currency,
         "payment_channel"=>$channel,
         "ip_address"=>$ipAddress,
         "payment_medium"=>$paymentMedium,
         "payment_log"=>$log,
         "payment_status"=>$status,
         "gateway_response"=>$gatewayResponse,
         "message"=>$message,
         "created_at"=>$createdAt,
         "paid_at"=>$paidAt,
      ]);
   }

   public function updateResponseDetailsFromPaymentGateway(string $transcRef, string $respCode, string $respMsg, string $payRef) {
      return $this->where("transc_ref = '$transcRef'")
      ->update([
         "resp_code"=>$respCode,
         "resp_msg"=>$respMsg,
         "pay_ref"=>$payRef
      ]);
   }

}
