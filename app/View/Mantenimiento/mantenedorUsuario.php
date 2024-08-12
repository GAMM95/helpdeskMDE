<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <?php
    global $usuarioRegistrado;
    require_once './app/Model/UsuarioModel.php';
    $usuarioModel = new UsuarioModel();
    $limit = 5; // Numero de filas por pagina
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; //pagina actual
    $start = ($page - 1) * $limit; // Calcula el índice de inicio

    // Obtener el total de registros
    $totalUsuarios = $usuarioModel->contarUsuarios();
    $totalPages = ceil($totalUsuarios / $limit);

    $usuarios = $usuarioModel->listarUsuarios($start, $limit);
    ?>

    <!-- Miga de pan -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Registro de usuarios</h1>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href=""><i class="feather icon-server"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Mantenedor</a></li>
              <li class="breadcrumb-item"><a href="">Usuarios</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de miga de pan -->

    <div class="flex space-x-4">
      <!-- Inicio de Formulario -->
      <div class="flex flex-col w-1/3">
        <form id="formUsuario" action="modulo-usuario.php?action=registrar" method="POST" class="card table-card  bg-white shadow-md p-6  text-xs  flex flex-col mb-2">
          <input type="hidden" id="form-action" name="action" value="registrar">
          <!-- Subtitulo -->
          <h3 class="text-2xl font-plain mb-4 text-xs text-gray-400">Informaci&oacute;n del usuario</h3>

          <!-- Inicio de Card de formulario -->
          <div class="card-body">
            <!-- CAMPO ESCONDIDO -->
            <div class="flex justify-center -mx-2 ">
              <!-- CODIGO DE USUARIO -->
              <div class="w-full sm:w-1/4 px-2 mb-2">
                <div class="flex items-center">
                  <label for="CodUsuario" class="block font-bold mb-1 mr-3 text-lime-500">C&oacute;digo de Usuario:</label>
                  <input type="text" id="CodUsuario" name="CodUsuario" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center">
                </div>
              </div>
            </div>

            <!-- SELECCION DE PERSONA -->
            <div class="mb-4">
              <label for="persona" class="block text-gray-700 font-bold mb-2">Trabajador:</label>
              <select id="cbo_persona" name="persona" class="border p-2 w-full text-xs cursor-pointer rounded-md">
                <option value="" selected disabled>Seleccione una persona</option>
              </select>
            </div>

            <!-- Seleccion de area -->
            <div class="mb-4">
              <label for="area" class="block text-gray-700 font-bold mb-2">&Aacute;rea:</label>
              <select id="cbo_area" name="area" class="border p-2 w-full text-xs cursor-pointer rounded-md">
                <option value="" selected disabled>Seleccione un área</option>
              </select>
            </div>

            <!-- Seleccion de rol -->
            <div class="mb-4">
              <label for="rol" class="block text-gray-700 font-bold mb-2">Rol:</label>
              <select id="cbo_rol" name="rol" class="border p-2 w-full text-xs cursor-pointer rounded-md">
                <option value="" selected disabled>Seleccione un rol</option>
              </select>
            </div>

            <!-- Input Usuario y Contraseña -->
            <div class="flex flex-wrap -mx-2">
              <!-- NOMBRE DE USUARIO -->
              <div class="w-full sm:w-1/2 px-2 mb-2">
                <label for="username" class="block mb-1 font-bold text-xs">Usuario:</label>
                <input type="text" id="username" name="username" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese username" oninput="uppercaseInput(this)">

                <script>
                  function uppercaseInput(element) {
                    element.value = element.value.toUpperCase();
                  }
                </script>
              </div>

              <!-- CONTRASEÑA -->
              <div class="w-full sm:w-1/2 px-2 mb-2">
                <label for="password" class="block mb-1 font-bold text-xs">Contrase&ntilde;a:</label>
                <input type="text" id="password" name="password" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese contraseña">
              </div>
            </div>

            <!-- Funciones -->
            <script>
              document.getElementById('CodUsuario').value = '<?php echo $usuarioRegistrado ? $usuarioRegistrado['USU_codigo'] : ''; ?>';
              document.getElementById('cbo_persona').value = '<?php echo $usuarioRegistrado ? $usuarioRegistrado['PER_codigo'] : ''; ?>';
              document.getElementById('cbo_area').value = '<?php echo $usuarioRegistrado ? $usuarioRegistrado['ARE_codigo'] : ''; ?>';
              document.getElementById('cbo_rol').value = '<?php echo $usuarioRegistrado ? $usuarioRegistrado['ROL_codigo'] : ''; ?>';
              document.getElementById('username').value = '<?php echo $usuarioRegistrado ? $usuarioRegistrado['USU_nombre'] : ''; ?>';
              document.getElementById('password').value = '<?php echo $usuarioRegistrado ? $usuarioRegistrado['USU_password'] : ''; ?>';
            </script>

            <!-- Botones del formulario -->
            <div class="flex justify-center space-x-4 mt-3 ">
              <button type="submit" id="guardar-usuario" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md"><i class="feather mr-2 icon-save"></i>Guardar</button>
              <button type="button" id="editar-usuario" class="bn btn-info text-xs text-white font-bold py-2 px-3 rounded-md" disabled><i class="feather mr-2 icon-edit"></i>Editar</button>
              <button type="button" id="nuevo-registro" class="bn btn-secondary text-xs text-white font-bold py-2 px-3 rounded-md" disabled> <i class="feather mr-2 icon-plus-square"></i>Nuevo</button>
            </div>
            <!-- Fin botones -->
          </div>
          <!-- Fin de Card de formulario -->
        </form>
      </div>
      <!-- Fin de formulario -->

      <!-- Tabla de usuarios -->
      <div class="w-2/3">
        <div class="flex justify-between items-center mt-2">
          <input type="text" id="searchInput" class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-lime-300" placeholder="Buscar persona..." oninput="filtrarTablaPersonas()" />
          <!-- Paginacion -->
          <?php if ($totalPages > 0) : ?>
            <div class="flex justify-end items-center mt-1">
              <?php if ($page > 1) : ?>
                <a href="#" class="px-4 py-2 bg-gray-200 text-gray-800 hover:bg-gray-300" onclick="changePageTablaUsuarios(<?php echo $page - 1; ?>)">&lt;</a>
              <?php endif; ?>
              <span class="mx-2">P&aacute;gina <?php echo $page; ?> de <?php echo $totalPages; ?></span>
              <?php if ($page < $totalPages) : ?>
                <a href="#" class="px-4 py-2 bg-gray-200 text-gray-800 hover:bg-gray-300" onclick="changePageTablaUsuarios(<?php echo $page + 1; ?>)">&gt;</a>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
        <!-- Fin de paginacion -->

        <!-- TABLA DE USUARIOS -->
        <div class="relative max-h-[800px] mt-2 overflow-x-hidden shadow-md sm:rounded-lg bg-white">
          <table id="tablaListarUsuarios" class="w-full text-xs text-left rtl:text-right text-gray-500">
            <!-- Encabezado -->
            <thead class="sticky top-0 text-xs text-gray-70 uppercase bg-lime-300">
              <tr>
                <th scope="col" class="px-6 py-2 hidden">N°</th>
                <th scope="col" class="px-6 py-2">Trabajador</th>
                <th scope="col" class="px-6 py-2">&Aacute;rea</th>
                <th scope="col" class="px-6 py-2">Usuario</th>
                <th scope="col" class="px-6 py-2">Contrase&ntilde;a</th>
                <th scope="col" class="px-6 py-2">Rol</th>
                <th scope="col" class="px-6 py-2">Estado</th>
              </tr>
            </thead>
            <!-- Fin de encabezado -->

            <!-- Inicio de cuerpo de tabla -->
            <tbody>
              <?php if (!empty($usuarios)) : ?>
                <?php foreach ($usuarios as $usuario) : ?>
                  <?php
                  $estado = htmlspecialchars($usuario['EST_descripcion']);
                  $isActive = ($estado === 'Activo');
                  ?>
                  <tr class=" hover:bg-green-100 hover:scale-[101%] transition-all hover:cursor-pointer border-b">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap hidden"><?= htmlspecialchars($usuario['USU_codigo']); ?></th>
                    <td class="px-6 py-3"><?= htmlspecialchars($usuario['persona']); ?></td>
                    <td class="px-6 py-3"><?= htmlspecialchars($usuario['ARE_nombre']); ?></td>
                    <td class="px-6 py-3"><?= htmlspecialchars($usuario['USU_nombre']); ?></td>
                    <td class="px-6 py-3"><?= htmlspecialchars($usuario['USU_password']); ?></td>
                    <td class="px-6 py-3"><?= htmlspecialchars($usuario['ROL_nombre']); ?></td>
                    <td class="px-6 py-3">
                      <div class="custom-control custom-switch cursor-pointer">
                        <input type="checkbox" class="custom-control-input" id="customswitch<?= $usuario['USU_codigo']; ?>" <?= $isActive ? 'checked' : ''; ?>>
                        <label class="custom-control-label" for="customswitch<?= $usuario['USU_codigo']; ?>"><?= $isActive ? 'Activo' : 'Inactivo'; ?></label>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
                <tr>
                  <td colspan="7" class="text-center py-4">No hay usuarios</td>
                </tr>
              <?php endif; ?>
            </tbody>
            <!-- Fin de cuerpo de tabla -->
          </table>
        </div>
      </div>
      <!-- Fin de la Tabla de usuarios -->

    </div>
  </div>
</div>
<script src="https://cdn.tailwindcss.com"></script>


<script>
  function toggleUsuario(USU_codigo, checkbox) {
    var action = checkbox.checked ? 'habilitar' : 'deshabilitar';

    $.ajax({
      url: 'app/Controller/usuarioController.php',
      type: 'POST',
      data: {
        action: action,
        USU_codigo: USU_codigo
      },
      success: function(response) {
        var result = JSON.parse(response);
        if (result.status === 'success') {
          toastr.success(result.message);
          checkbox.nextElementSibling.textContent = checkbox.checked ? 'Activo' : 'Inactivo';
        } else {
          toastr.error(result.message);
          checkbox.checked = !checkbox.checked; // Revert the checkbox state
        }
      },
      error: function(xhr, status, error) {
        toastr.error('Error en la solicitud: ' + error);
        checkbox.checked = !checkbox.checked; // Revert the checkbox state
      }
    });
  }
</script>