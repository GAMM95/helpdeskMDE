<?php
require_once 'app/Model/PersonaModel.php';

class PersonaController
{
  private $personaModel;

  public function __construct()
  {
    $this->personaModel = new PersonaModel();
  }

  // TODO: Método para registrar personas
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
            'message' => 'El DNI ya esta registrado.'
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

        if ($insertSuccessId) {
          echo json_encode([
            'success' => true,
            'message' => 'Persona registrada.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'Error al registrar persona.'
          ]);
        }
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
    }
  }

  // TODO: Método para editar personas
  public function editarPersona()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      header('Content-Type: application/json');

      try {
        $codigoPersona = $_POST['CodPersona'] ?? null;
        $dni = $_POST['dni'] ?? null;
        $nombres = $_POST['nombres'] ?? null;
        $apellidoPaterno = $_POST['apellidoPaterno'] ?? null;
        $apellidoMaterno = $_POST['apellidoMaterno'] ?? null;
        $email = $_POST['email'] ?? null;
        $celular = $_POST['celular'] ?? null;

        // Normaliza los valores de campos opcionales
        $email = !empty($email) ? $email : null;
        $celular = !empty($celular) ? $celular : null;

        if ($codigoPersona && $dni && $nombres && $apellidoPaterno && $apellidoMaterno) {
          $personaModel = $this->personaModel->actualizarPersona(
            $dni,
            $nombres,
            $apellidoPaterno,
            $apellidoMaterno,
            $celular,
            $email,
            $codigoPersona
          );

          if ($personaModel > 0) {
            echo json_encode(['success' => true, 'message' => 'Datos actualizados.']);
          } else {
            echo json_encode(['success' => false, 'message' => 'No se realizaron cambios.']);
          }
        } else {
          echo json_encode(['success' => false, 'message' => 'Todos los campos obligatorios deben completarse.']);
        }
      } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
      }
    } else {
      echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    }
  }


  public function filtrarPersonas()
  {
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
      $terminoBusqueda = $_GET['termino'] ?? '';

      header('Content-Type: application/json'); // Establecer el tipo de contenido como JSON

      try {
        $resultados = $this->personaModel->filtrarPersonas($terminoBusqueda);
        echo json_encode($resultados);
      } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
      }
    } else {
      echo json_encode(['error' => 'Método no permitido.']);
    }
  }
}
