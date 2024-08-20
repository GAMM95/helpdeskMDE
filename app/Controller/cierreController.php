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

      // Verificar que la fecha no es nula
      if ($fecha === null || $fecha === '') {
        echo "Error: La fecha es un campo obligatorio.";
        return;
      }

      // Llamar al método del modelo para insertar el cierre en la base de datos
      $insertSuccess = $this->cierreModel->insertarCierre(
        $fecha,
        $hora,
        $diagnostico,
        $documento,
        $asunto,
        $recomendaciones,
        $operatividad,
        $recepcion,
        $usuario
      );
      if ($insertSuccess) {
        header('Location: registro-cierre-admin.php?CIE_numero=' . $insertSuccess);
      } else {
        echo "Error al registrar cierre.";
      }
    } else {
      echo "Error: Método no permitido.";
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

      // Llamar al método para consultar incidencias por área, estado y fecha
      $consultaIncidencia = $this->cierreModel->buscarCierres($area, $codigoPatrimonial, $fechaInicio, $fechaFin);

      // Retornar el resultado de la consulta
      return $consultaIncidencia;
    }
  }
}
