<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Star Touring Motorcycle Club Philippines</title>
  <link rel="shortcut icon" href="./assets/img/logo/logo.png" type="image/x-icon">
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
                <h1 class="display-5 fw-bolder text-white mb-2">Star Touring Motorcycle Club Philippines</h1>

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
              <img class="img-fluid rounded" src="./assets/img/about-us-4-pillars.jpg" alt="Image of 4 pillars">
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
      <section class="activities dark-section py-5">
        <div class="container">
          <h2>Activities</h2>

          
        </div>
      </section>
      <!-- End of activities section -->
    </main>
    <!-- End of main -->

    <footer>
      <p>Footer</p>
    </footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>

</html>