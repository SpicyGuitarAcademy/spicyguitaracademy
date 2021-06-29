<?php
namespace Controllers;
use Framework\Http\Http;
use Framework\Http\Request;
use Framework\Http\Response;
use App\Services\Auth;
use Models\TransactionModel;

class TransactionController
{

   public function index(Request $req, Response $res)
   {
      $txnMdl = new TransactionModel();
      $allTxns = $txnMdl->getAllTransactions();

      $res->send(
         $res->render('admin/transactions.html', [
            "transactions" => json_encode($allTxns)
         ])
      );
   }

   public function create(Request $req, Response $res)
   {
      // create a resource
   }

   public function read(Request $req, Response $res)
   {
      // return a resource
   }

   public function update(Request $req, Response $res)
   {
      // update a resource
   }

   public function delete(Request $req, Response $res)
   {
      // remove a resouce
   }

}
