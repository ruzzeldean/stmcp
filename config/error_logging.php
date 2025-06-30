<?php
ini_set("log_errors", 1);
ini_set("error_log", __DIR__ . "/../logs/errors.log");
ini_set("display_errors", 0);
ini_set("display_startup_errors", 0);

// For development
error_reporting(E_ALL);

// For production
// error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);