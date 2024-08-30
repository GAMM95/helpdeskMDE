<?php
require 'app/Model/CierreModel.php';
class cierreController
{
  private $cierreModel;

  public function __construct()
  {
    $this->cierreModel = new CierreModel();
  }

  //TODO: Metodo para registrar cierre
  public function registrarCierre()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $fecha = $_POST['fecha_cierre'] ?? null;
      $hora = $_POST['hora'] ?? null;
      $diagnostico = $_POST['diagnostico'] ?? null;
      $documento = $_POST['documento'] ?? null;
      $asunto = $_POST['asunto'] ?? null;
      $recomendaciones = $_POST['recomendaciones'] ?? null;
      $operatividad = $_POST['operatividad'] ?? null;
      $recepcion = $_POST['recepcion'] ?? null;
      $usuario = $_POST['usuario'] ?? null;

      try {
        // Llamar al método del modelo para insertar el cierre en la base de datos
        $insertSuccess = $this->cierreModel->insertarCierre($fecha, $hora, $diagnostico, $documento, $asunto, $recomendaciones, $operatividad, $recepcion, $usuario);

        if ($insertSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Cierre de incidencia registrado.',
            'CIE_numero' => $insertSuccess
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

  // TODO: Metodo para editar el cierre
  public function actualizarCierre()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener y validar los parámetros
      $numeroCierre = $_POST['num_cierre'] ?? null;
      $asunto = $_POST['asunto'] ?? null;
      $documento = $_POST['documento'] ?? null;
      $condicion = $_POST['operatividad'] ?? null;
      $diagnostico = $_POST['diagnostico'] ?? null;
      $recomendaciones = $_POST['recomendaciones'] ?? null;

      // if (empty($asunto) || empty($documento) || empty($condicion)) {
      //   echo json_encode([
      //     'success' => false,
      //     'message' => 'Campo obligatorio.'
      //   ]);
      //   exit();
      // }

      try {

        // Llamar al modelo para editar el cierre de la incidencia
        $updateSuccess = $this->cierreModel->editarCierre($asunto, $documento, $condicion, $diagnostico, $recomendaciones, $numeroCierre);

        if ($updateSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Cierre de incidencia actualizado.'
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

  // TODO: Metodo para consultar cierres - Administrador
  public function consultarCierres($area = NULL, $codigoPatrimonial = null, $fechaInicio = null, $fechaFin = null)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Obtener los valores de los parámetros GET o asignar null si no existen
      $area = isset($_GET['area']) ? (int) $_GET['area'] : null;
      $codigoPatrimonial = isset($_GET['codigoPatrimonial']) ? $_GET['codigoPatrimonial'] : null;
      $fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : null;
      $fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : null;
      error_log("Área: $area, CodigoPatrimonial: $codigoPatrimonial, Fecha Inicio: $fechaInicio, Fecha Fin: $fechaFin");

      // Llamar al método para consultar cierres 
      $consultaIncidencia = $this->cierreModel->buscarCierres($area, $codigoPatrimonial, $fechaInicio, $fechaFin);
      // Retornar el resultado de la consulta
      return $consultaIncidencia;
    }
  }
}
