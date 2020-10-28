<?php
namespace App\Migrations;
use Framework\Database\Designer;

$table = new Designer();


// $table->create('lesson_tbl');
// $table->double('id')->primary()->auto_increment();
// $table->double('course')->index();
// $table->varchar('lesson', 20)->index();
// $table->varchar('description', 100)->index()->default('No Description');
// $table->varchar('thumbnail', 100);
// $table->varchar('tutor', 40);
// $table->varchar('low_video', 100)->null();
// $table->varchar('high_video', 100)->null();
// $table->varchar('audio', 100)->null();
// $table->varchar('practice_audio', 100)->null();
// $table->varchar('tablature', 100)->null();
// $table->text('note')->null();
// $table->timestamp('date_added');
// $table->foreign('course', 'course_tbl', 'id', 'RESTRICT', 'CASCADE');
// $table->exe();


$table->create('lesson_tbl');
$table->double('id')->primary()->auto_increment();
$table->double('course')->index();
$table->varchar('lesson', 20)->index();
$table->varchar('description', 100)->index()->default('No Description');
$table->varchar('thumbnail', 100);
$table->varchar('tutor', 40);
$table->varchar('low_video', 100)->null();
$table->varchar('high_video', 100)->null();
$table->varchar('audio', 100)->null();
$table->varchar('practice_audio', 100)->null();
$table->varchar('tablature', 100)->null();
$table->text('note')->null();
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