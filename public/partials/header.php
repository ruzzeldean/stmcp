<header>
  <nav class="navbar navbar-expand-lg bg-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="./">
        <img class="d-inline-block align-text-top" src="../../src/images/logo/stmcp_official_back_logo.png" alt="STMCP Logo" width="32" height="31">
        STMCP
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#navMenu" aria-controls="navMenu" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="offcanvas offcanvas-end" tabindex="-1" id="navMenu" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title pe-5" id="offcanvasNavbarLabel">STMCP</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body pe-5 pe-lg-0">
          <ul class="navbar-nav justify-content-end flex-grow-1">
            <li class="nav-item">
              <a class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>" <?= $currentPage === 'about.php' ? 'aria-current="page"' : '' ?> href="./">Home</a>
            </li>

            <li class="nav-item">
              <a class="nav-link <?= $currentPage === 'about.php' ? 'active' : '' ?>" <?= $currentPage === 'about.php' ? 'aria-current="page"' : '' ?> href="./about.php">About</a>
            </li>

            <li class="nav-item">
              <a id="act-page" class="nav-link <?= $currentPage === 'activities.php' ? 'active' : '' ?>" <?= $currentPage === 'activities.php' ? 'aria-current="page"' : '' ?> href="./activities.php">Activities</a>
            </li>

            <li class="nav-item">
              <a class="nav-link <?= $currentPage === 'news_and_updates.php' ? 'active' : '' ?>" href="./news_and_updates.php">News and Updates</a>
            </li>

            <li class="nav-item">
              <a class="nav-link <?= $currentPage === 'join_us.php' ? 'active' : '' ?>" href="./join_us.php">Join Us</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</header>