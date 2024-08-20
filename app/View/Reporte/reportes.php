<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <!-- Miga de pan -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Generaci&oacute;n de reportes</h1>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href=""><i class="feather icon-file"></i></a></li>
              <li class="breadcrumb-item"><a href="reportes.php">Reportes</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de miga de pan -->

    <!-- Formulario Consulta de cierres -->
    <form id="formReportes" action="reportes.php?action=consultar" method="POST" class="card table-card bg-white shadow-md p-6 w-full text-xs mb-2">
      <div class="flex flex-wrap -mx-2 justify-center">
        <!-- Reportes generales -->
        <div class="col-md-6">
          <h5 class="mb-1 text-lg text-bold">Reportes generales</h5>
          <hr class="mb-2">

          <!-- Botones -->
          <div class="flex justify-center space-x-2 mt-10">
            <button type="button" id="reporte-incidencias-totales" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md"><i class="feather mr-2 icon-file"></i>Incidencias totales</button>
            <button type="button" id="reporte-cierres-totales" class="bn btn-secondary text-xs text-white font-bold py-2 px-3 rounded-md"> <i class="feather mr-2 icon-file"></i>Pendientes de cierre</button>
          </div>
        </div>
        <!-- Fin de reportes generales -->

        <!-- Reportes por fecha -->
        <div class="col-md-6">
          <h5 class="mb-1 text-lg text-bold">Reportes por fecha</h5>
          <hr class="mb-2">

          <!-- Contenedor para alinear los inputs horizontalmente -->
          <div class="flex justify-center space-x-4 mt-4 mr-5 ml-5">
            <!-- BUSCAR POR FECHA DE INICIO -->
            <div class="w-full">
              <label for="fechaInicio" class="block mb-1 text-center font-bold text-xs">Fecha Inicio:</label>
              <input type="date" id="fechaInicio" name="fechaInicio" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
            </div>

            <!-- Buscar por fecha fin -->
            <div class="w-full">
              <label for="fechaFin" class="block mb-1 text-center font-bold text-xs">Fecha Fin:</label>
              <input type="date" id="fechaFin" name="fechaFin" class="w-full border p-2 text-xs cursor-pointer text-center rounded-md" max="<?php echo date('Y-m-d'); ?>">
            </div>
          </div>


          <!-- Botones -->
          <div class="flex justify-center space-x-2 mt-4">
            <button type="button" id="reporte-incidencias-fechas" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md"> <i class="feather mr-2 icon-printer"></i>Incidencias </button>
            <button type="button" id="reportes-cierres-fechas" class="bn btn-secondary text-xs text-white font-bold py-2 px-3 rounded-md"> <i class="feather mr-2 icon-printer"></i>Cierres </button>
          </div>
        </div>
        <!-- Fin de reporte por fechas -->


        <!-- Reportes por area -->
        <div class="col-md-6 mt-5">
          <h5 class="mb-1 text-lg text-bold">Reportes por &aacute;rea</h5>
          <hr class="mb-2">
          <!-- Contenedor para alinear los inputs horizontalmente -->
          <div class="flex justify-center space-x-4 mt-4">
            <!-- Buscar por area -->
            <div class="w-full md:w-1/1 px-2 mb-2">
              <!-- <label for="area"class="block font-bold mb-1 text-[#32cfad]">&Aacute;rea:</label> -->
              <select id="area" name="area" class="border p-2 w-full text-xs cursor-pointer">
              </select>
            </div>
          </div>
          <!-- Botones -->
          <div class="flex justify-center space-x-2 mt-4">
            <button type="button" id="reportes-areas" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md"><i class="feather mr-2 icon-printer"></i>Imprimir</button>
          </div>
        </div>
        <!-- Fin de reporte por areas -->

        <!-- Reportes por codigo patrimonial -->
        <div class="col-md-6 mt-5">
          <h5 class="mb-1 text-lg text-bold">Reportes por c&oacute;digo patrimonial</h5>
          <hr class="mb-2">
          <div class="flex justify-center space-x-4 mt-4">
            <div class="text-center w-full md:w-3/4"> <!-- Ajuste de ancho con md:w-2/3 -->
              <input type="text" id="codigoPatrimonial" name="codigoPatrimonial" class="border p-2 w-full text-xs rounded-md" maxlength="12" pattern="\d{1,12}" inputmode="numeric" title="Ingrese solo dígitos" placeholder="Ingrese c&oacute;digo patrimonial">
            </div>
          </div>
          <!-- Botones -->
          <div class="flex justify-center space-x-2 mt-4">
            <button type="button" id="reportes-codigoPatrimonial" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md"><i class="feather mr-2 icon-printer"></i>Imprimir</button>
          </div>
        </div>
        <!-- Fin de reporte por codigo patrimonial -->

      </div>
    </form>
    <!-- Fin de formulario de consultas -->
  </div>
</div>

<script src="https://cdn.tailwindcss.com"></script>