<div class="pcoded-main-container">
  <div class="pcoded-content">
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
      <!-- FORMULARIO -->
      <div class="flex flex-col w-1/3">
        <form id="formPersona" action="modulo-persona.php?action=registrar" method="POST" class="card table-card bg-white shadow-md p-6 text-xs  flex flex-col mb-2">
          <h3 class="text-2xl font-plain mb-4 text-sm text-gray-400">Datos personales</h3>

          <div class="card-body">
            <!-- DNI DE LA PERSONA -->
            <div class="mb-2 sm:w-1/3">
              <label for="dni" class="block mb-1 font-bold text-xs">DNI:</label>
              <input type="text" id="txt_dni" name="dni" class="border p-2 w-full text-xs rounded-md" maxlength="8" pattern="\d{1,8}" inputmode="numeric" title="Ingrese solo dígitos" required oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Ingrese DNI">
            </div>

            <!-- NOMBRES DE LA PERSONA -->
            <div class="mb-2 sm:w-1/2">
              <label for="nombre" class="block mb-1 font-bold text-xs">Nombres:</label>
              <input type="text" id="txt_nombre" name="nombre" class="border p-2 w-full text-xs rounded-md" pattern="[A-Za-z]+" title="Ingrese solo letras" placeholder="Ingrese nombres" required>
            </div>

            <div class="flex flex-wrap -mx-2">
              <!-- APELLIDO PATERNO -->
              <div class="w-full sm:w-1/2 px-2 mb-2">
                <label for="apellidoPaterno" class="block mb-1 font-bold text-xs">Apellido Paterno:</label>
                <input type="text" id="txt_apellidoPaterno" name="apellidoPaterno" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese apellido paterno" required>
              </div>

              <!-- APELLIDO MATERNO -->
              <div class="w-full sm:w-1/2 px-2 mb-2">
                <label for="apellidoMaterno" class="block mb-1 font-bold text-xs">Apellido Materno:</label>
                <input type="text" id="txt_apellidoMaterno" name="apellidoMaterno" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese apellido materno" required>
              </div>
            </div>

            <div class="flex flex-wrap -mx-2">
              <!-- CELULAR -->
              <div class="w-full sm:w-1/3 px-2 mb-2">
                <label for="celular" class="block mb-1 font-bold text-xs">Celular:</label>
                <input type="tel" id="txt_celular" name="celular" class="border p-2 w-full text-xs rounded-md" maxlength="9" pattern="\d{1,9}" inputmode="numeric" title="Ingrese el número de celular" required oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Ingrese celular">
              </div>

              <!-- EMAIL -->
              <div class="w-full sm:w-2/3 px-2 mb-2">
                <label for="email" class="block mb-1 font-bold text-xs">Email:</label>
                <input type="email" id="txt_email" name="email" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese email" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Ingrese un correo electrónico válido.">
              </div>
            </div>

            <!-- BOTONES DEL FORMULARIO -->
            <div class="flex justify-center space-x-4 mt-3">
              <button type="submit" id="guardar-persona" class="btn-primary text-white font-bold py-2 px-4 rounded-md">
                Guardar
              </button>
              <button type="button" id="editar-persona" class="bg-blue-500 text-white font-bold hover:bg-blue-600 py-2 px-4 rounded-md">
                Editar
              </button>
              <button type="reset" id="nuevo-registro" class="bg-gray-500 text-white font-bold hover:bg-gray-600 py-2 px-4 rounded-md">
                Nuevo
              </button>
            </div>
          </div>
        </form>
      </div>


      <!-- TABLA DE PERSONAS -->
      <div class="w-2/3">

        <!-- Buscador de personas -->
        <div class="flex justify-between items-center mt-2">
          <input type="text" id="searchInput" class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-lime-300 text-xs" placeholder="Buscar persona..." oninput="filtrarTablaPersonas()" />
        </div>

        <!-- Tabla de personas -->
        <div class="relative max-h-[800px] mt-2 overflow-x-hidden shadow-md sm:rounded-lg">
          <table class="w-full text-xs text-left rtl:text-right text-gray-500">
            <thead class="sticky top-0 text-xs text-gray-70 uppercase bg-lime-300">
              <tr>
                <th scope="col" class="px-6 py-1">N°</th>
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
                echo "<tr class='bg-white hover:bg-green-100 hover:scale-[101%] transition-all hover:cursor-pointer border-b'>";
                echo "<th scope='col' class='px-6 py-3 font-medium text-gray-900 whitespace-nowrap' data-cod='" . htmlspecialchars($persona['PER_codigo']) . "'>";
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
<script src="https://cdn.tailwindcss.com"></script>