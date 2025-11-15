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
          <p class="lead text-muted px-3">At Star Touring Motorcycle Club Philippines, we take pride in making a positive impact on the community. Below are some of our past charity events where we strive to help those in need.</p>
        </div>

        <div class="container">
          <div id="loading-spinner" class="text-center my-3 d-none">
          <div class="spinner-border" role="status"></div>
        </div>
          <div id="posts-container" class="row row-gap-3">
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