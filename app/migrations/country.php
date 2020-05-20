<?php
namespace App\Migrations;
use Framework\Database\Designer;

$table = new Designer();



/*
   Help

   To create a table
   $table->create('table-name');

   To drop a table if it exists before creating it
   $table->create('table-name', true);

   To drop a table that references foreign keys before creating it
   $table->create('table-name', true, 'referencing-table-name.referenced-key');
*/