<?php
namespace App\Migrations;
use Framework\Database\Designer;

$table = new Designer();


$table->create('course_tbl');
$table->double('id')->primary()->auto_increment();
$table->int('category', 4)->index();
$table->varchar('course', 20)->index();
$table->varchar('description', 100)->index()->default('No Description');
$table->varchar('thumbnail', 100);
$table->varchar('tutor', 40);
$table->int('ord',20)->index()->comment('courses must be taken in this order.');
$table->timestamp('date_added');
$table->boolean('active')->default(true);
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