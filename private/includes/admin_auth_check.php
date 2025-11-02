<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['gate_pass']) || $_SESSION['gate_pass'] !== 'admin') {
  header('Location: /stmcp/private/');
  exit;
}

require_once __DIR__ . '/../../config/database.php';
$db = new Database();

function e($value)
{
  return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
