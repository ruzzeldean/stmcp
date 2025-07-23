<?php
require_once __DIR__ . '/../config/error_logging.php';
require_once __DIR__ . '/../.env/credentials.php';

try {
  $conn = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $user, $pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  error_log("Database connection failed: " . $e->getMessage());
  http_response_code(500);
  exit('Database connection error. Please try again later.');
}
