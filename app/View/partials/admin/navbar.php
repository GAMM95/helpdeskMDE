<!doctype html>
<html lang="es">

<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>

<nav class="pcoded-navbar ">
  <div class="navbar-wrapper ">
    <div class="navbar-content scroll-div ">

      <div class="">
        <div class="main-menu-header">
          <i class='bx bxs-user icon-input icon text-gray-200 text-3xl'></i>
          <div class="user-details">
            <span>
              <?php
              echo htmlspecialchars($_SESSION['nombreDePersona'], ENT_QUOTES, 'UTF-8');
              ?>
            </span>
            <div id="more-details">
              <?php
              echo htmlspecialchars($_SESSION['area'], ENT_QUOTES, 'UTF-8');
              ?>
              <i class="fa fa-chevron-down m-l-5"></i>
            </div>
          </div>
        </div>
        <div class="collapse" id="nav-user-link">
          <ul class="list-unstyled">
            <li class="list-group-item"><a href="user-profile.html"><i class="feather icon-user m-r-5"></i>View Profile</a></li>
            <li class="list-group-item"><a href="#!"><i class="feather icon-settings m-r-5"></i>Settings</a></li>
            <li class="list-group-item"><a href="auth-normal-sign-in.html"><i class="feather icon-log-out m-r-5"></i>Logout</a></li>
          </ul>
        </div>
      </div>

      <ul class="nav pcoded-inner-navbar ">
        <li class="nav-item pcoded-menu-caption">
          <label>Navegaci&oacute;n</label>
        </li>
        <li class="nav-item">
          <a href="index.html" class="nav-link "><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Inicio</span></a>
        </li>
        <li class="nav-item pcoded-hasmenu">
          <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="feather icon-layout"></i></span><span class="pcoded-mtext">Registrar</span></a>
          <ul class="pcoded-submenu">
            <li><a href="layout-vertical.html" target="_blank">Incidencia</a></li>
            <li><a href="layout-horizontal.html" target="_blank">Recepcion</a></li>
            <li><a href="layout-horizontal.html" target="_blank">Cierre</a></li>
          </ul>
        </li>
        <li class="nav-item pcoded-menu-caption">
          <label>Consultas</label>
        </li>
        <li class="nav-item pcoded-hasmenu">
          <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">Consultar</span></a>
          <ul class="pcoded-submenu">
            <li><a href="bc_alert.html">Incidencias</a></li>
            <li><a href="bc_button.html">Recepciones</a></li>
            <li><a href="bc_button.html">Cierres</a></li>

          </ul>
        </li>

        <li class="nav-item pcoded-menu-caption">
          <label>Mantenedor</label>
        </li>
        <li class="nav-item">
          <a href="form_elements.html" class="nav-link "><span class="pcoded-micon"><i class="feather icon-file-text"></i></span><span class="pcoded-mtext">Forms</span></a>
        </li>
        <li class="nav-item">
          <a href="tbl_bootstrap.html" class="nav-link "><span class="pcoded-micon"><i class="feather icon-align-justify"></i></span><span class="pcoded-mtext">Bootstrap
              table</span></a>
        </li>
      </ul>

    </div>
  </div>
</nav>