<?php
require_once '../config/conexion.php';

class ReporteEquipoMasIncidencia extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteEquipoMasIncidencia($fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    $sql = "SELECT TOP 10 
        i.INC_codigoPatrimonial AS codigoPatrimonial,
        -- Subconsulta para obtener el nombre del bien utilizando los primeros 8 dígitos
        (SELECT BIE_nombre 
        FROM BIEN 
        WHERE LEFT(i.INC_codigoPatrimonial, 8) = LEFT(BIE_codigoIdentificador, 8)) AS nombreBien,
        COUNT(*) AS cantidadIncidencias
    FROM INCIDENCIA i 
    WHERE 
        i.INC_codigoPatrimonial IS NOT NULL AND
        i.INC_fecha >= :fechaInicio
        AND i.INC_fecha <= :fechaFin
    GROUP BY i.INC_codigoPatrimonial
    ORDER BY cantidadIncidencias DESC";

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

// Obtención del parámetro 'fechaInicio' y 'fechaFin' desde la solicitud
$fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : '';
$fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : '';

$reporteEquipoMasIncidencia = new ReporteEquipoMasIncidencia();
$reporte = $reporteEquipoMasIncidencia->getReporteEquipoMasIncidencia($fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
