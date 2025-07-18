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
                  <a class="nav-link active" aria-current="page" href="./">Home</a>
                </li> <!-- End of .nav-item (home) -->
                
                <li class="nav-item">
                  <a class="nav-link" href="./about.php">About</a>
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
            <div class="col-xl-8 d-flex justify-content-center align-items-center mt-5">
              <div id="hero-content" class="text-center text-xl-start">
                <h1 class="display-3 fw-semibold">Star Touring Motorcycle Club Philippines</h1>
            
                <p class="lead text-muted fs-4 fw-normal mb-4">People on their quest to share and explore the Philippines's greatest adventures.</p>
          
                <div class="d-flex gap-3 justify-content-center justify-content-xl-start">
                  <button class="btn btn-warning btn-lg">Join us</button>
                  <a class="btn btn-outline-light btn-lg" href="./about.php">About us</a>
                </div> <!-- End of buttons -->
              </div> <!-- End of #hero-content -->
            </div> <!-- End of .col -->

            <!-- Hidden on small screens -->
            <div class="d-none d-xl-block col-xl-4 mt-5">
              <div id="hero-img" class="d-flex justify-content-center align-items-center">
                <img id="hero-logo" class="img-fluid" src="./assets/img/logo/logo.png" alt="">
              </div> <!-- End of .col -->
            </div> <!-- End of #hero-img -->
          </div> <!-- End of .row -->
          
        </div> <!-- End of .container -->
      </section> <!-- End of hero-section -->

      <!-- About section -->
      <section class="py-5">
        <div class="container">
          <h2 class="text-center display-3 fw-semibold mb-5">About STMCP</h2>

          <div class="row row-gap-3">
            <div class="col-md-6">
              <div>
                <img class="img-fluid rounded" src="./assets/img/logo/one-star-logo.png" alt="STMCP One Star Logo">
              </div>
            </div> <!-- End of .col-md-6 -->

            <div class="col-md-6">
              <div class="d-flex flex-column justify-content-center h-100">
                <p class="lead fs-4 mb-4"><span class="text-warning">STMCP</span> is a motorcycle club driven by passion, camaraderie, and a commitment to helping others — combining the thrill of the ride with meaningful community service.</p>

                <div class="text-center text-md-start">
                  <a class="btn btn-outline-light btn-lg" href="./about.php">More about us</a>
                </div> <!-- End of div for button -->
              </div> <!-- End of about us text-div -->
            </div> <!-- End of .col-md-6 -->
          </div> <!-- End of .row -->
        </div> <!-- End of .container -->
      </section> <!-- End of about section -->

      <!-- Posts section -->
      <section class="bg-body-tertiary py-5">
        <div class="container pb-4">
          <h2 class="text-center display-3 fw-semibold mb-5">Posts</h2>

          <div class="row row-gap-4">
            <div class="col-md-6">
              <div class="h-100">
                <div class="card overflow-hidden h-100">
                  <div class="overflow-hidden">
                    <img class="card-img-top post-thumbnail" src="./assets/img/home-page/posts-page/haerin-1.png" alt="Post thumbnail">
                  </div> <!-- End of card-img -->

                  <div class="card-body d-flex flex-column">
                    <h3 class="card-title mb-4">Post Title</h3>

                    <p class="card-text pb-3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit dolorem expedita exercitationem quo corrupti omnis, quaerat nostrum reprehenderit ex, accusamus a ab odio magni minima vero laborum, debitis fugit eaque?</p>

                    <a class="btn btn-secondary mt-auto w-100" href="">View Activities</a>
                  </div> <!-- End of .card-body -->
                </div> <!-- End of .card -->
              </div> <!-- End of div -->
            </div> <!-- End of .col -->

            <div class="col-md-6">
              <div class="h-100">
                <div class="card overflow-hidden h-100">
                  <div class="overflow-hidden">
                    <img class="card-img-top post-thumbnail" src="./assets/img/home-page/posts-page/haerin-3.jpg" alt="Post thumbnail">
                  </div> <!-- End of card-img -->

                  <div class="card-body d-flex flex-column">
                    <h3 class="card-title mb-4">Post Title</h3>

                    <p class="card-text pb-3">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deleniti provident minus accusantium repellendus blanditiis, quidem ratione nihil beatae libero non, quasi similique quos dolore ipsa earum veniam dolor, accusamus eveniet.</p>

                    <a class="btn btn-secondary mt-auto w-100" href="">View News and Updates</a>
                  </div> <!-- End of .card-body -->
                </div> <!-- End of .card -->
              </div> <!-- End of div -->
            </div> <!-- End of .col -->
          </div> <!-- End of .row -->
        </div> <!-- End of .container -->
      </section> <!-- End of posts section -->

      <!-- Join us section -->
      <section id="join-us-section" class="py-5">
        <div class="container my-5 py-5 text-center">
          <h4 class="display-3 fw-semibold mb-3">High Five! STAR Family!</h4>
          <p class="lead fs-4 fw-normal mb-5">Be one of the STMCP family. Start your adventure with us.</p>

          <button class="btn btn-warning btn-lg px-4">Join Us</button>
        </div>
      </section> <!-- End of join us section -->
    </main>
    <!-- End of main -->
    
    <footer class="py-5">

    </footer> <!-- End of footer -->
  </div>
  <!-- End of .wrapper -->
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>