<header class="navbar pcoded-header navbar-expand-lg navbar-light header-dark fixed top-0 left-0 right-0 z-50">
  <div class="m-header">
    <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
    <a href="#!" class="b-brand">
      <!-- ========   change your logo hear   ============ -->
      <img src="dist/assets/images/logo.png" alt="" class="logo">
    </a>
  </div>
  <div class="navbar-collapse">
    <ul class="navbar-nav mr-auto">
      <!-- <li class="nav-item">
        <a href="#!" class="pop-search"><i class="feather icon-search"></i></a>
        <div class="search-bar">
          <input type="text" class="form-control border-0 shadow-none" placeholder="Buscar">
          <button type="button" class="close" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </li> -->
      <li class="nav-item ixed flex items-center">
        <div class="dropdown">
          <span class="dropdown-toggle h-drop" href="#" data-toggle="dropdown">
            Sistema HelpDesk de la Municipalidad Distrital de La Esperanza
          </span>
        </div>
      </li>

    </ul>
    <ul class="navbar-nav ml-auto">
      <li>
        <div class="dropdown">
          <a class="dropdown-toggle" href="#" data-toggle="dropdown">
            <i class="icon feather icon-bell"></i>
            <span class="badge badge-pill badge-danger">5</span>
          </a>
          <div class="dropdown-menu dropdown-menu-right notification">
            <div class="noti-head">
              <h6 class="d-inline-block m-b-0">Notificaciones</h6>
              <div class="float-right">
                <!-- <a href="#!" class="m-r-10">mark as read</a>
                <a href="#!">clear all</a> -->
              </div>
            </div>
            <ul class="noti-body">
              <li class="n-title">
                <p class="m-b-0">Nuevos</p>
              </li>
              <li class="notification">
                <div class="media">
                  <img class="img-radius" src="dist/assets/images/user/avatar.jpg" alt="User-Profile-Image">
                  <div class="media-body">
                    <p><strong>John Doe</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>5 min</span></p>
                    <p>New ticket Added</p>
                  </div>
                </div>
              </li>
              <li class="n-title">
                <p class="m-b-0">EARLIER</p>
              </li>
              <li class="notification">
                <div class="media">
                  <img class="img-radius" src="dist/assets/images/user/avatar.jpg" alt="User-Profile-Image">
                  <div class="media-body">
                    <p><strong>Joseph William</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>10 min</span></p>
                    <p>Prchace New Theme and make payment</p>
                  </div>
                </div>
              </li>
              <li class="notification">
                <div class="media">
                  <img class="img-radius" src="dist/assets/images/user/avatar.jpg" alt="User-Profile-Image">
                  <div class="media-body">
                    <p><strong>Sara Soudein</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>12 min</span></p>
                    <p>currently login</p>
                  </div>
                </div>
              </li>
              <li class="notification">
                <div class="media">
                  <img class="img-radius" src="dist/assets/images/user/avatar.jpg" alt="User-Profile-Image">
                  <div class="media-body">
                    <p><strong>Joseph William</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>30 min</span></p>
                    <p>Prchace New Theme and make payment</p>
                  </div>
                </div>
              </li>
            </ul>
            <!-- <div class="noti-footer">
            <a href="#!">show all</a>
          </div> -->
          </div>
        </div>
      </li>
      <li>
        <div class="dropdown drp-user">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="feather icon-user"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right profile-notification">
            <div class="pro-head fixed flex items-center">
              <img class="img-radius" src="dist/assets/images/user/avatar.jpg" alt="User-Profile-Image">
              <span>
                <?php
                if (isset($_SESSION['nombreDePersona'])) {
                  echo '<span class="text-base">' . htmlspecialchars($_SESSION['nombreDePersona'], ENT_QUOTES, 'UTF-8') . '</span>';
                  echo '<br>';
                  echo '<span class="text-xs text-gray-100">' . htmlspecialchars($_SESSION['rol'], ENT_QUOTES, 'UTF-8') . '</span>';
                } else {
                  echo "Usuario no logueado";
                }
                ?>
              </span>
            </div>
            <ul class="pro-body">
              <li><a href="user-profile.html" class="dropdown-item"><i class="feather icon-user"></i>
                  Perfil</a></li>
              <li><a href="logout.php" class="dropdown-item"><i class="feather icon-log-out"></i>
                  Cerrar sesi&oacute;n</a></li>
            </ul>
          </div>
        </div>
      </li>
    </ul>
  </div>
</header>