<?php
use Core\Route;

// Configs
include_once '../config.php';

// Routes
include_once '../routes/web.php';
include_once '../routes/api.php';

// autoload
include_once '../autoload.php';

// Initialize Application 😉
(new Route)->Init();

?>