<div class="pcoded-main-container ">
  <div class="pcoded-content">
    
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

      <!-- TODO: FORMULARIO -->
      <div class="flex flex-col w-1/3">
        <form id="formUsuario" action="modulo-usuario.php?action=registrar" method="POST" class="card table-card  bg-white shadow-md p-6  w-full text-xs ">
          <input type="hidden" id="form-action" name="action" value="registrar">

          <!-- CAMPO ESCONDIDO -->
          <div class="flex justify-center -mx-2 mb-5  hidden">
            <!-- CODIGO DE USUARIO -->
            <div class="w-full sm:w-1/4 px-2 mb-2">
              <div class="flex items-center">
                <label for="CodUsuario" class="block font-bold mb-1 mr-3 text-lime-500">C&oacute;digo de Usuario:</label>
                <input type="text" id="txt_codigoUsuario" name="CodUsuario" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-sm text-center">
              </div>
            </div>
          </div>

          <!-- SELECCION DE PERSONA -->
          <div class="MB-2">
            <div class="mb-2">
              <label for="persona" class="block mb-1 font-bold text-xs">Persona:</label>
              <select id="cbo_persona" name="persona" class="border p-2 w-full text-xs cursor-pointer"></select>
            </div>
          </div>

          <!-- SELECCION DE AREA -->
          <div class="mb-2">
            <label for="area" class="block mb-1 font-bold text-xs">&Aacute;rea:</label>
            <select id="cbo_area" name="area" class="border p-2 w-full text-xs cursor-pointer"></select>
          </div>

          <!-- SELECCION DE ROL -->
          <div class="w-full sm:w-1/2  mb-2">
            <label for="rol" class="block mb-1 font-bold text-xs">Rol:</label>
            <select id="cbo_rol" name="rol" class="border p-2 w-full text-xs cursor-pointer rounded-md"></select>
          </div>

          <div class="flex flex-wrap -mx-2">
            <!-- NOMBRE DE USUARIO -->
            <div class="w-full sm:w-1/2 px-2 mb-2">
              <label for="nombre" class="block mb-1 font-bold text-xs">Usuario:</label>
              <input type="text" id="txt_nombreUsuario" name="nombre" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese username">
            </div>

            <!-- CONTRASEÑA -->
            <div class="w-full sm:w-1/2 px-2 mb-2">
              <label for="password" class="block mb-1 font-bold text-xs">Contrase&ntilde;a:</label>
              <input type="text" id="txt_password" name="password" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese contraseña">
            </div>
          </div>

          <script>
            document.getElementById('txt_codigoUsuario').value = '<?php echo $UsuarioRegistrado ? $UsuarioRegistrado['USU_codigo'] : ''; ?>';
            document.getElementById('cbo_persona').value = '<?php echo $UsuarioRegistrado ? $UsuarioRegistrado['PER_codigo'] : ''; ?>';
            document.getElementById('cbo_area').value = '<?php echo $UsuarioRegistrado ? $UsuarioRegistrado['ARE_codigo'] : ''; ?>';
            document.getElementById('cbo_rol').value = '<?php echo $UsuarioRegistrado ? $UsuarioRegistrado['ROL_codigo'] : ''; ?>';
            document.getElementById('txt_nombreUsuario').value = '<?php echo $UsuarioRegistrado ? $UsuarioRegistrado['USU_nombre'] : ''; ?>';
            document.getElementById('txt_password').value = '<?php echo $UsuarioRegistrado ? $UsuarioRegistrado['USU_password'] : ''; ?>';
          </script>

          <!-- BOTONES -->
          <div class="flex justify-center space-x-4 mt-8 mb-2">
            <button type="submit" id="guardar-usuario" class="btn-primary text-white font-bold py-2 px-4 rounded-md"> Guardar </button>
            <button type="button" id="editar-usuario" class="bg-blue-500 text-white font-bold hover:bg-blue-600 py-2 px-4 rounded-md"> Editar </button>
            <button type="reset" id="nuevo-registro" class="bg-gray-500 text-white font-bold hover:bg-gray-600 py-2 px-4 rounded-md"> Nuevo </button>
          </div>
        </form>
      </div>
      <!-- TODO: TABLA DE LISTA DE USUARIOS REGISTRADOS -->

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
        <div class="relative max-h-[800px] mt-2 overflow-x-hidden shadow-md sm:rounded-lg">
          <table id="tablaListarUsuarios" class="w-full text-xs text-left rtl:text-right text-gray-500">
            <thead class="sticky top-0 text-xs text-gray-70 uppercase bg-lime-300">
              <tr>
                <th scope="col" class="px-6 py-1 hidden">N°</th>
                <th scope="col" class="px-6 py-1">Nombre completo</th>
                <th scope="col" class="px-6 py-3">&Aacute;rea</th>
                <th scope="col" class="px-6 py-3">Usuario</th>
                <th scope="col" class="px-6 py-3">Contrase&ntilde;a</th>
                <th scope="col" class="px-6 py-3">Rol</th>
                <th scope="col" class="px-6 py-3">Estado</th>
                <th scope="col" class="px-6 py-3">Opciones</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($usuarios)) : ?>
                <?php foreach ($usuarios as $usuario) : ?>
                  <?php
                  $estado = htmlspecialchars($usuario['EST_descripcion']);
                  $isActive = ($estado === 'Activo');
                  ?>
                  <tr class="bg-white hover:bg-green-100 hover:scale-[101%] transition-all hover:cursor-pointer border-b">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap hidden"><?= htmlspecialchars($usuario['USU_codigo']); ?></th>
                    <td class="px-6 py-4"><?= htmlspecialchars($usuario['persona']); ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($usuario['ARE_nombre']); ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($usuario['USU_nombre']); ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($usuario['USU_password']); ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($usuario['ROL_nombre']); ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($usuario['EST_descripcion']); ?></td>
                    <td class="px-6 py-4">
                      <div class="flex flex-col space-y-2">
                        <div class="custom-control custom-switch">
                          <input type="checkbox" class="custom-control-input cursor-pointer" id="customswitch1">
                          <label class="custom-control-label cursor-pointer" for="customswitch1">Habilitar</label>
                        </div>
                        <button class="flex items-center justify-center text-white font-bold py-2 px-4 rounded 
            <?= $isActive ? 'bg-gray-500 cursor-not-allowed' : 'bg-blue-500 hover:bg-blue-700' ?>" <?= $isActive ? 'disabled' : '' ?>>
                          <i class="bx bxs-bulb <?= $isActive ? 'text-white' : 'text-white' ?>"></i>
                        </button>
                        <button class="flex items-center justify-center text-white font-bold py-2 px-4 rounded 
            <?= !$isActive ? 'bg-gray-500 cursor-not-allowed' : 'bg-red-500 hover:bg-red-700' ?>" <?= !$isActive ? 'disabled' : '' ?>>
                          <i class="bx bx-bulb <?= !$isActive ? 'text-white' : 'text-white' ?>"></i>
                        </button>
                      </div>
                    </td>

                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
                <tr>
                  <td colspan="8" class="text-center py-4">No hay usuarios</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>