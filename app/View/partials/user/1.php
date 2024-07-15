<!doctype html>
<html lang="es">
<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>
<!-- [ navigation menu ] start -->
<nav class="pcoded-navbar  ">
  <div class="navbar-wrapper  ">
    <div class="navbar-content scroll-div ">

      <div class="">
        <div class="main-menu-header">
          <!-- <img class="img-radius" src="dist/assets/images/user/avatar.jpg" alt="User-Profile-Image"> -->
          <img class="img-radius" src="public/assets/logo.ico">
          <div class="user-details">
            <?php
            if (isset($_SESSION['nombreDePersona'])) {
              echo htmlspecialchars($_SESSION['area'], ENT_QUOTES, 'UTF-8');
            } else {
              echo "Usuario no logueado";
            }
            ?>
          </div>
        </div>
        <div class="collapse" id="nav-user-link">
          <ul class="list-unstyled">
            <li class="list-group-item"><a href="user-profile.html"><i class="feather icon-user m-r-5"></i>View Profile</a></li>
            <li class="list-group-item"><a href="#!"><i class="feather icon-settings m-r-5"></i>Settings</a></li>
            <li class="list-group-item"><a href="logout.php"><i class="feather icon-log-out m-r-5"></i>Cerrar Sesi&oacute;n</a></li>
          </ul>
        </div>
      </div>

      <ul class="nav pcoded-inner-navbar ">
        <li class="nav-item pcoded-menu-caption">
          <label>Navegaci&oacute;n</label>
        </li>
        <li class="nav-item">
          <a href="inicio.php" class="nav-link ">
            <span class="pcoded-micon"> <i class="feather icon-home"></i></span>
            <span class="pcoded-mtext">Dashboard </span>
          </a>
        </li>
        <li class="nav-item pcoded-hasmenu">
          <a href="#!" class="nav-link ">
            <span class="pcoded-micon">
              <i class="feather icon-layout"></i>
            </span>
            <span class="pcoded-mtext">Registrar</span>
          </a>
          <ul class="pcoded-submenu">
            <li><a href="registro-incidencia-admin.php">Incidencia</a></li>
            <li><a href="registro-recepcion-admin.php">Recepcion</a></li>
            <li><a href="registro-cierre-admin.php">Cierre</a></li>
          </ul>
        </li>
        <li class="nav-item pcoded-hasmenu">
          <a href="#!" class="nav-link ">
            <span class="pcoded-micon">
              <i class="feather icon-layout"></i>
            </span>
            <span class="pcoded-mtext">Consultar</span>
          </a>
          <ul class="pcoded-submenu">
            <li><a href="layout-vertical.html">Incidencia</a></li>
            <li><a href="layout-horizontal.html">Recepcion</a></li>
            <li><a href="layout-horizontal.html">Cierre</a></li>
          </ul>
        </li>
        
        <li class="nav-item pcoded-menu-caption">
          <label>Reportes</label>
        </li>
        <li class="nav-item pcoded-hasmenu">
          <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">Basic</span></a>
          <ul class="pcoded-submenu">
            <li><a href="bc_alert.html">Personas</a></li>
            <li><a href="bc_button.html">Usuarios</a></li>
            <li><a href="bc_badges.html">Áreas</a></li>
            <li><a href="bc_badges.html">Categorías</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- [ navigation menu ] end -->