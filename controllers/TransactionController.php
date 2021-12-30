<?php

namespace Controllers;

use Framework\Http\Http;
use Framework\Http\Request;
use Framework\Http\Response;
use App\Services\Auth;
use App\Services\Validate;
use Models\TransactionModel;

class TransactionController
{

   public function index(Request $req, Response $res)
   {
      $page = $req->query()->page ?? null;
      if ($page !== null) {
         $v = new Validate();
         $v->numbers('Page', $page, 'Invalid page number')->minvalue(1);

         if ($v->errors()) $page = 1;
      }

      $txnMdl = new TransactionModel();
      $allTxns = $txnMdl->getAllTransactionsPaginate($page ?? 1);
      $countTxns = ceil(count($txnMdl->getAllTransactions()) / 20);


      $res->send(
         $res->render('admin/transactions.html', [
            "transactions" => json_encode($allTxns),
            "page" => $page ?? 1,
            "total" => $countTxns ?? 0,
            "start" => (($page ?? 1) - 1) * 20
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
