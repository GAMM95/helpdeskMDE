<?php
require 'app/Model/RecepcionModel.php';

class RecepcionController
{
  private $recepcionModel;

  public function __construct()
  {
    $this->recepcionModel = new RecepcionModel();
  }

  // Metodo para registrar recepcion
  public function registrarRecepcion()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener los datos del formulario
      $fecha = $_POST['fecha_recepcion'] ?? null;
      $hora = $_POST['hora'] ?? null;
      $incidencia = $_POST['incidencia'] ?? null;
      $prioridad = $_POST['prioridad'] ?? null;
      $impacto = $_POST['impacto'] ?? null;
      $usuario = $_POST['usuario'] ?? null;

      // Validar que todos los campos requeridos estén completos
      if (empty($fecha) || empty($hora) || empty($incidencia) || empty($prioridad) || empty($impacto) || empty($usuario)) {
        echo json_encode([
          'success' => false,
          'message' => 'Todos los campos son obligatorios.'
        ]);
        exit();
      }

      try {
        // Llamar al método del modelo para insertar la recepción en la base de datos
        $insertSuccessId = $this->recepcionModel->insertarRecepcion($fecha, $hora, $incidencia, $prioridad, $impacto, $usuario);

        if ($insertSuccessId) {
          echo json_encode([
            'success' => false,
            'message' => 'Error al registrar la recepcion.',
            'REC_numero' => $insertSuccessId
          ]);
        } else {
          echo json_encode([
            'success' => true,
            'message' => 'Recepción registrada.',
          ]);
        }
      } catch (Exception $e) {
        // Capturar y mostrar detalles del error
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
      exit();
    } else {
      echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
      ]);
    }
  }


  // Metodo para editar la recepcion 
  public function actualizarRecepcion()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener y validar los parámetros
      $numeroRecepcion = $_POST['num_recepcion'] ?? null;
      $prioridad = $_POST['prioridad'] ?? null;
      $impacto = $_POST['impacto'] ?? null;

      if (empty($numeroRecepcion) || empty($prioridad) || empty($impacto)) {
        echo json_encode([
          'success' => false,
          'message' => 'Todos los campos son obligatorios.'
        ]);
        exit();
      }

      try {
        // Verificar el estado de la incidencia
        $estado = $this->recepcionModel->obtenerEstadoRecepcion($numeroRecepcion);

        // Suponiendo que el estado "4" permite la actualización
        if ($estado === 4) {
          // Estado no permitido para actualización
          echo json_encode([
            'success' => false,
            'message' => 'La recepción no está en un estado que permita actualización.'
          ]);
          exit();
        }

        // Llamar al modelo para actualizar la incidencia
        $updateSuccess = $this->recepcionModel->editarRecepcion($prioridad, $impacto, $numeroRecepcion);

        if ($updateSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Recepción actualizada.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se realizó ninguna actualización.'
          ]);
        }
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
      exit();
    } else {
      echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
      ]);
    }
  }

  // TODO: Metodo para eliminar recepcion
  // Metodo para editar la recepcion 
  public function eliminarRecepcion()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener y validar los parámetros
      $numeroRecepcion = $_POST['num_recepcion'] ?? null;

      if (empty($numeroRecepcion)) {
        echo json_encode([
          'success' => false,
          'message' => 'Todos los campos son obligatorios.'
        ]);
        exit();
      }

      try {
        // Verificar el estado de la incidencia
        $estado = $this->recepcionModel->obtenerEstadoRecepcion($numeroRecepcion);

        // Suponiendo que el estado "4" permite la actualización
        if ($estado === 4) {
          // Estado no permitido para actualización
          echo json_encode([
            'success' => false,
            'message' => 'La recepción no está en un estado que permita actualización.'
          ]);
          exit();
        }

        // Llamar al modelo para actualizar la incidencia
        $updateSuccess = $this->recepcionModel->eliminarRecepcion($numeroRecepcion);

        if ($updateSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Recepción eliminada.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se realizó ninguna eliminación.'
          ]);
        }
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
      exit();
    } else {
      echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
      ]);
    }
  }
}
