<?php
require_once 'app/Model/PersonaModel.php';

class PersonaController
{
  private $personaModel;

  public function __construct()
  {
    $this->personaModel = new PersonaModel();
  }

  public function registrarPersona()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $datosPersona = $this->obtenerDatosFormulario();

      try {
        $this->validarDatos($datosPersona);

        // Verificar si el DNI ya existe
        if ($this->personaModel->existeDni($datosPersona['dni'])) {
          throw new Exception("El DNI ya está registrado");
        }

        $personaModel = new PersonaModel(
          null,
          $datosPersona['dni'],
          $datosPersona['nombre'],
          $datosPersona['apellidoPaterno'],
          $datosPersona['apellidoMaterno'],
          $datosPersona['email'],
          $datosPersona['celular']
        );
        $insertSuccessId = $personaModel->registrarPersona();
        if ($insertSuccessId) {
          header('Location: modulo-persona.php?CodPersona=' . $insertSuccessId);
          exit();
        } else {
          echo "Error al registrar persona";
        }
      } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
      }
    } else {
      echo "Error: Método no permitido";
    }
  }

  public function validarDniExistente()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $dni = $_POST['dni'] ?? null;
      if ($dni) {
        $exists = $this->personaModel->existeDni($dni);
        echo json_encode(['exists' => $exists]);
      } else {
        echo json_encode(['error' => 'DNI no proporcionado']);
      }
    } else {
      echo json_encode(['error' => 'Método no permitido']);
    }
  }

  // Método para obtener los datos ingresados en el formulario
  private function obtenerDatosFormulario()
  {
    return [
      'dni' => $_POST['dni'] ?? null,
      'nombre' => $_POST['nombre'] ?? null,
      'apellidoPaterno' => $_POST['apellidoPaterno'] ?? null,
      'apellidoMaterno' => $_POST['apellidoMaterno'] ?? null,
      'email' => $_POST['email'] ?? null,
      'celular' => $_POST['celular'] ?? null,
    ];
  }

  // Método para validar los campos de formulario
  private function validarDatos($datos, $esActualizacion = false)
  {
    if ($esActualizacion && ($datos['codPersona'] === null || trim($datos['codPersona']) === '')) {
      throw new Exception("El código de la persona no puede estar vacío");
    }

    $campos = [
      'DNI' => $datos['dni'],
      'Nombre' => $datos['nombre'],
      'Apellido Paterno' => $datos['apellidoPaterno'],
      'Apellido Materno' => $datos['apellidoMaterno'],
      'Email' => $datos['email'],
      'Celular' => $datos['celular'],
    ];

    foreach ($campos as $campo => $valor) {
      if ($valor === null || trim($valor) === '') {
        throw new Exception("El campo $campo no puede estar vacío");
      }
    }
  }
}
