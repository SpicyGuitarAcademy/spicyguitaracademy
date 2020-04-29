<?php
namespace App;
use Framework\Database\Designer;

include_once '../vendor/autoload.php';

$table = new \Framework\Database\Designer();

$table->create("user_tbl");
$table->int("id",11)->default("CURRENT_TIMESTAMP")->auto_increment();
$table->varchar("username",20);
$table->exe();

echo "\n";

$table->create("user_tbl");
$table->int("id",11)->default(0)->primary();
$table->varchar("username",20)->auto_increment();
$table->enum("enuum", ["EBUKA", "ODINI"]);
$table->exe();