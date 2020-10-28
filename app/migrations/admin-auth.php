<?php
namespace App\Migrations;
use Framework\Database\Designer;

$table = new Designer();

$table->create('admin_auth_tbl');
$table->double('id')->primary()->auto_increment();
$table->varchar('username', 40)->index();
$table->varchar('password', 100)->index();
$table->varchar('role')->index(); # student, tutor, admin
$table->varchar('status')->default('inactive'); # blocked, active
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