<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Star Touring Motorcycle Club Philippines</title>
  <link rel="shortcut icon" href="./assets/img/logo/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
  <div class="wrapper bg-dark text-white">
    <!-- Header -->
    <header>
      <!-- Navigation -->
      <nav class="navbar navbar-expand-lg bg-dark fixed-top" data-bs-theme="dark">
        <!-- Container class inside nav -->
        <div class="container">
          <!-- Navbar brand and toggler button -->
          <a href="./" class="navbar-brand">STMCP</a>
          <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbar" aria-controls="navbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <!-- End of navbar brand and toggler button -->

          <!-- Offcanvas -->
          <div class="offcanvas offcanvas-end" tabindex="-1" id="navbar" aria-labelledby="offcanvasNavbarLabel">
            <!-- Offcanvas header(title) -->
            <div class="offcanvas-header">
              <h5 id="offcanvasNavbarLabel" class="offcanvas-title">STMCP</h5>
              <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <!-- End of offcanvas header(title) -->

            <!-- Offcanvas body (nav buttons) -->
            <div class="offcanvas-body">
              <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                <li class="nav-item">
                  <a href="./" class="nav-link active" aria-current="page">Home</a>
                </li>

                <li class="nav-item">
                  <a href="about-us.php" class="nav-link">About Us</a>
                </li>

                <li class="nav-item">
                  <a href="activities.php" class="nav-link">Activities</a>
                </li>

                <li class="nav-item">
                  <a href="news-and-updates.php" class="nav-link">News and Updates</a>
                </li>
              </ul>
            </div>
            <!-- End of offcanvas body (nav buttons) -->
          </div>
          <!-- End of offcanvas-->
        </div>
        <!-- End of container class inside nav -->
      </nav>
      <!-- End of navigation -->
    </header>
    <!-- End of header -->

    <!-- Main -->
    <main>
      <!-- Hero section -->
      <section class="hero py-5">
        <!-- Hero content -->
        <div class="container">
          <div class="row align-items-center justify-content-center pt-5">
            <!-- Hero text -->
            <div class="col-lg-8 col-xl-7 col-xxl-6">
              <div class="my-5 text-center text-xl-start">
                <h1 class="display-5 fw-bolder mb-2">Star Touring Motorcycle Club Philippines</h1>

                <p class="lead fw-normal text-white-50 mb-4">People on their quest to share and explore the Philippines's greatest adventures.</p>

                <a class="btn btn-warning btn-lg px-4" href="join-us.php">Join Us</a>
              </div>
            </div>
            <!-- End of hero text -->

            <!-- Hero image, hidden on small screens -->
            <div class="col-xl-5 col-xxl-6 d-none d-xl-block text-center">
              <img class="img-fluid rounded-3 my-5" src="./assets/img/logo/one-star-logo.png" alt="One star logo" />
            </div>
            <!-- End of hero image -->
          </div>
        </div>
        <!-- End of hero content -->
      </section>
      <!-- End of hero section -->

      <!-- About us section -->
      <section class="about-us py-5">
        <div class="container">
          <div class="row px-2 px-sm-0 py-4 gy-4 gy-md-0">
            <!-- About us image -->
            <div class="col-md-6">
              <img class="img-fluid rounded" src="./assets/img/home-page/about-us-section/4-pillars.jpg" alt="Image of 4 pillars">
            </div>
            <!-- End of about us image -->

            <!-- About us text -->
            <div class="col-md-6 d-flex flex-column justify-content-center align-items-start">
              <h2 class="mb-4">About us</h2>

              <p class="lh-base">
                <span class="text-warning">STMCP</span> is a motorcycle club driven by passion, camaraderie, and a commitment to helping others— combining the thrill of the ride with meaningful community service.
              </p>

              <a class="btn btn-outline-warning align-self-center align-self-lg-start mt-4 mt-sm-2 px-3 py-2" type="button" href="about-us">More about us</a>
            </div>
            <!-- End of about us text -->
          </div>
        </div>
      </section>
      <!-- End of about us section -->

      <!-- Activities section -->
      <section class="activities gray-section py-5">
        <div class="container">
          <h2 class="text-center mb-4 mb-xl-5">Activities</h2>

          <div class="row d-flex gx-4 gy-4">
            <!-- First Card -->
            <div class="col-lg-4">
              <div class="card h-100 shadow">
                <img class="card-img-top" src="./assets/img/home-page/activities-section/sample-img-1.jpg" alt="...">

                <!-- Card body -->
                <div class="card-body">
                  <h5 class="card-title">Activity Title</h5>
                  <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
                <!-- End of card body -->

                <!-- Card footer -->
                <div class="card-footer bg-transparent border-top-0 pb-3">
                  <a class="btn btn-warning" href="">Read More</a>
                </div>
                <!-- End of card footer -->
              </div>
            </div>
            <!-- End of first card -->

            <!-- Second Card -->
            <div class="col-lg-4">
              <div class="card h-100 shadow">
                <img class="card-img-top" src="./assets/img/home-page/activities-section/sample-img-1.jpg" alt="...">

                <!-- Card body -->
                <div class="card-body">
                  <h5 class="card-title">Activity Title</h5>
                  <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
                <!-- End of card body -->

                <!-- Card footer -->
                <div class="card-footer bg-transparent border-top-0 pb-3">
                  <a class="btn btn-warning" href="">Read More</a>
                </div>
                <!-- End of card footer -->
              </div>
            </div>
            <!-- End of second card -->

            <!-- Third Card -->
            <div class="col-lg-4">
              <div class="card h-100 shadow">
                <img class="card-img-top" src="./assets/img/home-page/activities-section/sample-img-1.jpg" alt="...">

                <!-- Card body -->
                <div class="card-body">
                  <h5 class="card-title">Activity Title</h5>
                  <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
                <!-- End of card body -->

                <!-- Card footer -->
                <div class="card-footer bg-transparent border-top-0 pb-3">
                  <a class="btn btn-warning" href="">Read More</a>
                </div>
                <!-- End of card footer -->
              </div>
            </div>
            <!-- End of third card -->
          </div>
        </div>
      </section>
      <!-- End of activities section -->

      <!-- News and updates section -->
      <!-- <section class="new-and-updates">

      </section> -->
      <!-- End of news and updates section -->

      <!-- Testimonial section -->
      <section class="testimonials py-5 text-center">
        <div class="container">
          <h2 class="mb-5">Testimonials</h2>

          <div class="row">
            <!-- First testimonial -->
            <div class="col-md-4 mb-5 mb-md-0">
              <!-- Testimonials image -->
              <div class="d-flex justify-content-center mb-4">
                <img class="rounded-circle" src="./assets/img/home-page/testimonials-section/haerin.jpg" alt="" width="150" height="150">
              </div>
              <!-- End of testimonial image -->

              <h5 class="mb-3">Haerin</h5>
              <p><i class="bi bi-quote pe-2"></i>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Nemo ipsam odio non voluptatum aliquam temporibus totam unde, amet, harum exercitationem ut debitis minus, doloremque a tempore dignissimos accusantium eos vero?</p>
            </div>
            <!-- End of first testimonial -->

            <!-- Second testimonial -->
            <div class="col-md-4 mb-5 mb-md-0">
              <!-- Testimonials image -->
              <div class="d-flex justify-content-center mb-4">
                <img class="rounded-circle" src="./assets/img/home-page/testimonials-section/danielle.jpg" alt="" width="150" height="150">
              </div>
              <!-- End of testimonial image -->

              <h5 class="mb-3">Danielle</h5>
              <p><i class="bi bi-quote pe-2"></i>Lorem ipsum dolor, sit amet consectetur adipisicing elit.</p>
            </div>
            <!-- End of second testimonial -->

            <!-- Third testimonial -->
            <div class="col-md-4 mb-5 mb-md-0">
              <!-- Testimonials image -->
              <div class="d-flex justify-content-center mb-4">
                <img class="rounded-circle" src="./assets/img/home-page/testimonials-section/hanni.jpg" alt="" width="150" height="150">
              </div>
              <!-- End of testimonial image -->

              <h5 class="mb-3">Hanni</h5>
              <p><i class="bi bi-quote pe-2"></i>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Aspernatur commodi ducimus eum possimus, soluta voluptate maxime repudiandae.</p>
            </div>
            <!-- End of third testimonial -->
          </div>
        </div>
      </section>
      <!-- End of testimonial section -->

      <!-- Join us section -->
      <section class="join-us py-5 text-center">
        <div class="container py-5">
          <h4 class="display-5 fw-bolder mt-5 mb-3">High Five! STAR Family!</h4>

          <p class="lead mb-5">Be one of the STMCP family. Start your adventure with us.</p>

          <a class="btn btn-warning btn-lg px-4 mb-5" href="join-us.php">Join Us</a>
        </div>
      </section>
      <!-- End of join us section -->
    </main>
    <!-- End of main -->

    <!-- Footer -->
    <footer class="py-5">
      <div class="container">
        <div class="row gx-md-3 gx-md-4">
          <!-- Grid column -->
          <div class="col-md-6 col-lg-3 mb-4">
            <h4 class="text-warning text-uppercase fw-bold mb-4">STMCP</h4>
            <p>People on their quest to share and explore the Philippines's greatest adventures.</p>
          </div>
          <!-- End of grid column -->

          <!-- Quick links column -->
          <div class="col-md-6 col-lg-3 mb-4">
            <h4 class="text-warning fw-bold mb-4">Quick Links</h4>
            <p>
              <a class="text-white text-decoration-none" href="./">Home</a>
            </p>
            <p>
              <a class="text-white text-decoration-none" href="">About</a>
            </p>
            <p>
              <a class="text-white text-decoration-none" href="">Activities</a>
            </p>
            <p>
              <a class="text-white text-decoration-none" href="">News and Updates</a>
            </p>
            <p>
              <a class="text-warning text-decoration-none" href="">Join Us</a>
            </p>
          </div>
          <!-- End of quick links column -->

          <!-- Contacts column -->
          <div class="col-md-6 col-lg-3 mb-4">
            <h4 class="text-warning fw-bold mb-4">Contact Us</h4>
            <p>Philippines</p>
            <p>0929404144626</p>
            <p>stmcp.highfive@gmail.com</p>
          </div>
          <!-- End of contacts column -->

          <!-- Follow us column -->
          <div class="col-md-6 col-lg-3 mb-4">
            <h4 class="text-warning fw-bold mb-4">Follow Us</h4>
            <p>
              <a class="text-white text-decoration-none" href="https://www.facebook.com/HighfiveFam/">Facebook</a>
            </p>
            <p>
              <a class="text-white text-decoration-none" href="https://www.youtube.com/@stmcpofficial6175">YouTube</a>
            </p>
            <p>
              <a class="text-white text-decoration-none" href="https://www.tiktok.com/@stmcphighfive">TikTok</a>
            </p>
          </div>
          <!-- End of follow us column -->

          <div class="col-12 text-center">
            &copy; 2025 <span class="text-warning">STMCP</span>. All rights reserved.
          </div>
        </div>
      </div>
    </footer>
    <!-- End of footer -->
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>

</html>