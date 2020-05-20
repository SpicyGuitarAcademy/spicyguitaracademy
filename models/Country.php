<?php
namespace Models;
use Framework\Database\Model;

class Country extends Model
{
   public function __construct()
   {
      parent::__construct('country_tbl');
   }
}
