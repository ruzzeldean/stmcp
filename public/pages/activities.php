<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
  <?php include __DIR__ . '/../partials/head.php'; ?>
</head>

<body>
  <div class="wrapper">
    <?php include __DIR__ . '/../partials/header.php'; ?>

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
          <div id="posts-container" class="row row-gap-4"></div>
        </div>

        <div class="container mt-3">
          <div class="row row-gap-2">
            <div class="col-md-6 order-md-last">
              <nav class="">
                <ul id="pagination-controls"
                  class="pagination pagination-sm justify-content-center justify-content-md-end mb-0 mt-3 mt-lg-0"></ul>
              </nav>
            </div>

            <div id="pagination-info"
              class="col-md-6 d-flex align-items-center justify-content-center justify-content-md-start">
            </div>
          </div>
        </div>
      </section>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>
  </div>

  <?php include __DIR__ . '/../partials/scripts.php'; ?>
  <script src="../assets/js/posts/posts.js"></script>
</body>

</html>