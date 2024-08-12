<?php

require_once 'app/Model/UsuarioModel.php';

class UsuarioController
{
  private $usuarioModel;
  public function __construct()
  {
    $this->usuarioModel = new UsuarioModel();
  }

  // TODO: Metodo de controlador para registrar usuarios
  public function registrarUsuario()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener los datos del formulario
      $username = $_POST['username'] ?? null;
      $password = $_POST['password'] ?? null;
      $per_codigo = $_POST['persona'] ?? null;
      $rol_codigo = $_POST['rol'] ?? null;
      $are_codigo = $_POST['area'] ?? null;

      // Validar campos
      if (empty($username) || empty($password) || empty($per_codigo) || empty($rol_codigo) || empty($are_codigo)) {
        echo json_encode([
          'success' => true,
          'message' => 'Todos los campos son obligatorios.'
        ]);
        return;
      } else if (empty($per_codigo)) {
        echo json_encode([
          'success' => true,
          'message' => 'Debe seleccionar una persona.'
        ]);
        return;
      } else if (empty($rol_codigo)) {
        echo json_encode([
          'success' => true,
          'message' => 'Debe seleccionar un rol.'
        ]);
        return;
      } else if (empty($are_codigo)) {
        echo json_encode([
          'success' => true,
          'message' => 'Debe seleccionar un area.'
        ]);
        return;
      } else if (empty($username)) {
        echo json_encode([
          'success' => true,
          'message' => 'Debe ingresar un nombre de usuario.'
        ]);
        return;
      } else if (empty($password)) {
        echo json_encode([
          'success' => true,
          'message' => 'Debe ingresar una contraseña.'
        ]);
        return;
      }

      // Llamar al método del modelo para registrar el usuario en la base de datos
      try {
        $insertSuccess = $this->usuarioModel->guardarUsuario(
          $username,
          $password,
          $per_codigo,
          $rol_codigo,
          $are_codigo
        );

        if ($insertSuccess) {
          // header('Location: modulo-usuario.php?USU_codigo=' . $insertSuccess);
          echo json_encode([
            'success' => true,
            'message' => 'Usuario registrado.'
          ]);
        } else {
          // echo "Error al registrar el usuario eje.";
          echo json_encode([
            'success' => false,
            'message' => 'Error al registrar usuario.'
          ]);
        }
      } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        echo json_encode([
          'success' => false,
          'message' => 'Error de controlador: ' . $e->getMessage()
        ]);
      }
    } else {
      echo "Error: Método no permitido.";
    }
  }

  // Metodo para editar usuarios
  public function editarUsuario()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      header('Content-Type: application/json');

      try {
        // Obtener los datos de formulario
        $codigoUsuario = $_POST['CodUsuario'] ?? null;
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;
        $persona = $_POST['persona'] ?? null;
        $rol = $_POST['rol'] ?? null;
        $area = $_POST['area'] ?? null;
        // Actualizar usuario
        $this->usuarioModel->actualizarUsuario($username, $password, $persona, $rol, $area, $codigoUsuario);
        header("Location: modulo-usuario.php");
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
    }
  }

  public function editarPerfil()
  {
    // header('Content-Type: application/json');

    try {
      // Obtener los datos del formulario
      $usu_nombre = $_POST['username'] ?? null;
      $usu_password = $_POST['password'] ?? null;
      $per_dni = $_POST['dni'] ?? null;
      $per_nombres = $_POST['nombres'] ?? null;
      $per_apellidoPaterno = $_POST['apellidoPaterno'] ?? null;
      $per_apellidoMaterno = $_POST['apellidoMaterno'] ?? null;
      $per_celular = $_POST['celular'] ?? null;
      $per_email = $_POST['email'] ?? null;
      $usu_codigo = $_POST['codigoUsuario'] ?? null;


      // Actualizar usuario
      $this->usuarioModel->editarPerfilUsuario($usu_codigo, $usu_nombre, $usu_password, $per_dni, $per_nombres, $per_apellidoPaterno, $per_apellidoMaterno, $per_celular, $per_email);

      // echo json_encode([
      //   'success' => true,
      //   'message' => 'Perfil actualizado correctamente'
      // ]);
    } catch (Exception $e) {
      // echo json_encode([
      //   'success' => false,
      //   'message' => 'Error: ' . $e->getMessage()
      // ]);
    }
  }


  public function habilitarUsuario()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $USU_codigo = isset($_POST['USU_codigo']) ? intval($_POST['USU_codigo']) : 0;

      try {
        $this->usuarioModel->habilitarUsuario($USU_codigo);
        echo json_encode(['success' => true]);
      } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
      }
    } else {
      echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    }
  }

  public function deshabilitarUsuario()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $USU_codigo = isset($_POST['USU_codigo']) ? intval($_POST['USU_codigo']) : 0;

      try {
        $this->usuarioModel->deshabilitarUsuario($USU_codigo);
        echo json_encode(['success' => true]);
      } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
      }
    } else {
      echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    }
  }


  //   public function habilitarUsuario($USU_codigo)
  //   {
  //     try {
  //       $this->usuarioModel->habilitarUsuario($USU_codigo);
  //       echo json_encode(['status' => 'success', 'message' => 'Usuario habilitado exitosamente']);
  //     } catch (Exception $e) {
  //       echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
  //     }
  //   }

  //   public function deshabilitarUsuario($USU_codigo)
  //   {
  //     try {
  //       $this->usuarioModel->deshabilitarUsuario($USU_codigo);
  //       echo json_encode(['status' => 'success', 'message' => 'Usuario deshabilitado exitosamente']);
  //     } catch (Exception $e) {
  //       echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
  //     }
  //   }
  // }

  // // Manejar solicitudes AJAX
  // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  //   $controller = new UsuarioController();
  //   $action = $_POST['action'];
  //   $USU_codigo = $_POST['USU_codigo'];

  //   if ($action === 'habilitar') {
  //     $controller->habilitarUsuario($USU_codigo);
  //   } elseif ($action === 'deshabilitar') {
  //     $controller->deshabilitarUsuario($USU_codigo);
  //   }
}
