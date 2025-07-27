<?php
require_once __DIR__ . '/../config/connection.php';

try {
  $limit = 2;
  $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
  $offset = ($page - 1) * $limit;

  $sql = 'SELECT post_id, title, category, image_path, content, created_at FROM posts WHERE category = :category AND status = :status ORDER BY created_at DESC LIMIT :limit OFFSET :offset';

  $loadUpcoming = $conn->prepare($sql);
  // $loadUpcoming->execute(['category' => 'Upcoming', 'status' => 'Published']);
  $loadUpcoming->bindValue(':category', 'Upcoming', PDO::PARAM_STR);
  $loadUpcoming->bindValue(':status', 'Published', PDO::PARAM_STR);
  $loadUpcoming->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
  $loadUpcoming->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
  $loadUpcoming->execute();

  $upcomingPosts = $loadUpcoming->fetchAll();

  $countSql = 'SELECT COUNT(*) FROM posts WHERE category = :category AND status = :status';
  $countStmt = $conn->prepare($countSql);
  $countStmt->execute(['category' => 'Upcoming', 'status' => 'Published']);
  
  $totalPosts = $countStmt->fetchColumn();
  $totalPages = ceil($totalPosts / $limit);
} catch (Throwable $ex) {
  error_log('Error fetching posts: ' . $ex->getMessage());
  exit('Error fetching posts. Please try again later');
}
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
      <section id="home-hero" class="hero d-flex justify-content-center align-items-center py-5">
        <div class="container py-5">
          <div class="row py-5">
            <div class="d-flex justify-content-center align-items-center mt-5 py-5">
              <div id="hero-content" class="py-5 text-center">
                <h1 class="display-3 fw-semibold">ACTIVITIES</h1>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="bg-body-tertiary py-5">
        <div class="container py-5">
          <h2 class="text-center display-4 fw-semibold mb-3">Our Club Activities</h2>
          <p class="lead text-muted px-3">At Star Touring Motorcycle Club Philippines, we take pride in making a positive impact on the community. Below are some of our past and upcoming charity events where we strive to help those in need.</p>
        </div>
      </section>

      <!-- Upcoming Events -->
      <section class="py-5">
        <div class="container py-5">
          <div class="mb-4">
            <h3 class="display-5 fw-semibold text-center">UPCOMING EVENTS</h3>
          </div>

          <div id="upcoming-posts-wrapper">
            <div class="row row-gap-3">
              <?php if (count($upcomingPosts) === 0): ?>
                <p class="text-center lead">There's no post yet. Come back later.</p>

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
              <?php endif; ?>
            </div> <!-- /.row -->
          </div> <!-- /.upcoming-posts-wrapper -->
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
  <script src="./assets/js/posts/posts.js"></script>
</body>
</html>