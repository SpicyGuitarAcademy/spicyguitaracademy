<?php
namespace App\Migrations;
use Framework\Database\Designer;

$table = new Designer();

$table->create('admin_tbl');
$table->double('id')->primary()->auto_increment();
$table->double('auth_id');
$table->varchar('firstname', 20);
$table->varchar('lastname', 20);
$table->varchar('avatar', 100)->null();
$table->varchar('email', 100);
$table->varchar('telephone', 20);
$table->varchar('twitter', 100)->null();
$table->year('experience');
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