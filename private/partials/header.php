    <nav class="app-header navbar navbar-expand bg-body">
      <div class="container-fluid">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
              <i class="fa-solid fa-bars"></i>
            </a>
          </li>
        </ul>

        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown">
            <button
              class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center"
              id="bd-theme"
              type="button"
              aria-expanded="false"
              data-bs-toggle="dropdown"
              data-bs-display="static">
              <span class="theme-icon-active">
                <i class="my-1"></i>
              </span>
              <span class="d-lg-none ms-2" id="bd-theme-text">Toggle theme</span>
            </button>
            <ul
              class="dropdown-menu dropdown-menu-end"
              aria-labelledby="bd-theme-text"
              style="--bs-dropdown-min-width: 8rem;">
              <li>
                <button
                  type="button"
                  class="dropdown-item d-flex align-items-center active"
                  data-bs-theme-value="light"
                  aria-pressed="false">
                  <i class="fa-solid fa-sun me-2"></i>
                  Light
                  <i class="fa-regular fa-sun ms-auto d-none"></i>
                </button>
              </li>
              <li>
                <button
                  type="button"
                  class="dropdown-item d-flex align-items-center"
                  data-bs-theme-value="dark"
                  aria-pressed="false">
                  <i class="fa-solid fa-moon me-2"></i>
                  Dark
                  <i class="fa-regular fa-moon ms-auto d-none"></i>
                </button>
              </li>
              <li>
                <button
                  type="button"
                  class="dropdown-item d-flex align-items-center"
                  data-bs-theme-value="auto"
                  aria-pressed="true">
                  <i class="fa-solid fa-circle-half-stroke me-2"></i>
                  Auto
                  <i class="fa-solid fa-circle-half-stroke ms-auto d-none"></i>
                </button>
              </li>
            </ul>
          </li>

          <li class="nav-item dropdown user-menu">
            <a href="" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
              <img
                src="https://i.pravatar.cc/?img=3"
                class="user-image rounded-circle shadow"
                alt="User Image" />
              <span class="d-none d-md-inline"><?= e($_SESSION['full_name']) ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
              <!--begin::User Image-->
              <li class="user-header text-bg-primary">
                <img
                  src="https://i.pravatar.cc/?img=3"
                  class="rounded-circle shadow"
                  alt="User Image" />
                <p>
                  <?= e($_SESSION['full_name']) ?> - Web Developer
                  <small>Member since Nov. 2023</small>
                </p>
              </li>
              <!--end::User Image-->
              <!--begin::Menu Body-->
              <li class="user-body">
                <!--begin::Row-->
                <div class="row">
                  <div class="col-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!--end::Row-->
              </li>
              <!--end::Menu Body-->
              <!--begin::Menu Footer-->
              <li class="user-footer">
                <a href="#" class="btn btn-outline-secondary">Profile</a>
                <a href="../auth/logout.php" class="btn btn-outline-danger float-end">Logout</a>
              </li>
              <!--end::Menu Footer-->
            </ul>
          </li>
        </ul>
      </div>
    </nav>