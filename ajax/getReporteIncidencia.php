<?php
require_once '../config/conexion.php';

class ReporteIncidencia extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }
  public function getReporteIncidencia()
  {
    $conector = parent::getConexion();
    $sql = "SELECT 
        I.INC_numero,
        (CONVERT(VARCHAR(10), INC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada,
        I.INC_codigoPatrimonial,
        I.INC_asunto,
        I.INC_documento,
        I.INC_descripcion,
        CAT.CAT_nombre,
        A.ARE_nombre,
        CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
        END AS ESTADO,
        p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario
        FROM INCIDENCIA I
        INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
        INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
        INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
        LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
        LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
        LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
        LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
        LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
        LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
        LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
        INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
        WHERE (I.EST_codigo IN (3, 4, 5) OR C.EST_codigo IN (3, 4, 5))
        ORDER BY i.INC_numero DESC";
    $stmt = $conector->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
  }
}

$reporteIncidencia = new ReporteIncidencia();
$reporte = $reporteIncidencia->getReporteIncidencia();

header('Content-Type: application/json');
echo json_encode($reporte);