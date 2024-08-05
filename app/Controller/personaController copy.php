<?php
require_once 'app/Model/PersonaModel.php';

class PersonaController
{
  private $personaModel;

  public function __construct()
  {
    $this->personaModel = new PersonaModel();
  }

  // Método para registrar personas
  public function registrarPersona()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener los datos del formulario
      $dni = $_POST['dni'] ?? null;
      $nombres = $_POST['nombres'] ?? null;
      $apellidoPaterno = $_POST['apellidoPaterno'] ?? null;
      $apellidoMaterno = $_POST['apellidoMaterno'] ?? null;
      $celular = $_POST['celular'] ?? null;
      $email = $_POST['email'] ?? null;

      // Registrar la persona
      $insertSuccessId = $this->personaModel->registrarPersona(
        $dni,
        $nombres,
        $apellidoPaterno,
        $apellidoMaterno,
        $celular,
        $email
      );

      if ($insertSuccessId) {
        header('Location: modulo-persona.php?PER_codigo=' . $insertSuccessId);
        exit();
      } else {
        echo "Error al registrar persona";
      }
    }
  }

  // Método para editar personas
  public function editarPersona()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      try {
        // Obtener los datos del formulario
        $codigoPersona = $_POST['CodPersona'] ?? null;
        $dni = $_POST['dni'] ?? null;
        $nombres = $_POST['nombres'] ?? null;
        $apellidoPaterno = $_POST['apellidoPaterno'] ?? null;
        $apellidoMaterno = $_POST['apellidoMaterno'] ?? null;
        $email = $_POST['email'] ?? null;
        $celular = $_POST['celular'] ?? null;

        // Verificar que todos los campos necesarios estén presentes
        if ($codigoPersona && $dni && $nombres && $apellidoPaterno && $apellidoMaterno && $email && $celular) {
          // Actualizar la persona
          $rowsAffected = $this->personaModel->actualizarPersona(
            $codigoPersona,
            $dni,
            $nombres,
            $apellidoPaterno,
            $apellidoMaterno,
            $email,
            $celular
          );

          if ($rowsAffected > 0) {
            echo "Datos actualizados correctamente";
          } else {
            echo "No se realizaron cambios";
          }
        } else {
          echo "Todos los campos son obligatorios";
        }
      } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
      }
    } else {
      echo "Error: Método no permitido";
    }
  }

  // Método para verificar la existencia de un DNI
  public function verificarDni()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $dni = $_POST['dni'] ?? null;

      if ($dni) {
        $existe = $this->personaModel->validarDniExistente($dni);
        echo json_encode(['existe' => $existe]);
      } else {
        echo json_encode(['existe' => false]);
      }
    }
  }
}
