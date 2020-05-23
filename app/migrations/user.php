<?php
namespace App\Migrations;
use Framework\Database\Designer;

$table = new Designer();

$table->create("user_tbl");
$table->int('user_id')->primary()->auto_increment();
$table->varchar('username', 20)->index();
$table->exe();


/*
   Help

   To create a table
   $table->create('table-name');

   To drop a table if it exists before creating it
   $table->create('table-name', true);

   To drop a table that references foreign keys before creating it
   $table->create('table-name', true, 'referencing-table-name.referenced-key');
*/