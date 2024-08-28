<?php
require 'app/Model/RecepcionModel.php';

class RecepcionController
{
  private $recepcionModel;

  public function __construct()
  {
    $this->recepcionModel = new RecepcionModel();
  }

  //TODO: Metodo para registrar la recepcion
  public function registrarRecepcion()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener los datos del formulario
      $fecha = $_POST['fecha_recepcion'] ?? null;
      $hora = $_POST['hora'] ?? null;
      $incidencia = $_POST['incidencia'] ?? null;
      $prioridad = $_POST['prioridad'] ?? null;
      $impacto  = $_POST['impacto'] ?? null;
      $usuario = $_POST['usuario'] ?? null;

      try {
        // Llamar al método del modelo para insertar la incidencia en la base de datos
        $insertSuccessId = $this->recepcionModel->insertarRecepcion($fecha, $hora, $incidencia, $prioridad, $impacto, $usuario);

        if ($insertSuccessId) {
          echo json_encode([
            'success' => true,
            'message' => 'Recepcion registrada.',
            'INC_numero' => $insertSuccessId
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'Error al registrar la recepcion.'
          ]);
        }
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
      exit();
    }
  }

  // TODO: Metodo para editar la recepcion 
  public function actualizarRecepcion()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener y validar los parámetros
      $numeroRecepcion = $_POST['numero_incidencia'] ?? null;
      $prioridad = $_POST['prioridad'] ?? null;
      $impacto = $_POST['impacto'] ?? null;

      try {
        // Verificar el estado de la incidencia
        $estado = $this->recepcionModel->obtenerEstadoRecepcion($numeroRecepcion);
        if ($estado === 4) {
          // Estado permitido para actualización
          echo json_encode([
            'success' => false,
            'message' => 'La recepcion no está Recepcionada y no puede ser actualizada.'
          ]);
          exit();
        }

        // Llamar al modelo para actualizar la incidencia
        $updateSuccess = $this->recepcionModel->editarRecepcion($numeroRecepcion, $prioridad, $impacto);

        if ($updateSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Recepcion actualizada.'
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
    }
  }
}
