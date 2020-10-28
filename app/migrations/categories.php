<?php
namespace App\Migrations;
use Framework\Database\Designer;

$table = new Designer();

$table->create('category_tbl');
$table->int('id', 4)->primary()->auto_increment();
$table->varchar('category', 15)->index();
$table->varchar('thumbnail', 100);
$table->timestamp('date_added');
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