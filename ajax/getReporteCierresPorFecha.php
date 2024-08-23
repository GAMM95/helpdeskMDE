<?php
require_once '../config/conexion.php';

class ReporteCierresPorFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteCierresPorFecha($fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    $sql = "";
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':fechaInicio', $fechaInicio);
    $stmt->bindParam(':fechaFin', $fechaFin);

    try {
      $stmt->execute();
      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo json_encode(['error' => $e->getMessage()]);
      exit;
    }
    return $resultado;
  }
}

// Obtención del parámetro 'area' desde la solicitud
$fechaInicio = isset($_GET['fechaInicio']) ? intval($_GET['fechaInicio']) : 0;
$fechaFin = isset($_GET['fechaFin']) ? intval($_GET['fechaFin']) : 0;

$reporteCierresPorFecha = new ReporteCierresPorFecha();
$reporte = $reporteCierresPorFecha->getReporteCierresPorFecha($fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
