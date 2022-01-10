<?php

namespace Controllers;

use App\Services\CurrencyConverter;
use Framework\Http\Http;
use Framework\Http\Request;
use Framework\Http\Response;
use App\Services\PayPalClient;
use App\Services\User;
use App\Services\Validate;
use App\Services\Sanitize;
use App\Services\PaystackClient;
use Framework\Cipher\Encrypt;
use Framework\Mail\Mail;
use Models\StudentSubscriptionModel;
use Models\SubscriptionModel;
use Models\StudentCourseModel;
use Models\CourseModel;
use Models\NotificationsModel;
use Models\TransactionModel;
use Models\PaymentModel;
use Models\StudentModel;

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
      $sub = $studentSubMdl->getStudentActiveSubscriptionStatus($email)[0] ?? null;

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
      $medium = $req->params()->medium ?? null;

      $v = new Validate();
      $v->numbers('Plan', $plan, 'Invalid Plan')->minvalue(0)->maxvalue(4);

      if (!$v->errors()) {
         $mdl = new SubscriptionModel();

         $sub = $mdl->getThisSubscription($plan)[0];
         $price = $sub['price'] ?? 0;
         $title = $sub['plan'];

         // continue payment initiation based on payment medium

         if ($medium == 'paystack') {

            // prepare payment parameters
            $amountInKobo = $price * 100;
            $reference = $this->generateTxnref("N$plan");
            $displayName = $title . ' Subscription Plan';
            $variableName = "Subscription Plan";
            $variableValue = $title;
            $paymentMedium = "Paystack";
            $paymentId = 0;
            $callback = SERVER . '/api/subscription/paystack/verify';

            // add payment record
            $mdl = new PaymentModel();
            $add = $mdl->addPayment($reference, $amountInKobo, $paymentMedium);

            if ($add > 0) {
               // last added Id of payment
               $paymentId = $add;

               // initiate payment with paystack
               $init = PaystackClient::initiatePayment($paymentId, $displayName, $variableName, $variableValue, $paymentMedium, $email, $amountInKobo, $callback, $reference);

               if ($init['flag'] == true) {
                  $init['data']['price'] = $amountInKobo;
                  $res->success('Payment intialized', $init['data']);
               } else {
                  $res->error($init['error']);
               }
            } else {
               $res->error('Payment was not added');
            }
         } else if ($medium == 'paypal') {

            // TODO:
            $res->error('Unavailable for now, Please try again later.');
            exit;

            // conversion rate
            $conversion_rate = CurrencyConverter::getNairaToDollarRate();

            // prepare payment parameters
            $amountInDollar = ceil((float)$price / $conversion_rate);
            $reference = $this->generateTxnref("N$plan");
            $displayName = $title . ' Subscription Plan';
            $variableValue = $title;
            $paymentMedium = "PayPal";
            $paymentId = 0;

            // add payment record
            $mdl = new PaymentModel();
            $add = $mdl->addPayment($reference, $amountInDollar, $paymentMedium);

            if ($add > 0) {
               // last added Id of payment
               $paymentId = $add;

               // initiate payment with paystack
               $init = PayPalClient::initiatePayment($displayName, $amountInDollar, $reference, $variableValue);

               if ($init['statusCode'] == 201) {
                  $res->success('Payment intialized', $init['result']);
               } else {
                  $res->error($init['error']);
               }
            } else {
               $res->error('Payment was not added');
            }
         } else {
            $res->error('Invalid payment medium');
         }
      } else {
         $res->error('Invalid plan');
      }
   }

   public function initiateFeaturedPayment(Request $req, Response $res)
   {
      $email = $req->body()->email ?? null;
      $courseId = $req->body()->course ?? null;
      $medium = $req->params()->medium ?? null;

      $v = new Validate();
      $v->numbers('Plan', $courseId, 'Invalid Course')->minvalue(1);

      if (!$v->errors()) {
         $mdl = new CourseModel();

         $course = $mdl->getCourse($courseId)[0];
         $price = $course['featuredprice'] ?? 0;
         $title = $course['course'];


         // continue payment initiation based on payment medium

         if ($medium == 'paystack') {

            // prepare payment parameters
            $amountInKobo = $price * 100;
            $reference = $this->generateTxnref("Q$courseId");
            $displayName = $title . ' (Featured Course)';
            $variableName = "Featured Course";
            $variableValue = $courseId;
            $paymentMedium = "Paystack";
            $paymentId = 0;
            $callback = SERVER . '/api/subscription/paystack/verify-featured';

            // add payment record
            $mdl = new PaymentModel();
            $add = $mdl->addPayment($reference, $amountInKobo, $paymentMedium);
            if ($add > 0) {
               // last added Id of payment
               $paymentId = $add;

               // initiate payment with paystack
               $init = PaystackClient::initiatePayment($paymentId, $displayName, $variableName, $variableValue, $paymentMedium, $email, $amountInKobo, $callback, $reference);

               if ($init['flag'] == true) {
                  $init['data']['price'] = $amountInKobo;
                  $res->success('Payment intialized', $init['data']);
               } else {
                  $res->error($init['error']);
               }
            } else {
               $res->error('Payment was not added');
            }
         } else if ($medium == 'paypal') {

            // TODO
            $res->error('Unavailable for now, Please try again later.');
            exit;

            // conversion rate
            $conversion_rate = CurrencyConverter::getNairaToDollarRate();

            // prepare payment parameters
            $amountInDollar = ceil((float)$price / $conversion_rate);
            // exit($amountInDollar);
            $reference = $this->generateTxnref("Q$courseId");
            $displayName = $title . ' (Featured Course)';
            $variableValue = $courseId;
            $paymentMedium = "PayPal";
            $paymentId = 0;

            // add payment record
            $mdl = new PaymentModel();
            $add = $mdl->addPayment($reference, $amountInDollar, $paymentMedium);

            if ($add > 0) {
               // last added Id of payment
               $paymentId = $add;

               // initiate payment with paystack
               $init = PayPalClient::initiatePayment($displayName, $amountInDollar, $reference, $variableValue);

               if ($init['statusCode'] == 201) {
                  $res->success('Payment intialized', $init['result']);
               } else {
                  $res->error($init['error']);
               }
            } else {
               $res->error('Payment was not added');
            }
         } else {
            $res->error('Invalid payment medium');
         }
      } else {
         $res->error('Invalid course id');
      }
   }

   public function verifyPayment(Request $req, Response $res)
   {
      $txnref = $req->params()->reference ?? null;
      $paypalPaymentID = $req->query()->paymentID ?? null;
      $medium = $req->params()->medium ?? null;

      if ($txnref != null) {

         $isVerified = false;

         if ($medium == 'paystack') {

            $verification = PaystackClient::verifyPayment($txnref);

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
            $log = json_encode($details['log']);
            $status = $details['status'];
            $gatewayResponse = $details['gateway_response'];
            $message = $details['message'];
            $createdAt = $details['created_at'];
            $paidAt = $details['paid_at'];

            // update payment log
            $mdl = new PaymentModel();
            $mdl->updatePaymentRecordForPaymentWithPaystack($paymentId, $id, $domain, $reference, $product, $amount, $currency, $channel, $ipAddress, $log, $status, $gatewayResponse, $message, $createdAt, $paidAt);

            $isVerified = $status == 'success';
         } else if ($medium == 'paypal') {

            $verification = PayPalClient::verifyPayment($paypalPaymentID);

            $details = $verification['result'];

            // retrieving payment details
            $id = $details['id'];
            $domain = $details['domain'];
            $reference = $txnref;
            $product = $details['purchase_units'][0]['description'];
            $amount = $details['purchase_units'][0]['amount']['value'];
            $currency = $details['purchase_units'][0]['amount']['currency_code'];
            $channel = 'paypal';
            $ipAddress = $req->ip();
            $log = json_encode($details['links']);
            $status = $details['status'] == 'APPROVED' ? 'success' : 'failed';
            $gatewayResponse = $details['status'];
            $message = $details['status'];
            $createdAt = $details['create_time'];
            $paidAt = date('c');

            // update payment log
            $mdl = new PaymentModel();
            $mdl->updatePaymentRecordForPaymentWithPayPal($id, $domain, $reference, $product, $amount, $currency, $channel, $ipAddress, $log, $status, $gatewayResponse, $message, $createdAt, $paidAt);

            $isVerified = $status == 'success';
         } else {
            $res->error('Invalid payment medium');
         }

         if ($isVerified == true) {

            // conversion rate
            $conversion_rate = CurrencyConverter::getNairaToDollarRate();

            // prepare details
            if ($medium == 'paystack') {
               $amountInNaira = $amount / 100;
               $plan = $details['metadata']['custom_fields'][0]['value'];
            }

            if ($medium == 'paypal') {
               $amountInNaira = $amount * $conversion_rate;
               $plan = $details['purchase_units'][0]['custom_id'];
            }

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
               
               $planToWords = $this->planToWords($plan);

               // notify student of subscription details
               (new NotificationsModel())->addNotification(User::$email, "Thank you for subscribing. You have Subscribed to a $planToWords plan. Your Subscription expires ($end).");

               $msg = <<<HTML
                     <div>
                        <h3>Thank you for subscribing</h3>
                        <p>You have subscribed to a <b>{$planToWords}</b> plan. Your Subscription expires (<b>$end</b>).</p>
                     </div>
HTML;
               Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", User::$email, "Thank You For Subscribing.", 'info@spicyguitaracademy.com:Spicy Guitar Academy');

               if ($medium == 'paypal' || $medium == 'paystack') {
                  // add spicy units for referral
                  $this->addSpicyUnitsForStudentReferral(User::$email, $amountInNaira);
               }

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

   public function planToWords(int $plan)
   {
      switch ($plan) {
         case 1: return "One Month";
         case 3: return "Three Months";
         case 6: return "Six Months";
         case 12: return "Twelve Months";
      }
   }

   public function verifyFeaturedPayment(Request $req, Response $res)
   {
      $txnref = $req->params()->reference ?? null;
      $paypalPaymentID = $req->query()->paymentID ?? null;
      $medium = $req->params()->medium ?? null;

      if ($txnref != null) {

         $isVerified = false;

         if ($medium == 'paystack') {

            $verification = PaystackClient::verifyPayment($txnref);

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
            $log = json_encode($details['log']);
            $status = $details['status'];
            $gatewayResponse = $details['gateway_response'];
            $message = $details['message'];
            $createdAt = $details['created_at'];
            $paidAt = $details['paid_at'];

            // update payment log
            $mdl = new PaymentModel();
            $mdl->updatePaymentRecordForPaymentWithPaystack($paymentId, $id, $domain, $reference, $product, $amount, $currency, $channel, $ipAddress, $log, $status, $gatewayResponse, $message, $createdAt, $paidAt);

            $isVerified = $status == 'success';
         } else if ($medium == 'paypal') {

            $verification = PayPalClient::verifyPayment($paypalPaymentID);

            $details = $verification['result'];

            // retrieving payment details
            $id = $details['id'];
            $domain = $details['domain'];
            $reference = $txnref;
            $product = $details['purchase_units'][0]['description'];
            $amount = $details['purchase_units'][0]['amount']['value'];
            $currency = $details['purchase_units'][0]['amount']['currency_code'];
            $channel = 'paypal';
            $ipAddress = $req->ip();
            $log = json_encode($details['links']);
            $status = $details['status'] == 'APPROVED' ? 'success' : 'failed';
            $gatewayResponse = $details['status'];
            $message = $details['status'];
            $createdAt = $details['create_time'];
            $paidAt = date('c');

            // update payment log
            $mdl = new PaymentModel();
            $mdl->updatePaymentRecordForPaymentWithPayPal($id, $domain, $reference, $product, $amount, $currency, $channel, $ipAddress, $log, $status, $gatewayResponse, $message, $createdAt, $paidAt);

            $isVerified = $status == 'success';
         } else {
            $res->error('Invalid payment medium');
         }

         if ($isVerified == true) {

            // conversion rate
            $conversion_rate = CurrencyConverter::getNairaToDollarRate();

            // prepare details
            if ($medium == 'paystack') {
               $amountInNaira = $amount / 100;
               $courseId = $details['metadata']['custom_fields'][0]['value'];
            }

            if ($medium == 'paypal') {
               $amountInNaira = $amount * $conversion_rate;
               $courseId = $details['purchase_units'][0]['custom_id'];
            }

            // add transaction
            $mdl = new TransactionModel();

            if ($mdl->addTransaction(User::$email, $reference, $amountInNaira, date("Y-m-d"), date("H:i:s"), $status) == true) {

               $cmdl = new CourseModel();
               $categoryId = $cmdl->getCourse($courseId)[0]['category'] ?? 0;

               // add the course to the student courses table
               $studentCourseMdl = new StudentCourseModel();
               $studentCourseMdl->addCourseForStudent($categoryId, $courseId, User::$email, "FEATURED");

               // notify student of subscription details
               (new NotificationsModel())->addNotification(User::$email, "Thank you for Buying a Course. You have lifetime Access to this course and all its features.");

               $msg = <<<HTML
         <div>
            <h3>Thank you for Buying a Course.</h3>
            <p>You have lifetime Access to this course and all its features.</p>

            <p>Thanks.</p>
         </div>
HTML;
               Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", User::$email, "Thank You For Buying.", 'info@spicyguitaracademy.com:Spicy Guitar Academy');

               if ($medium == 'paypal' || $medium == 'paystack') {
                  // add spicy units for referral
                  $this->addSpicyUnitsForStudentReferral(User::$email, $amountInNaira);
               }

               $res->success('Bought course successfully');
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

   public function completeSubscriptionPaymentWithSpicyUnits(Request $req, Response $res)
   {
      // get the course from req body
      // get the spicy units for this student
      // if the units is greater thana or equal to the course price
      // then: 
      // 1. add course for student
      // 2. deduct units from students units
      // 3. notify students
      // 4. send response

      $plan = $req->body()->plan ?? null;

      $v = new Validate();
      $v->numbers('Plan', $plan, 'Invalid Plan')->minvalue(0)->maxvalue(4);

      if (!$v->errors()) {
         $mdl = new SubscriptionModel();
         $sMdl = new Studentmodel();
         $spicyUnits = $sMdl->getStudent(User::$email)[0]['referral_units'] ?? 0;

         $reference = $this->generateTxnref("N$plan");
         $sub = $mdl->getThisSubscription($plan)[0];
         $price = $sub['price'] ?? 0;
         $plan = $sub['plan'];

         if ($spicyUnits >= $price) {
            $status = "success";

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

            if ($mdl->addTransaction(User::$email, $reference, $price, date("Y-m-d"), date("H:i:s"), $status) == true) {

               // add to student subscription table
               $mdl = new StudentSubscriptionModel();
               $mdl->addStudentSubscription(User::$email, $reference, $plan, 0, $start, $end);

               // deduct spicy units
               $sMdl->updateRefUnits(User::$email, ($spicyUnits - $price));

               $planToWords = $this->planToWords($plan);

               // notify student of subscription details
               (new NotificationsModel())->addNotification(User::$email, "Thank you for subscribing. You have Subscribed to a $planToWords plan. Your Subscription expires ($end).");

               $msg = <<<HTML
                     <div>
                        <h3>Thank you for subscribing</h3>
                        <p>You have subscribed to a <b>{$planToWords}</b> plan. Your Subscription expires (<b>$end</b>).</p>
                     </div>
HTML;
               Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", User::$email, "Thank You For Subscribing.", 'info@spicyguitaracademy.com:Spicy Guitar Academy');

               $res->success('Subscription completed');
            } else {
               $res->error('Incomplete transaction');
            }
         } else {
            $res->error("Insufficient Spicy Units");
         }
      } else {
         $res->error('Invalid course id');
      }
   }

   public function completeFeaturedPaymentWithSpicyUnits(Request $req, Response $res)
   {
      // get the course from req body
      // get the spicy units for this student
      // if the units is greater thana or equal to the course price
      // then: 
      // 1. add course for student
      // 2. deduct units from students units
      // 3. notify students
      // 4. send response

      $courseId = $req->body()->course ?? null;

      $v = new Validate();
      $v->numbers('Plan', $courseId, 'Invalid Course')->minvalue(1);

      if (!$v->errors()) {
         $mdl = new CourseModel();
         $sMdl = new Studentmodel();
         $spicyUnits = $sMdl->getStudent(User::$email)[0]['referral_units'] ?? 0;

         $reference = $this->generateTxnref("Q$courseId");
         $course = $mdl->getCourse($courseId)[0];
         $price = $course['featuredprice'] ?? 0;

         if ($spicyUnits >= $price) {
            $status = "success";

            // add transaction
            $mdl = new TransactionModel();

            if ($mdl->addTransaction(User::$email, $reference, $price, date("Y-m-d"), date("H:i:s"), $status) == true) {

               $cmdl = new CourseModel();
               $categoryId = $cmdl->getCourse($courseId)[0]['category'] ?? 0;

               // add the course to the student courses table
               $studentCourseMdl = new StudentCourseModel();

               $studentCourseMdl->addCourseForStudent($categoryId, $courseId, User::$email, "FEATURED");

               // deduct spicy units
               $sMdl->updateRefUnits(User::$email, ($spicyUnits - $price));

               // notify student of subscription details
               (new NotificationsModel())->addNotification(User::$email, "Thank you for Buying a Course. You have lifetime Access to this course and all its features.");

               $msg = <<<HTML
         <div>
            <h3>Thank you for Buying a Course.</h3>
            <p>You have lifetime Access to this course and all its features.</p>

            <p>Thanks.</p>
         </div>
HTML;
               Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", User::$email, "Thank You For Buying.", 'info@spicyguitaracademy.com:Spicy Guitar Academy');

               $res->success('Bought course successfully');
            } else {
               $res->error('Incomplete transaction');
            }
         } else {
            $res->error("Insufficient Spicy Units");
         }
      } else {
         $res->error('Invalid course id');
      }
   }

   private function addSpicyUnitsForStudentReferral($email, $amount)
   {
      $studentMdl = new StudentModel();
      $student = $studentMdl->getStudent($email)[0];
      $referredBy = $student['referred_by'];

      if ($referredBy != '') {
         $refBy = $studentMdl->getStudentByRefCode($referredBy);
         $refUnits = $refBy['referral_units'];
         $spicyUnits = floor((10 / 100) * $amount);

         $studentMdl->updateRefUnits($refBy['email'], ($refUnits + $spicyUnits));

         // notify student
         (new NotificationsModel())->addNotification($refBy['email'], "Congratulations You have earned $spicyUnits Spicy Units. You can use Spicy Units to either pay for Subscription or buy Featured Courses. Thanks.");

         $msg = <<<HTML
         <div>
            <h3>Congratulations</h3>
            <p>You have earned $spicyUnits Spicy Units.</p>

            <p>You can use Spicy Units to either pay for Subscription or buy Featured Courses.</p>

            <p>Continue referring.</p>
            <p>Thanks.</p>
         </div>
HTML;
         Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", $refBy['email'], "Congratulations!!!.", 'info@spicyguitaracademy.com:Spicy Guitar Academy');
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
   }
}
