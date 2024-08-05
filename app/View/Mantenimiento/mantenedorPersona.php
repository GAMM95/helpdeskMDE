<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <?php
    global $personaRegistrada;
    ?>

    <!-- Miga de pan -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Registro de personas</h1>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href=""><i class="feather icon-server"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Mantenedor</a></li>
              <li class="breadcrumb-item"><a href="">Personas</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de miga de pan -->

    <!-- Contenedor principal para el formulario y la tabla -->
    <div class="flex space-x-4">
      <!-- Inicio de Formulario de registro de personas -->
      <div class="flex flex-col w-1/3">
        <form id="formPersona" action="modulo-persona.php?action=registrar" method="POST" class="card table-card bg-white shadow-md p-6 text-xs  flex flex-col mb-2">
          <input type="hidden" id="form-action" name="action" value="registrar">

          <h3 class="text-2xl font-plain mb-4 text-xs text-gray-400">Datos personales</h3>

          <div class="card-body">
            <!-- PRIMERA FILA -->
            <div class="flex justify-center -mx-2 hidden">
              <div class="flex items-center mb-4">
                <div class="flex items-center">
                  <label for="CodPersona" class="block font-bold mb-1 mr-3 text-lime-500">C&oacute;digo de persona:</label>
                  <input type="text" id="CodPersona" name="CodPersona" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center" readonly>
                </div>
              </div>
            </div>

            <!-- DNI DE LA PERSONA -->
            <!-- <div class="mb-2 sm:w-1/3">
              <label for="dni" class="block mb-1 font-bold text-xs">DNI: *</label>
              <div class="relative">
                <input type="text" id="dni" name="dni" class="border p-2 w-full text-xs rounded-md pl-10" maxlength="8" pattern="\d{1,8}" autofocus inputmode="numeric" title="Ingrese solo dígitos" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Ingrese DNI">
              </div>
            </div> -->
            <div class="mb-2 sm:w-1/3">
              <label for="dni" class="block mb-1 font-bold text-xs">DNI: *</label>
              <div class="relative">
                <input type="text" id="dni" name="dni" class="border p-2 w-full text-xs rounded-md pl-10" maxlength="8" pattern="\d{1,8}" autofocus inputmode="numeric" title="Ingrese solo dígitos" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Ingrese DNI">
                <span id="dni-status" class="absolute inset-y-0 right-0 pr-3 flex items-center"></span>
              </div>

            </div>

            <!-- NOMBRES DE LA PERSONA -->
            <div class="mb-2 sm:w-1/2">
              <label for="nombres" class="block mb-1 font-bold text-xs">Nombres: *</label>
              <input type="text" id="nombres" name="nombres" class="border p-2 w-full text-xs rounded-md" title="Ingrese solo letras" placeholder="Ingrese nombres">
            </div>

            <div class="flex flex-wrap -mx-2">
              <!-- APELLIDO PATERNO -->
              <div class="w-full sm:w-1/2 px-2 mb-2">
                <label for="apellidoPaterno" class="block mb-1 font-bold text-xs">Apellido Paterno: *</label>
                <input type="text" id="apellidoPaterno" name="apellidoPaterno" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese apellido paterno">
              </div>

              <!-- APELLIDO MATERNO -->
              <div class="w-full sm:w-1/2 px-2 mb-2">
                <label for="apellidoMaterno" class="block mb-1 font-bold text-xs">Apellido Materno: *</label>
                <input type="text" id="apellidoMaterno" name="apellidoMaterno" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese apellido materno">
              </div>
            </div>

            <div class="flex flex-wrap -mx-2">
              <!-- CELULAR -->
              <div class="w-full sm:w-1/3 px-2 mb-2">
                <label for="celular" class="block mb-1 font-bold text-xs">Celular:</label>
                <input type="tel" id="celular" name="celular" class="border p-2 w-full text-xs rounded-md" maxlength="9" pattern="\d{1,9}" inputmode="numeric" placeholder="Ingrese celular">
              </div>

              <!-- EMAIL -->
              <div class="w-full sm:w-2/3 px-2 mb-2">
                <label for="email" class="block mb-1 font-bold text-xs">Email:</label>
                <input type="email" id="email" name="email" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese email">
              </div>
            </div>

            <!-- Recopilacion de valores de cada input -->
            <script>
              document.getElementById('dni').value = '<?php echo $personaRegistrada ? $personaRegistrada['PER_dni'] : ''; ?>';
              document.getElementById('nombres').value = '<?php echo $personaRegistrada ? $personaRegistrada['PER_nombres'] : ''; ?>';
              document.getElementById('apellidoPaterno').value = '<?php echo $personaRegistrada ? $personaRegistrada['PER_apellidoPaterno'] : ''; ?>';
              document.getElementById('apellidoMaterno').value = '<?php echo $personaRegistrada ? $personaRegistrada['PER_apellidoMaterno'] : ''; ?>';
              document.getElementById('celular').value = '<?php echo $personaRegistrada ? $personaRegistrada['PER_celular'] : ''; ?>';
              document.getElementById('email').value = '<?php echo $personaRegistrada ? $personaRegistrada['PER_email'] : ''; ?>';
            </script>

            <!-- BOTONES DEL FORMULARIO -->
            <div class="flex justify-center space-x-4 mt-3">
              <button type="submit" id="guardar-persona" class="btn-primary text-white font-bold py-2 px-4 rounded-md"> Guardar </button>
              <button type="button" id="editar-persona" class="bg-blue-500 text-white font-bold hover:bg-blue-600 py-2 px-4 rounded-md"> Editar </button>
              <button type="reset" id="nuevo-registro" class="bg-gray-500 text-white font-bold hover:bg-gray-600 py-2 px-4 rounded-md"> Nuevo </button>
            </div>
          </div>
        </form>
      </div>
      <!-- Fin de formulario -->

      <!-- TABLA DE PERSONAS -->
      <div class="w-2/3">

        <!-- Buscador de personas -->
        <div class="flex justify-between items-center mt-2">
          <input type="text" id="searchInput" class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-lime-300 text-xs" placeholder="Buscar trabajador..." oninput="filtrarTablaPersonas()" />
        </div>

        <!-- Tabla de personas -->
        <div class="relative max-h-[800px] mt-2 overflow-x-hidden shadow-md sm:rounded-lg bg-white ">
          <table class="w-full text-xs text-left rtl:text-right text-gray-500 ">
            <thead class="sticky top-0 text-xs text-gray-70 uppercase bg-lime-300">
              <tr>
                <th scope="col" class="px-6 py-1 hidden">N°</th>
                <th scope="col" class="px-6 py-1">DNI</th>
                <th scope="col" class="px-6 py-2">Nombre completo</th>
                <th scope="col" class="px-6 py-2">Celular</th>
                <th scope="col" class="px-6 py-2">Email</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $personas = $personaModel->listarPersona();
              foreach ($personas as $persona) {
                echo "<tr class='hover:bg-green-100 hover:scale-[101%] transition-all hover:cursor-pointer border-b'>";
                echo "<th scope='col' class='hidden px-6 py-3 font-medium text-gray-900 whitespace-nowrap' data-cod='" . htmlspecialchars($persona['PER_codigo']) . "'>";
                echo $persona['PER_codigo'];
                echo "</th>";
                echo "<td class='px-6 py-3' data-dni='" . htmlspecialchars($persona['PER_dni']) . "'>";
                echo $persona['PER_dni'];
                echo "</td>";
                echo "<td class='px-6 py-3' data-nombre='" . htmlspecialchars($persona['PER_nombres']) . "'>";
                echo $persona['PER_nombres'] . ' ' . $persona['PER_apellidoPaterno'] . ' ' . $persona['PER_apellidoMaterno'];
                echo "</td>";
                echo "<td class='px-6 py-3' data-celular='" . htmlspecialchars($persona['PER_celular']) . "'>";
                echo $persona['PER_celular'];
                echo "</td>";
                echo "<td class='px-6 py-3' data-email='" . htmlspecialchars($persona['PER_email']) . "'>";
                echo $persona['PER_email'];
                echo "</td>";
                echo "</tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>