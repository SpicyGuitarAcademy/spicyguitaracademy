<?php

namespace Controllers;

use Framework\Http\Http;
use Framework\Http\Request;
use Framework\Http\Response;
use App\Services\Auth;
use App\Services\User;
use App\Services\Validate;
use App\Services\Sanitize;
use App\Services\Paystack;
use Framework\Cipher\Encrypt;
use Models\StudentSubscriptionModel;
use Models\SubscriptionModel;
use Models\StudentCourseModel;
use Models\CourseModel;
use Models\TransactionModel;
use Models\PaymentModel;

class SubscriptionController
{

   public function subscriptions(Request $req, Response $res)
   {
      $Subscription = new SubscriptionModel();
      $subscriptions = $Subscription->read("*");

      $res->send(
         $res->render('admin/subscriptions.html', [
            "plans" => json_encode($subscriptions)
         ])
      );
   }

   public function updateprice(Request $req, Response $res)
   {
      $price = $req->body()->price;
      $id = $req->body()->id;

      $v = new Validate();
      $v->amount("Price", $price, "Invalid subscription price");
      $errors = $v->errors();
      if (!$errors) {
         $Subscription = new SubscriptionModel();
         if ($Subscription->where("plan_id = '$id'")->update([
            "price" => $price
         ])) {
            $res->send(
               $res->json([
                  'status' => true,
                  'message' => 'Subscription price updated'
               ])
            );
         } else {
            $res->send(
               $res->json([
                  'status' => false,
                  'message' => 'Subscription price not updated'
               ])
            );
         }
      } else {
         $res->send(
            $res->json([
               'status' => false,
               'message' => implode(", ", $errors)
            ])
         );
      }
   }

   public function updatedescription(Request $req, Response $res)
   {
      $description = $req->body()->description;
      $id = $req->body()->id;

      $v = new Validate();
      $v->any("Description", $description)->max(250);
      $errors = $v->errors();
      if (!$errors) {
         $description = (new Sanitize())->string($description);
         $Subscription = new SubscriptionModel();
         if ($Subscription->where("plan_id = '$id'")->update([
            "description" => $description
         ])) {
            $res->send(
               $res->json([
                  'status' => true,
                  'message' => 'Subscription description updated'
               ])
            );
         } else {
            $res->send(
               $res->json([
                  'status' => false,
                  'message' => 'Subscription description not updated'
               ])
            );
         }
      } else {
         $res->send(
            $res->json([
               'status' => false,
               'message' => implode(", ", $errors)
            ])
         );
      }
   }

   public function status(Request $req, Response $res)
   {
      // temporary
      $email = User::$email;

      $studentSubMdl = new StudentSubscriptionModel();
      $sub = $studentSubMdl->getStudentSubscriptionStatus($email)[0] ?? null;

      if (!is_null($sub)) {

         $id = $sub['id'];
         $today = new \DateTime(date("Y-m-d"));
         $expire = new \DateTime(date("Y-m-d", strtotime($sub['sub_expire'])));
         $diff = $today->diff($expire);

         if ($today > $expire) {
            $studentSubMdl->update(['status' => 'EXPIRED'], "WHERE id = '$id'");
            $res->error('Subscription expired', [
               'status' => 'INACTIVE',
               'days' => 0,
               "plan" => $sub['plan']
            ]);
         } else {
            $res->success('Subscription is active', [
               "status" => "ACTIVE",
               "days" => $diff->days,
               "plan" => $sub['plan']
            ]);
         }
      } else {
         $res->error('Subscription expired', [
            'status' => 'INACTIVE',
            'days' => 0,
            'plan' => $sub['plan']
         ]);
      }
   }

   public function initiatePayment(Request $req, Response $res)
   {
      $email = $req->body()->email ?? null;
      $plan = $req->body()->plan ?? null;

      $v = new Validate();
      $v->numbers('Plan', $plan, 'Invalid Plan')->minvalue(0)->maxvalue(4);

      if (!$v->errors()) {
         $mdl = new SubscriptionModel();

         $sub = $mdl->getThisSubscription($plan)[0];
         $price = $sub['price'] ?? 0;
         $title = $sub['plan'];

         // prepare payment parameters
         $amountInKobo = $price * 100;
         $reference = $this->generateTxnref("N$plan");
         $displayName = $title . ' Subscription Plan';
         $variableName = "Subscription Plan";
         $variableValue = $title;
         $paymentMedium = "Mobile App";
         $paymentId = 0;
         $callback = SERVER . '/api/subscription/verify';

         // add payment record
         $mdl = new PaymentModel();
         $add = $mdl->addPayment($reference, $amountInKobo, $paymentMedium);
         if ($add > 0) {
            // last added Id of payment
            $paymentId = $add;

            // initiate payment with paystack
            $paystack = new Paystack();
            $init = $paystack->initiatePayment($paymentId, $displayName, $variableName, $variableValue, $paymentMedium, $email, $amountInKobo, $callback, $reference);

            if ($init['flag'] == true) {
               $init['data']['price'] = $amountInKobo;
               $res->success('Payment intialized', $init['data']);
            } else {
               $res->error('Error', $init);
            }
         } else {
            $res->error('Payment was not added');
         }
      } else {
         $res->error('Invalid plan');
      }
   }

   public function initiateFeaturedPayment(Request $req, Response $res)
   {
      $email = $req->body()->email ?? null;
      $courseId = $req->body()->course ?? null;

      $v = new Validate();
      $v->numbers('Plan', $courseId, 'Invalid Course')->minvalue(1);

      if (!$v->errors()) {
         $mdl = new CourseModel();

         $course = $mdl->getCourse($courseId)[0];
         $price = $course['featuredprice'] ?? 0;
         $title = $course['course'];

         // prepare payment parameters
         $amountInKobo = $price * 100;
         $reference = $this->generateTxnref("Q$courseId");
         $displayName = $title . ' (Featured Course)';
         $variableName = "Featured Course";
         $variableValue = $courseId;
         $paymentMedium = "Mobile App";
         $paymentId = 0;
         $callback = SERVER . '/api/subscription/verify-featured';

         // add payment record
         $mdl = new PaymentModel();
         $add = $mdl->addPayment($reference, $amountInKobo, $paymentMedium);
         if ($add > 0) {
            // last added Id of payment
            $paymentId = $add;

            // initiate payment with paystack
            $paystack = new Paystack();
            $init = $paystack->initiatePayment($paymentId, $displayName, $variableName, $variableValue, $paymentMedium, $email, $amountInKobo, $callback, $reference);

            if ($init['flag'] == true) {
               $init['data']['price'] = $amountInKobo;
               $res->success('Payment intialized', $init['data']);
            } else {
               $res->error('Error', $init);
            }
         } else {
            $res->error('Payment was not added');
         }
      } else {
         $res->error('Invalid plan');
      }
   }

   public function verifyPayment(Request $req, Response $res)
   {
      $email = $req->body()->email;
      $txnref = $req->params()->reference ?? null;

      User::$email = $email; // Temporary

      if ($txnref != null) {

         $paystack = new Paystack();
         $verification = $paystack->verifyPayment($txnref);

         if ($verification['flag'] == true) {

            $details = $verification['data'];

            // retrieving payment details
            $paymentId = $details['metadata']['payment_id'];
            $id = $details['id'];
            $domain = $details['domain'];
            $reference = $details['reference'];
            $product = json_encode($details['metadata']['custom_fields'][0]);
            $amount = $details['amount'];
            $currency = $details['currency'];
            $channel = $details['channel'];
            $ipAddress = $details['ip_address'];
            $paymentMedium = $details['metadata']['payment_medium'];
            $log = json_encode($details['log']);
            $status = $details['status'];
            $gatewayResponse = $details['gateway_response'];
            $message = $details['message'];
            $createdAt = $details['created_at'];
            $paidAt = $details['paid_at'];

            // update payment log
            $mdl = new PaymentModel();
            $mdl->updatePaymentRecord($paymentId, $id, $domain, $reference, $product, $amount, $currency, $channel, $ipAddress, $paymentMedium, $log, $status, $gatewayResponse, $message, $createdAt, $paidAt);

            // prepare details
            $amountInNaira = $amount / 100;
            $plan = $details['metadata']['custom_fields'][0]['value'];

            $start = date("Y-m-d H:i:s");
            // generate expiry date
            switch ($plan) {
               case '1 Month':
                  $plan = 1;
                  $end = '1 Month';
                  break;

               case '3 Months':
                  $plan = 2;
                  $end = '3 Months';
                  break;

               case '6 Months':
                  $plan = 3;
                  $end = '6 Months';
                  break;

               case '12 Months':
                  $plan = 4;
                  $end = '12 Months';
                  break;

               default:
                  $plan = 0;
                  break;
            }
            $date = date_create();
            date_add($date, date_interval_create_from_date_string($end));
            $end = date_format($date, "Y-m-d H:i:s");

            // add transaction
            $mdl = new TransactionModel();

            if ($mdl->addTransaction(User::$email, $reference, $amountInNaira, date("Y-m-d"), date("H:i:s"), $status) == true) {

               // add to student subscription table
               $mdl = new StudentSubscriptionModel();
               $mdl->addStudentSubscription(User::$email, $reference, $plan, 0, $start, $end);

               $res->success('Subscription completed');
            } else {
               $res->error('Incomplete transaction');
            }
         } else {
            $res->error('Verification failed');
         }
      } else {
         $res->error('No reference');
      }
   }

   public function verifyFeaturedPayment(Request $req, Response $res)
   {
      $email = $req->body()->email;
      $txnref = $req->params()->reference ?? null;

      User::$email = $email; // Temporary

      if ($txnref != null) {

         $paystack = new Paystack();
         $verification = $paystack->verifyPayment($txnref);

         if ($verification['flag'] == true) {

            $details = $verification['data'];

            // retrieving payment details
            $paymentId = $details['metadata']['payment_id'];
            $id = $details['id'];
            $domain = $details['domain'];
            $reference = $details['reference'];
            $product = json_encode($details['metadata']['custom_fields'][0]);
            $amount = $details['amount'];
            $currency = $details['currency'];
            $channel = $details['channel'];
            $ipAddress = $details['ip_address'];
            $paymentMedium = $details['metadata']['payment_medium'];
            $log = json_encode($details['log']);
            $status = $details['status'];
            $gatewayResponse = $details['gateway_response'];
            $message = $details['message'];
            $createdAt = $details['created_at'];
            $paidAt = $details['paid_at'];

            // update payment log
            $mdl = new PaymentModel();
            $mdl->updatePaymentRecord($paymentId, $id, $domain, $reference, $product, $amount, $currency, $channel, $ipAddress, $paymentMedium, $log, $status, $gatewayResponse, $message, $createdAt, $paidAt);

            // prepare details
            $amountInNaira = $amount / 100;
            $courseId = $details['metadata']['custom_fields'][0]['value'];

            // add transaction
            $mdl = new TransactionModel();

            if ($mdl->addTransaction(User::$email, $reference, $amountInNaira, date("Y-m-d"), date("H:i:s"), $status) == true) {

               $cmdl = new CourseModel();
               $categoryId = $cmdl->getCourse($courseId)[0]['category'] ?? 0;

               // add the course to the student courses table
               $studentCourseMdl = new StudentCourseModel();

               $studentCourseMdl->addCourseForStudent($categoryId, $courseId, User::$email, "FEATURED");
               $res->success('Subscription completed');
            } else {
               $res->error('Incomplete transaction');
            }
         } else {
            $res->error('Verification failed');
         }
      } else {
         $res->error('No reference');
      }
   }


   private function generateTxnref(string $desc)
   {
      return "SGA.$desc." . Encrypt::token(8);
   }

   public function plans(Request $req, Response $res)
   {
      $mdl = new SubscriptionModel();
      $plans = $mdl->getSubscriptions();

      $res->success('Subscription plans', $plans);
      //   $res->send(
      //      $res->json(['plans'=>$plans])
      //   );

   }
}
