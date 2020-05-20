<?php
namespace App\Migrations;
use Framework\Database\Designer;

$table = new Designer();

$table->create('planks_tbl');
$table->int('plank_id', 11)->primary()->auto_increment();
$table->varchar('plank_name')->default();
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