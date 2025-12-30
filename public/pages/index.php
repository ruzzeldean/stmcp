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
          <div class="row">
            <div class="col-xl-8 d-flex justify-content-center align-items-center mt-5">
              <div id="hero-content" class="text-center text-xl-start">
                <h1 class="display-3 fw-semibold">Star Touring Motorcycle Club Philippines</h1>

                <p class="lead text-muted fs-4 fw-normal mb-4">People on their quest to share and explore the Philippines's greatest adventures.</p>

                <div class="d-flex gap-3 justify-content-center justify-content-xl-start">
                  <a class="btn btn-warning btn-lg" href="./join_us.php">Join us</a>
                  <a class="btn btn-outline-light btn-lg" href="./about.php">About us</a>
                </div>
              </div>
            </div>

            <div class="d-none d-xl-block col-xl-4 mt-5">
              <div id="hero-img" class="d-flex justify-content-center align-items-center">
                <img id="hero-logo" class="img-fluid" src="../../src/images/logo/stmcp_official_back_logo.png" alt="">
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="py-5">
        <div class="container">
          <h2 class="text-center display-3 fw-semibold mb-5">About STMCP</h2>

          <div class="row row-gap-3">
            <div class="col-md-6">
              <div>
                <img class="img-fluid rounded" src="../../src/images/logo/one_star_logo.png" alt="STMCP One Star Logo">
              </div>
            </div>

            <div class="col-md-6">
              <div class="d-flex flex-column justify-content-center h-100">
                <p class="lead fs-4 mb-4"><span class="text-warning">STMCP</span> is a motorcycle club driven by passion, camaraderie, and a commitment to helping others — combining the thrill of the ride with meaningful community service.</p>

                <div class="text-center text-md-start">
                  <a class="btn btn-outline-light btn-lg" href="./about.php">More about us</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="bg-body-tertiary py-5">
        <div class="container pb-4">
          <h2 class="text-center display-3 fw-semibold mb-5">Posts</h2>

          <div class="row row-gap-4">
            <div class="col-md-6">
              <div class="h-100">
                <div class="card overflow-hidden h-100">
                  <div class="overflow-hidden">
                    <img class="card-img-top post-thumbnail" src="../assets/images/home_page/post_section/club_activities_cover.jpg" alt="Post thumbnail">
                  </div>

                  <div class="card-body d-flex flex-column">
                    <h3 class="card-title mb-4">Club Activities</h3>

                    <p class="card-text pb-3">At Star Touring Motorcycle Club Philippines, we take pride in making a positive impact on the community.</p>

                    <a class="btn btn-secondary mt-auto w-100" href="./activities.php">View Activities</a>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="h-100">
                <div class="card overflow-hidden h-100">
                  <div class="overflow-hidden">
                    <img class="card-img-top post-thumbnail" src="../assets/images/home_page/post_section/news_and_updates_cover.jpg" alt="Post thumbnail">
                  </div>

                  <div class="card-body d-flex flex-column">
                    <h3 class="card-title mb-4">News and Updates</h3>

                    <p class="card-text pb-3">Stay updated with the latest news, events, and activities of the Star Touring Motorcycle Club Philippines.</p>

                    <a class="btn btn-secondary mt-auto w-100" href="./news_and_updates.php">View News and Updates</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="join-us-section" class="py-5">
        <div class="container my-5 py-5 text-center">
          <h4 class="display-3 fw-semibold mb-3">High Five! STAR Family!</h4>
          <p class="lead fs-4 fw-normal mb-5">Be one of the STMCP family. Start your adventure with us.</p>

          <button class="btn btn-warning btn-lg px-4">Join Us</button>
        </div>
      </section>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>
  </div>

  <?php include __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>