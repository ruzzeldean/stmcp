<?php
require_once __DIR__ . '/../../includes/admin_auth_check.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  exit('Invalid or missing contact ID');
}

$contactID = (int) $_GET['id'];

$sql = 'SELECT u.user_id, m.first_name, m.last_name FROM users u INNER JOIN official_members m ON u.member_id = m.member_id WHERE user_id = :user_id LIMIT 1';
$user = $db->fetchOne($sql, ['user_id' => $contactID]);

$firstName = $user['first_name'];
$lastName = $user['last_name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once __DIR__ . '/../../includes/admin/head.php'; ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
  <div class="wrapper">

    <?php require_once __DIR__ . '/../../includes/admin/aside.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div id="chat-wrapper" class="content-wrapper px-3 py-1">
      <div id="chat-container" class="card my-2">
        <div class="card-header p-0">
          <div class="col-12 d-flex justify-content-between p-2">
            <div id="chatting-with-con" class="d-flex align-items-center rounded p-1">
              <a href="./contacts.php" class="mr-1 text-dark">
                <i id="msg-back-icon" class="fa-solid fa-chevron-left"></i>
              </a>

              <div class="mr-2">
                <img class="chat-avatar img-fluid rounded-circle" src="https://i.pravatar.cc/?img=12" alt="">
              </div>

              <div class="text-nowrap mr-1 text-truncate">
                <?= e($firstName . ' ' . $lastName) ?>
              </div>
            </div> <!-- /.chatting-with-con -->

            <div class="d-flex align-items-center">
              <span id="chat-option-icon">
                <i class="fa-solid fa-ellipsis-vertical"></i>
              </span>
            </div>
          </div> <!-- /.col -->
        </div> <!-- /.card-header -->

        <div class="conversation-body card-body p-2">
          <!-- Conversation goes here :> -->
        </div> <!-- /.card-body -->

        <div id="chat-footer" class="card-footer d-flex p-2">
          <div>
            <button id="upload-image" class="btn">
              <i class="fa-solid fa-image"></i>
            </button>
          </div>

          <div class="w-100 mx-1">
            <textarea id="message-field" class="form-control rounded-pill" placeholder="Aa" rows="1"></textarea>
          </div>

          <div>
            <button id="send-button" class="btn">
              <i class="fa-solid fa-paper-plane"></i>
            </button>
          </div>
        </div> <!-- /.card-footer -->
      </div> <!-- /.row /.card -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <strong>&copy; 2025 <span class="text-warning">STMCP</span>. All rights reserved.
    </footer>
  </div>
  <!-- ./wrapper -->

  <?php require_once __DIR__ . '/../../includes/admin/scripts.php'; ?>
  <script>
    const CURRENT_USER_ID = <?= e((int) $_SESSION['user_id']) ?>;
    const CONTACT_ID = <?= e((int) $contactID) ?>;
  </script>
  <script defer src="../../assets/shared/js/messages.js"></script>
</body>

</html>