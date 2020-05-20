<?php
namespace App\Migrations;
use Framework\Database\Designer;

$table = new Designer();

$table->create("user_tbl");
$table->int("user_id",11)->primary()->auto_increment();
$table->varchar("username",20)->null()->default("user")->index();
$table->varchar("password",20)->not_null()->default("password");
$table->exe();

$table->create("customer_tbl");
$table->int("id",11)->primary()->auto_increment();
$table->varchar("username",20)->default("customer");
$table->varchar("password",20)->default("password");
$table->foreign("id", "user_tbl", "user_id", "CASCADE", "CASCADE");
$table->exe();

$table->create("students_tbl");
$table->double('student_id')->primary()->auto_increment();
$table->varchar("student_name","100")->index();
$table->exe();