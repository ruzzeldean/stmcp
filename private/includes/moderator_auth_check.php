<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['moderatorGatepass'])) {
  header('Location: /stmcp/private/');
  exit;
}

require_once __DIR__ . '/../../config/connection.php';

function e($value)
{
  return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
