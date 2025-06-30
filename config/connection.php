<?php
require_once __DIR__ . '/../config/error_logging.php';

$host = 'localhost';
$database = 'stmcp';
$user = 'root';
$pass = '';

try {
  $conn = new PDO("mysql:host=$host;dbname=$database", $user, $pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $ex) {
  error_log("Connection failed: " . $ex->getMessage());
  http_response_code(500);
  exit('Database connection error. Please try again later.');
}
