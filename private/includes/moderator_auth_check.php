<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['moderatorGatepass']) || $_SESSION['roleID'] === '3') {
  header('Location: /stmcp/private/');
  exit;
}

require_once __DIR__ . '/../../config/connection.php';
