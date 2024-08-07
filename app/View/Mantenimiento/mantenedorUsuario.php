<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <?php
    global $usuarioRegistrado;
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
        <form id="formUsuario" action="modulo-usuario.php?action=registrar" method="POST" class="card table-card  bg-white shadow-md p-6  w-full text-xs ">
          <input type="hidden" id="form-action" name="action" value="registrar">

          <!-- CAMPO ESCONDIDO -->
          <div class="flex justify-center -mx-2 mb-5 hidden">
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

          <div class="mb-4">
            <label for="area" class="block text-gray-700 font-bold mb-2">&Aacute;rea:</label>
            <select id="cbo_area" name="area" class="border p-2 w-full text-xs cursor-pointer rounded-md">
              <option value="" selected disabled>Seleccione un área</option>
            </select>
          </div>
          <div class="mb-4">
            <label for="rol" class="block text-gray-700 font-bold mb-2">Rol:</label>
            <select id="cbo_rol" name="rol" class="border p-2 w-full text-xs cursor-pointer rounded-md">
              <option value="" selected disabled>Seleccione un rol</option>
            </select>
          </div>

          <div class="flex flex-wrap -mx-2">
            <!-- NOMBRE DE USUARIO -->
            <div class="w-full sm:w-1/2 px-2 mb-2">
              <label for="username" class="block mb-1 font-bold text-xs">Usuario:</label>
              <input type="text" id="username" name="username" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese username">
            </div>

            <!-- CONTRASEÑA -->
            <div class="w-full sm:w-1/2 px-2 mb-2">
              <label for="password" class="block mb-1 font-bold text-xs">Contrase&ntilde;a:</label>
              <input type="text" id="password" name="password" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese contraseña">
            </div>
          </div>

          <script>
            document.getElementById('CodUsuario').value = '<?php echo $usuarioRegistrado ? $usuarioRegistrado['USU_codigo'] : ''; ?>';
            document.getElementById('cbo_persona').value = '<?php echo $usuarioRegistrado ? $usuarioRegistrado['PER_codigo'] : ''; ?>';
            document.getElementById('cbo_area').value = '<?php echo $usuarioRegistrado ? $usuarioRegistrado['ARE_codigo'] : ''; ?>';
            document.getElementById('cbo_rol').value = '<?php echo $usuarioRegistrado ? $usuarioRegistrado['ROL_codigo'] : ''; ?>';
            document.getElementById('username').value = '<?php echo $usuarioRegistrado ? $usuarioRegistrado['USU_nombre'] : ''; ?>';
            document.getElementById('password').value = '<?php echo $usuarioRegistrado ? $usuarioRegistrado['USU_password'] : ''; ?>';
          </script>

          <!-- Botones del formulario -->
          <div class="flex justify-center space-x-4 mt-8 mb-2">
            <button type="submit" id="guardar-usuario" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md"><i class="feather mr-2 icon-save"></i>Guardar</button>
            <button type="button" id="editar-usuario" class="bn btn-info text-xs text-white font-bold py-2 px-3 rounded-md" disabled><i class="feather mr-2 icon-edit"></i>Editar</button>
            <button type="button" id="nuevo-registro" class="bn btn-secondary text-xs text-white font-bold py-2 px-3 rounded-md" disabled> <i class="feather mr-2 icon-plus-square"></i>Nuevo</button>
          </div>
          <!-- Fin botones -->
        </form>
      </div>
      <!-- Fin de formulario -->

      <?php
      require_once './app/Model/UsuarioModel.php';

      $usuarioModel = new UsuarioModel();
      $limit = 10; // Numero de filas por pagina
      $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; //pagina actual
      $start = ($page - 1) * $limit; // Calcula el índice de inicio

      // Obtener el total de registros
      $totalUsuarios = $usuarioModel->contarUsuarios();
      $totalPages = ceil($totalUsuarios / $limit);

      $usuarios = $usuarioModel->listarUsuarios($start, $limit);
      ?>

      <div class="w-2/3">
        <div class="flex justify-between items-center mt-2">
          <input type="text" id="searchInput" class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-lime-300" placeholder="Buscar persona..." oninput="filtrarTablaPersonas()" />

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

        <!-- TABLA DE USUARIOS -->
        <div class="relative max-h-[800px] mt-2 overflow-x-hidden shadow-md sm:rounded-lg bg-white">
          <table id="tablaListarUsuarios" class="w-full text-xs text-left rtl:text-right text-gray-500">
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
          </table>
        </div>
        <!-- Fin de la Tabla de usuarios -->

      </div>
    </div>
  </div>
</div>
<script src="https://cdn.tailwindcss.com"></script>


<script>
  // TODO: Seteo de valores en los inputs y combos
  document.addEventListener('DOMContentLoaded', (event) => {
    // Obtén todas las filas de la tabla
    const filas = document.querySelectorAll('#tablaListarUsuarios tbody tr');

    filas.forEach(fila => {
      fila.addEventListener('click', () => {
        // Obtén los datos de la fila
        const celdas = fila.querySelectorAll('td');

        // Mapea los valores de las celdas a los inputs del formulario
        const codUsuario = fila.querySelector('th').innerText.trim();
        const personaValue = celdas[0].innerText.trim();
        const areaValue = celdas[1].innerText.trim();
        const usernameValue = celdas[2].innerText.trim();
        const passwordValue = celdas[3].innerText.trim();
        const rolValue = celdas[4].innerText.trim();

        // Setear valores en los inputs
        document.getElementById('CodUsuario').value = codUsuario;
        document.getElementById('username').value = usernameValue;
        document.getElementById('password').value = passwordValue;

        // Debug: Verifica los valores que estás estableciendo
        console.log("CodUsuario:", codUsuario);
        console.log("Persona:", personaValue);
        console.log("Area:", areaValue);
        console.log("Username:", usernameValue);
        console.log("Password:", passwordValue);
        console.log("Rol:", rolValue);

        // Setear valores en los combos
        setComboValue('cbo_persona', personaValue);
        setComboValue('cbo_area', areaValue);
        setComboValue('cbo_rol', rolValue);

        // Cambiar estado de los botones
        document.getElementById('guardar-usuario').disabled = true;
        document.getElementById('editar-usuario').disabled = false;
        document.getElementById('nuevo-registro').disabled = false;
      });
    });
  });

  function setComboValue(comboId, value) {
    const select = document.getElementById(comboId);
    const options = select.options;

    console.log("Setting value for:", comboId, "Value:", value);

    // Verificar si el valor está en el combo
    let valueFound = false;
    for (let i = 0; i < options.length; i++) {
      if (options[i].text.trim() === value) {
        select.value = options[i].value;
        valueFound = true;
        break;
      }
    }

    // Si no se encontró el valor, seleccionar el primer elemento
    if (!valueFound) {
      select.value = ''; // O establece un valor predeterminado si lo deseas
    }

    // Forzar actualización del select2 para mostrar el valor seleccionado
    $(select).trigger('change');
  }
</script>