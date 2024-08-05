<?php

require_once 'app/Model/UsuarioModel.php';

class UsuarioController
{
  private $usuarioModel;

  public function __construct()
  {
    $this->usuarioModel = new UsuarioModel();
  }

  public function registrarUsuario()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener los datos del formulario
      $username = $_POST['username'];
      $password = $_POST['password'];
      $per_codigo = $_POST['persona'];
      $rol_codigo = $_POST['rol'];
      $are_codigo = $_POST['area'];

      // Crear una instancia del modelo de usuario con los datos del usuario
      $usuarioModel = new UsuarioModel($username, $password);

      // Llamar al método del modelo para registrar el usuario en la base de datos
      try {
        $insertSuccess = $usuarioModel->registrarUsuario($username, $password, $per_codigo, $rol_codigo, $are_codigo);

        if ($insertSuccess) {
          header('Location: modulo-usuario.php?CodUsuario=' . $insertSuccess);
        } else {
          echo "Error al registrar el usuario.";
        }
      } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
      }
    } else {
      echo "Error: Método no permitido.";
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
}
