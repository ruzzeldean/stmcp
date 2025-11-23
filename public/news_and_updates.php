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
                <h1 class="display-3 fw-semibold">NEWS AND UPDATES</h1>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="bg-body-tertiary py-5">
        <div class="container py-5">
          <h2 class="text-center display-4 fw-semibold mb-3">News and Updates</h2>
          <p class="lead text-muted text-center px-3">Stay updated with the latest news, events, and activities of the Star Touring Motorcycle Club Philippines. Here, we share our journey, achievements, and upcoming events that bring our community together. Whether it's a charity ride, a meet and greet, or an online tournament, you'll find all the details right here. Join us in celebrating our passion for motorcycling and the camaraderie that makes STMCP special!</p>
        </div>

        <div class="container">
          <div id="loading-spinner" class="text-center my-3 d-none">
          <div class="spinner-border" role="status"></div>
        </div>
          <div id="posts-container" class="row row-gap-4">
            <!-- Posts goes here -->
          </div> <!-- /.past-posts-wrapper -->
        </div>

        
        <div class="container mt-4">
          <nav id="pagination-wrapper"></nav>
        </div>
      </section>

      <!-- Past Events -->
      
    </main>
    <!-- End of main -->

    <?php require_once __DIR__ . '/includes/footer.php' ?>
  </div>
  <!-- End of .wrapper -->

  <?php require_once __DIR__ . '/includes/scripts.php' ?>
  <!-- Custom script -->
  <script src="./assets/js/news_and_updates/news_and_updates.js"></script>
</body>
</html>