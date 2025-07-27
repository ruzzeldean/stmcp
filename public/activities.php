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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Star Touring Motorcycle Club Philippines</title>
  <link rel="shortcut icon" href="./assets/img/logo/logo.png" type="image/x-icon">
  <!-- Font Awesome Icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
  <div class="wrapper">
    <!-- Header -->
    <header>
      <nav class="navbar navbar-expand-lg bg-dark fixed-top">
        <div class="container">
          <!-- Brand Logo and Name -->
          <a class="navbar-brand" href="./">
            <img class="d-inline-block align-text-top" src="./assets/img/logo/logo.png" alt="STMCP Logo" width="32" height="31">
            STMCP
          </a> <!-- End of .navbar-brand -->
          <!-- End of Brand Logo and Name -->
          <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#navMenu" aria-controls="navMenu" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button> <!-- End of .navbar-toggler -->
            
          <div class="offcanvas offcanvas-end" tabindex="-1" id="navMenu" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
              <h5 class="offcanvas-title pe-5" id="offcanvasNavbarLabel">STMCP</h5>
              <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div> <!-- End of .offcanvas-header -->
            
            <div class="offcanvas-body pe-5 pe-lg-0">
              <ul class="navbar-nav justify-content-end flex-grow-1">
                <li class="nav-item">
                  <a class="nav-link" href="./">Home</a>
                </li> <!-- End of .nav-item (home) -->
                
                <li class="nav-item">
                  <a class="nav-link" href="./about.php">About</a>
                </li> <!-- End of .nav-item (about) -->

                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="./activities.php">Activities</a>
                </li> <!-- End of .nav-item (activities) -->

                <li class="nav-item">
                  <a class="nav-link" href="./news_and_updates.php">News and Updates</a> <!-- End of .nav-item (news and updates) -->
                </li> <!-- End of .nav-item (activities) -->
              </ul> <!-- End of .navbar-nav nav-links -->
            </div> <!-- End of .offcanvas-body -->
          </div> <!-- End of .offcanvas -->
        </div> <!-- End of .container -->
      </nav> <!-- End of nav -->
    </header>
    <!-- End of header -->
    
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
  
  <!-- Bootstrap 5 -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <!-- Custom script -->
  <script src="./assets/js/posts/posts.js"></script>
</body>
</html>