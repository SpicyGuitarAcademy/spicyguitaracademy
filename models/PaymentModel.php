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

   public function addPayment(string $reference, int $amount, string $paymentMedium)
   {
      $add = $this->create([
         "payment_medium_id" => "",
         "reference" => $reference,
         "amount" => $amount,
         "payment_medium" => $paymentMedium,
         "payment_status" => "pending",
      ]);

      if ($add == true) {
         return $this->lastId();
      } else {
         return 0;
      }
   }

   public function updatePaymentRecordForPaymentWithPaystack(int $paymentId, $id, $domain, $reference, $product, $amount, $currency, $channel, $ipAddress, $log, $status, $gatewayResponse, $message, $createdAt, $paidAt)
   {
      return $this->where("payment_id = $paymentId")
         ->update([
            "payment_medium_id" => $id,
            "domain" => $domain,
            "reference" => $reference,
            "product" => $product,
            "amount" => $amount,
            "currency" => $currency,
            "payment_channel" => $channel,
            "ip_address" => $ipAddress,
            "payment_log" => $log,
            "payment_status" => $status,
            "gateway_response" => $gatewayResponse,
            "message" => $message,
            "created_at" => $createdAt,
            "paid_at" => $paidAt,
         ]);
   }

   // TODO: UPDATE SQL
   // ALTER TABLE `payment_tbl` CHANGE `paystack_ref_id` `paystack_medium_id` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
   // UPDATE `payment_tbl` SET payment_medium = 'Paystack' WHERE payment_medium = 'Mobile App';
   // ALTER TABLE `payment_tbl` CHANGE `payment_channel` `payment_channel` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'card, bank, ussd, paypal etc', CHANGE `payment_medium` `payment_medium` VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Paystack, Paypal';
   // ALTER TABLE `payment_tbl` CHANGE `paystack_medium_id` `payment_medium_id` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

   public function updatePaymentRecordForPaymentWithPayPal($id, $domain, $reference, $product, $amount, $currency, $channel, $ipAddress, $log, $status, $gatewayResponse, $message, $createdAt, $paidAt)
   {
      return $this->where("reference = '$reference'")
         ->update([
            "payment_medium_id" => $id,
            "domain" => $domain,
            "reference" => $reference,
            "product" => $product,
            "amount" => $amount,
            "currency" => $currency,
            "payment_channel" => $channel,
            "ip_address" => $ipAddress,
            "payment_log" => $log,
            "payment_status" => $status,
            "gateway_response" => $gatewayResponse,
            "message" => $message,
            "created_at" => $createdAt,
            "paid_at" => $paidAt,
         ]);
   }

   public function updateResponseDetailsFromPaymentGateway(string $transcRef, string $respCode, string $respMsg, string $payRef)
   {
      return $this->where("transc_ref = '$transcRef'")
         ->update([
            "resp_code" => $respCode,
            "resp_msg" => $respMsg,
            "pay_ref" => $payRef
         ]);
   }
}
