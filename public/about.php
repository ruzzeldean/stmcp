<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Star Touring Motorcycle Club Philippines</title>
  <link rel="shortcut icon" href="./assets/img/logo/logo.png" type="image/x-icon">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
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
                  <a class="nav-link" aria-current="page" href="./">Home</a>
                </li> <!-- End of .nav-item (home) -->
                
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="./about.php">About</a>
                </li> <!-- End of .nav-item (about) -->

                <li class="nav-item">
                  <a class="nav-link" href="./activities.php">Activities</a>
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
      <!-- Hero section -->
      <section id="home-hero" class="hero d-flex justify-content-center align-items-center py-5">
        <div class="container py-5">
          <div class="row">
            <div class="d-flex justify-content-center align-items-center mt-5">
              <div id="hero-content" class="text-center">
                <h1 class="display-3 fw-semibold">ABOUT US</h1>
              </div> <!-- End of #hero-content -->
            </div> <!-- End of .col -->
          </div> <!-- End of .row -->
        </div> <!-- End of .container -->
      </section> <!-- End of hero-section -->

      <!-- History section (header) -->
      <section class="py-5">
        <div class="container">
          <h2 class="text-center display-5 fw-semibold mb-3">Our History</h2>
          <p class="lead text-muted text-center fw-normal">The journey of Star Touring Motorcycle Club Philippines (STMCP) — from humble beginnings to a strong community committed to charity and riding adventures.</p>
        </div> <!-- End of .container -->
      </section> <!-- End of history section (header) -->

      <!-- Foundation section -->
      <section class="py-5">
        <div class="container">
          <div class="mb-4">
            <h4 class="display-6 fw-semibold text-center">Foundation</h4>
          </div> <!-- End of div -->

          <div class="row row-gap-4">
            <div class="col-lg-6">
              <div id="four-pillar-img" class="rounded"></div>
            </div> <!-- End of .col for 4 pillars img -->

            <div class="col-lg-6 lead d-flex flex-column justify-content-center">
              <div>
                <p>The Star Touring Motorcycle Club Philippines (STMCP) was founded by four key individuals, affectionately known within the club as the "4 Pillars"—Erick Lee, Alo Yap, Ericson Gallito, and Mike Miranda.</p>
              
                <p>On July 5, 2021, STMCP was officially registered with the Securities and Exchange Commission (SEC), marking its formal recognition as an organization.</p>
              </div> <!-- End of div -->
            </div> <!-- End of .col -->
          </div> <!-- End of .row -->
        </div> <!-- End of .container -->
      </section> <!-- End of foundation section -->

      <!-- Growth & expansion section -->
      <section class="py-5">
        <div class="container">
          <div class="mb-4">
            <h4 class="display-6 fw-semibold text-center">Growth & Expansion</h4>
          </div> <!-- End of div -->

          <div class="row row-gap-4">

            <div class="col-lg-6">
              <div id="four-pillar-img" class="order-lg-last rounded"></div>
            </div>

            <div class="col-lg-6 order-lg-first d-flex flex-column justify-content-center">
              <div class="lead ">
                <p>Since its inception, STMCP has steadily grown, attracting motorcycle enthusiasts from the Luzon region and beyond.</p>

                <p>The club has expanded through word of mouth and active community involvement, creating a network of passionate riders.</p>

                <p>Today, STMCP is recognized not only as a riding club but as a vibrant and growing community united by shared values and advocacy.</p>
              </div>
            </div> <!-- End of .col -->
          </div> <!-- End of .row -->
        </div> <!-- End of .container -->
      </section> <!-- End of growth & expansion section -->
    </main>
    <!-- End of main -->
    
    <footer class="py-5">

    </footer> <!-- End of footer -->
  </div>
  <!-- End of .wrapper -->
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>