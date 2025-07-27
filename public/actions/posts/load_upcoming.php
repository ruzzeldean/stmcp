<?php

if (
  $_SERVER['REQUEST_METHOD'] !== 'GET' ||
  empty($_SERVER['HTTP_X_REQUESTED_WITH']) ||
  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'
) {
  http_response_code(403);
  exit('Direct access is not allowed.');
}

require_once __DIR__ . '/../../../config/connection.php';

try {
  $limit = 2;
  $page = max((int) ($_GET['page']) ?? 1, 1);
  $offset = ($page - 1) * $limit;

  $sql = 'SELECT post_id, title, category, image_path, content, created_at
          FROM posts
          WHERE category = :category AND status = :status
          ORDER BY created_at DESC
          LIMIT :limit OFFSET :offset';

  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':category', 'Upcoming', PDO::PARAM_STR);
  $stmt->bindValue(':status', 'Published', PDO::PARAM_STR);
  $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
  $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
  $stmt->execute();
  $upcomingPosts = $stmt->fetchAll();

  $countSql = 'SELECT COUNT(*) FROM posts WHERE category = :category AND status = :status';
  $countStmt = $conn->prepare($countSql);
  $countStmt->execute(['category' => 'Upcoming', 'status' => 'Published']);
  $totalPosts = $countStmt->fetchColumn();
  $totalPages = ceil($totalPosts / $limit);
} catch (Throwable $ex) {
  http_response_code(500);
  exit('Error loading posts');
}
?>

<div class="row row-gap-3">
  <?php if (count($upcomingPosts) === 0): ?>
    <p class="lead text-center">There's no post yet. Come back later.</p>
  <?php else: ?>
    <?php foreach ($upcomingPosts as $post): ?>
      <div class="col-lg-6">
        <div class="card shadow overflow-hidden">
          <div class="row g-0">
            <div class="col-md-4">
              <img class="upcoming-card-img img-fluid" src="/stmcp/uploads/posts/<?= htmlspecialchars($post['image_path']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
            </div>

            <div class="col-md-8">
              <div class="card-body">
                <h5 class="card-title text-truncate mb-0 py-1" title="<?= htmlspecialchars($post['title']) ?>"><?= htmlspecialchars($post['title']) ?></h5>

                <p class="card-text"><small class="text-muted"><?= date('M j, Y', strtotime($post['created_at'])) ?></small> | <span class="badge bg-secondary"><?= htmlspecialchars($post['category']) ?></span></p>

                <div class="post-content text-truncate"><?= htmlspecialchars($post['content']) ?></div>
              </div>

              <div class="card-footer bg-dark border-0 py-3">
                <a class="btn btn-warning" href="./view_post.php?id=<?= htmlspecialchars($post['post_id']) ?>">Read more</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

    <!-- Pagination -->
    <div class="pagination d-flex justify-content-center">
      <nav>
        <ul class="pagination">
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $page == $i ? 'active' : '' ?>">
              <a class="page-link pagination-link" href="#" data-page="<?= $i ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
    </div> <!-- /.pagination -->

    <!-- Loading spinner -->
    <div id="loading-spinner" class="text-center d-none">
      <div class="spinner-border text-warning" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    <!-- /.loading-spinner -->
  <?php endif; ?>
</div>