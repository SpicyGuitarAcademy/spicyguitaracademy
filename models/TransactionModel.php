<?php
namespace Models;
use Framework\Database\Model;

class TransactionModel extends Model
{
   public function __construct()
   {
      parent::__construct('transaction_tbl');
   }

   // write wonderful model codes...
   public function addTransaction(string $studentId, string $txnref, int $amount, string $transcDate, string $transcTime, string $transcStatus) {
      $newTransc = $this->create([
         "student_id"=>$studentId,
         "txnref"=>$txnref,
         "amount"=>$amount,
         "date"=>$transcDate,
         "time"=>$transcTime,
         "status"=>$transcStatus
      ]);

      if ($newTransc == true) {
         return ["flag"=>true,"msg"=>"transaction is added."];
      } else {
         return ["flag"=>false,"msg"=>"transaction is not added."];
      }
   }

   public function getAllTransactions() {
      return $this->read("*");
   }

   public function getThisTransactionDetails(int $transcId) {
      return $this->where("txn_id = $transcId")
      ->read("*");
   }

   public function getAllTransactionByThisStudent(string $studentId) {
      return $this->where("transaction_tbl.student_id = '$studentId' AND transaction_tbl.txnref = student_subscription_tbl.txnref")
      ->readJoin("transaction_tbl, student_subscription_tbl","transaction_tbl.*, student_subscription_tbl.subscription_type,student_subscription_tbl.subscription_plan");
   }

   public function getAllTransactionBetweenThisPeriod(string $startDate, string $endDate) {
      return $this->where("date BETWEEN '$startDate' AND '$endDate'")
      ->read("*");
   }

}
