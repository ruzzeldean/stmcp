<?php
require_once __DIR__ . '/../config/database.php';
// require_once __DIR__ . '/includes/functions/functions.php';

function e($value)
{
  return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

$db = new Database();
$postID = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

if (!$postID) {
  http_response_code(400);
  exit('Invalid Post ID');
}

$post = $db->getPostByID($postID);

if (!$post) {
  http_response_code(404);
  exit('Post not found or not published');
}

// latest post (right column):

// $limit = 3;
// $latestPosts = getLatestPosts($conn, $limit);
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <?php require_once './includes/head.php'; ?>
</head>
<body>
  <div class="wrapper">
    <!-- Header & Nav -->
    <?php require_once './includes/header.php'; ?>
    
    <!-- Main -->
    <main>
      <section class="py-5">
        <div class="container py-5">
          <div class="row row-gap-5">
            <div class="col-lg-9">
              <div>
                <a id="back-btn" class="btn btn-secondary mb-4"><i class="fa-solid fa-arrow-left"></i> <span class="fw-medium">Back</span></a>

                <div class="mb-4">
                  <h1><?= e($post['title']) ?></h1>

                  <?php
                    $category = $post['category'];
                    $badge = match($category) {
                      'Announcement' => 'info',
                      'Upcoming' => 'warning',
                      'Past Event' => 'success',
                      default => 'secondary'
                    }
                  ?>

                  <p class="card-text"><small class="text-muted">Published on <?= date('M j, Y', strtotime($post['created_at'])) ?></small> | <span class="badge text-bg-<?= e($badge) ?>"><?= e($post['category']) ?></span></p>
                </div>

                <div id="view-post-img" class="mb-4 text-center rounded" style="background: url('/stmcp/uploads/posts/<?= e($post['image_path']) ?>') center/cover;"></div>

                <div class="view-post-content"><?= e($post['content']) ?></div>
              </div>
            </div>

            <!-- Column Break -->

            <div class="col-lg-3">
              <div class="sticky-top">
                <!-- <div class="mb-4"><h3>Latest Posts</h3></div> -->

                <div class="row row-gap-3">
                  <div class="col-12 mt-lg-5"><h3 class="mt-lg-3">Latest Posts</h3></div>

                  <?php if ($latestPosts === 0): ?>
                    <p class="lead">No post found</p>
                  <?php else: ?>
                    <?php foreach ($latestPosts as $post): ?>
                      <div class="col-12">
                        <div class="card shadow overflow-hidden" title="<?= e($post['title']) ?>">
                          <div class="row g-0">
                            <div class="col-lg-4 order-lg-last">
                              <img class="latest-posts-img img-fluid" src="../uploads/posts/<?= e($post['image_path']) ?>" alt="<?= e($post['title']) ?>">
                            </div>

                            <div class="col-lg-8 order-lg-first">
                              <div class="card-body">
                                <h4 class="card-title custom-text-truncate-1"><?= e($post['title']) ?></h4>

                                <?php
                                  $category = $post['category'];
                                  $badge = match($category) {
                                    'Upcoming' => 'warning',
                                    'Past Event' => 'success',
                                    default => 'secondary'
                                  }
                                ?>

                                <p class="card-text"><small class="text-muted"><?= date('M j, Y', strtotime($post['created_at'])) ?></small> | <span class="badge text-bg-<?= e($badge) ?>"><?= e($post['category']) ?></span></p>

                                <a href="./view.php?id=<?= e($post['post_id']) ?>" class="stretched-link"></a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php endif;?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
    <!-- End of main -->
    
    <footer class="py-5">

    </footer>
    <!-- End of footer -->
  </div>
  <!-- End of .wrapper -->
  
  <?php require_once './includes/footer.php'; ?>
  <!-- Custom script -->
  <script>
    $('#back-btn').on('click', () => {
      if (document.referrer !== '') {
        history.back();
      } else {
        window.location.href = './';
      }
    });
  </script>
</body>
</html>