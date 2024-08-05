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

      header('Content-Type: application/json'); // Establecer el tipo de contenido como JSON

      try {
        // Validar si el DNI ya está registrado
        if ($this->personaModel->validarDniExistente($dni)) {
          echo json_encode([
            'success' => false,
            'message' => 'El DNI ya está registrado.'
          ]);
          exit();
        }

        // Registrar la persona
        $insertSuccessId = $this->personaModel->registrarPersona(
          $dni,
          $nombres,
          $apellidoPaterno,
          $apellidoMaterno,
          $celular,
          $email
        );

        // if ($insertSuccessId) {
        //   echo json_encode([
        //     'success' => true,
        //     'message' => 'Persona registrada con éxito.'
        //   ]);
        // } else {
        //   echo json_encode([
        //     'success' => false,
        //     'message' => 'Error al registrar persona.'
        //   ]);
        // }
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
    }
  }

  // Método para editar personas
  public function editarPersona()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      header('Content-Type: application/json'); // Establecer el tipo de contenido como JSON

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
          // Crear una instancia de PersonaModel con los datos
          $this->personaModel = new PersonaModel(
            $codigoPersona,
            $dni,
            $nombres,
            $apellidoPaterno,
            $apellidoMaterno,
            $email,
            $celular
          );

          // Actualizar la persona
          $rowsAffected = $this->personaModel->actualizarPersona();

          if ($rowsAffected > 0) {
            echo json_encode([
              'success' => true,
              'message' => 'Datos actualizados correctamente.'
            ]);
          } else {
            echo json_encode([
              'success' => false,
              'message' => 'No se realizaron cambios.'
            ]);
          }
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'Todos los campos son obligatorios.'
          ]);
        }
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
    } else {
      echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
      ]);
    }
  }
}
