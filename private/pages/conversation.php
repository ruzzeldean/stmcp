<?php
include __DIR__ . '/../includes/auth.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  exit('Invalid or missing contact ID');
}

$contactID = (int) $_GET['id'];

$db = new Database();

$sql = 'SELECT
          u.user_id,
          p.first_name,
          p.last_name
        FROM users AS u
        INNER JOIN people AS p
          ON u.person_id = p.person_id
        WHERE user_id = :user_id LIMIT 1';
$user = $db->fetchOne($sql, ['user_id' => $contactID]);

$firstName = $user['first_name'];
$lastName = $user['last_name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include __DIR__ . '/../partials/head.php'; ?>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <?php include __DIR__ . '/../partials/aside.php'; ?>

    <main id="chat-wrapper" class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Conversation</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="messages.php">Messages</a></li>
                <li class="breadcrumb-item active" aria-current="page">Conversation</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid">
          <div id="chat-container" class="card border-0 shadow-sm max-w-500">
            <div class="card-header p-0">
              <div class="col-12 d-flex justify-content-between p-2">
                <div id="chatting-with-con" class="d-flex align-items-center rounded p-1">
                  <a href="./messages.php" class="text-decoration-none link-secondary me-1">
                    <i id="msg-back-icon" class="fa-solid fa-chevron-left"></i>
                  </a>

                  <div class="me-2">
                    <img class="chat-avatar img-fluid rounded-circle" src="https://i.pravatar.cc/?img=12" alt="">
                  </div>

                  <div class="text-nowrap mr-1 text-truncate">
                    <?= e($firstName . ' ' . $lastName) ?>
                  </div>
                </div>

                <div class="d-flex align-items-center">
                  <span id="chat-option-icon">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="conversation-body card-body p-2"></div>

            <div class="card-footer bg-transparent p-2">
              <div id="image-preview-wrapper" class="d-none mb-2">
                <img id="image-preview" class="rounded" src="">
                <button id="remove-image-btn" class="btn btn-secondary btn-sm ">
                  <i class="fa-solid fa-xmark"></i>
                </button>
              </div>

              <div id="chat-footer" class="d-flex">
                <div>
                  <input type="file" id="image-input" accept="image/*" style="display: none;">
                  <button id="upload-image" class="btn shadow-none">
                    <i class="fa-solid fa-image"></i>
                  </button>
                </div>

                <div class="w-100 mx-1">
                  <textarea id="message-field" class="form-control rounded-pill" placeholder="Aa" rows="1"></textarea>
                </div>

                <div>
                  <button id="send-button" class="btn border-0 shadow-none">
                    <i class="fa-solid fa-paper-plane"></i>
                  </button>
                </div>
              </div>


            </div> <!-- /.card-footer -->
          </div> <!-- /.row /.card -->
        </div>
      </div>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>
  </div>

  <?php include __DIR__ . '/../partials/scripts.php'; ?>

  <script>
    const CURRENT_USER_ID = <?= e((int) $_SESSION['user_id']) ?>;
    const CONTACT_ID = <?= e((int) $contactID) ?>;
  </script>
  <script src="../assets/js/messages/conversation.js"></script>
</body>

</html>