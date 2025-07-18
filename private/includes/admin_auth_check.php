<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['adminGatepass'])) {
  header('Location: /stmcp/private/');
  exit;
}

require_once __DIR__ . '/../../config/connection.php';
