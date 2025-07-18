<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

<<<<<<< HEAD
if (!isset($_SESSION['moderatorGatepass']) || $_SESSION['roleID'] === '3') {
=======
if (!isset($_SESSION['moderatorGatepass'])) {
>>>>>>> ca66836 (feat: add helper function for htmlspecialchars())
  header('Location: /stmcp/private/');
  exit;
}

require_once __DIR__ . '/../../config/connection.php';
<<<<<<< HEAD
=======

function e($value)
{
  return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
>>>>>>> ca66836 (feat: add helper function for htmlspecialchars())
