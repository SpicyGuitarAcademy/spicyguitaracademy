<?php
namespace App\Migrations;
use Framework\Database\Designer;

$table = new Designer();

$table->create('assignment_tbl');
$table->double('id')->auto_increment()->primary();
$table->double('course_id')->index();
$table->double('tutor_id')->index();
$table->text('note')->default('No Assignmnet.');
$table->varchar('video', 100);
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