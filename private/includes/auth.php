<?php
session_start();

requireLogin();

include __DIR__ . '/../../config/database.php';

$currentPage = basename($_SERVER['PHP_SELF']);

if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['csrf_token'];

$sessionRoleId = $_SESSION['role_id'] ?? null;

$superAdmin = $sessionRoleId === 1;
$admin = $sessionRoleId === 2;
$moderator = $sessionRoleId === 3;
$member = $sessionRoleId === 4;
$aspirant = $sessionRoleId === 5;

if ($superAdmin || $admin) {
  $userRole = 'admin';
} 

if ($superAdmin || $moderator) {
  $userRole = 'moderator';
}

if ($superAdmin || $member) {
  $userRole = 'member';
}

if ($superAdmin || $aspirant) {
  $userRole = 'aspirant';
}

function requireLogin()
{
  if (!isset($_SESSION['user_id'], $_SESSION['role_id'])) {
    header('Location: ../auth/login.php');
    exit;
  }
}

function requireAdmin()
{
  $roleId = (int) $_SESSION['role_id'];

  if (!in_array($roleId, [1, 2], true)) {
    header('Location: dashboard.php');
    exit;
  }
}

function requireModerator()
{
  $roleId = (int) $_SESSION['role_id'];

  if (!in_array($roleId, [1, 3], true)) {
    header('Location: dashboard.php');
    exit;
  }
}

function requireAdminModerator()
{
  $roleId = (int) $_SESSION['role_id'];

  if (!in_array($roleId, [1, 2, 3], true)) {
    header('Location: dashboard.php');
    exit;
  }
}

function requireOfficialMembers()
{
  $roleId = (int) $_SESSION['role_id'];

  if (in_array($roleId, [5], true)) {
    header('Location: dashboard.php');
    exit;
  }
}

function e($value)
{
  return htmlspecialchars(
    $value ?? '',
    ENT_QUOTES | ENT_SUBSTITUTE,
    'UTF-8'
  );
}
