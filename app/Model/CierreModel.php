<?php
require_once 'config/conexion.php';

class CierreModel extends Conexion
{

  public function __construct()
  {
    parent::__construct();
  }

  //TODO: Metodo para obtener cierres por ID
  public function obtenerCierrePorID($CieNumero)
  {
    $conector = parent::getConexion();
    try {
      $sql = "SELECT * FROM CIERRE c 
      WHERE CIE_numero = ?";
      $stmt = $conector->prepare($sql);
      $stmt->execute(([$CieNumero]));
      $registros = $stmt->fetch(PDO::FETCH_ASSOC);
      return $registros;
    } catch (PDOException $e) {
      echo "Error al obtener los registros de los cierres: " . $e->getMessage();
      return null;
    }
  }

  //TODO: Metodo para insertar Cierre
  public function insertarCierre($fecha, $hora, $diagnostico, $documento, $asunto, $solucion, $recomendaciones, $operatividad, $recepcion, $usuario)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_InsertarCierreActualizarRecepcion @CIE_fecha = :fecha, @CIE_hora = :hora, @CIE_diagnostico = :diagnostico, @CIE_documento = :documento, @CIE_asunto = :asunto, @CIE_solucion = :solucion, @CIE_recomendaciones = :recomendaciones, @CON_codigo = :operatividad, @REC_numero = :recepcion, @USU_codigo = :usuario";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':diagnostico', $diagnostico);
        $stmt->bindParam(':documento', $documento);
        $stmt->bindParam(':asunto', $asunto);
        $stmt->bindParam(':solucion', $solucion);
        $stmt->bindParam(':recomendaciones', $recomendaciones);
        $stmt->bindParam(':operatividad', $operatividad);
        $stmt->bindParam(':recepcion', $recepcion);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        return $conector->lastInsertId();
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      echo "Error al insertar el cierre: " . $e->getMessage();
      return false;
    }
  }


  //TODO: Metodo para listar cierres Administrador - FORM CONSULTAR CIERRE
  public function listarCierresAdministrador()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT
          I.INC_numero,
          (CONVERT(VARCHAR(10),INC_fecha,103) + ' - '+   STUFF(RIGHT('0' + CONVERT(VarChar(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada,
          A.ARE_nombre,
          CAT.CAT_nombre,
          I.INC_asunto,
          I.INC_documento,
          I.INC_codigoPatrimonial,
	        (CONVERT(VARCHAR(10),CIE_fecha,103) + ' - '+   STUFF(RIGHT('0' + CONVERT(VarChar(7), CIE_hora, 0), 7), 6, 0, ' ')) AS fechaCierreFormateada,
	        O.CON_descripcion,
	        u.USU_nombre
        FROM RECEPCION R
        RIGHT JOIN INCIDENCIA I ON R.INC_numero = I.INC_numero
        INNER JOIN  AREA A ON I.ARE_codigo = A.ARE_codigo
        INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
        INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
        LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
        LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
        INNER JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
        INNER JOIN USUARIO U ON U.USU_codigo = C.USU_codigo
        WHERE  I.EST_codigo = 5 OR C.EST_codigo = 5
        ORDER BY C.CIE_numero DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      echo "Error al listar cierres registrados para el administrador: " . $e->getMessage();
      return false;
    }
  }

  // TODO: Metodo para contar incidencias cerradas para la tabla listar cierres
  public function contarIncidenciasCerradas()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) AS total
        FROM RECEPCION R
        RIGHT JOIN INCIDENCIA I ON R.INC_numero = I.INC_numero
        INNER JOIN  AREA A ON I.ARE_codigo = A.ARE_codigo
        INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
        INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
        LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
        LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
        INNER JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
        INNER JOIN USUARIO U ON U.USU_codigo = C.USU_codigo
        WHERE  I.EST_codigo = 5 OR C.EST_codigo = 5";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      echo "Error contar incidencias cerradas: " . $e->getMessage();
      return null;
    }
  }

  // TODO: Metodo para obtener la lista de incidencias cerradas para la tabla listar cierres
  public function obtenerIncidenciasCerradas($start, $limit)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT
          I.INC_numero,
          (CONVERT(VARCHAR(10),INC_fecha,103) + ' - '+   STUFF(RIGHT('0' + CONVERT(VarChar(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada,
          A.ARE_nombre,
          i.INC_asunto,
          I.INC_documento,
          I.INC_codigoPatrimonial,
          (CONVERT(VARCHAR(10),CIE_fecha,103) + ' - '+   STUFF(RIGHT('0' + CONVERT(VarChar(7), CIE_hora, 0), 7), 6, 0, ' ')) AS fechaCierreFormateada,
          CIE_asunto,
          C.CIE_documento,
	        O.CON_descripcion,
	        u.USU_nombre,
          PER_nombres + ' ' + PER_apellidoPaterno AS Usuario
        FROM RECEPCION R
        RIGHT JOIN INCIDENCIA I ON R.INC_numero = I.INC_numero
        INNER JOIN  AREA A ON I.ARE_codigo = A.ARE_codigo
        INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
        INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
        LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
        LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
        INNER JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
        INNER JOIN USUARIO U ON U.USU_codigo = C.USU_codigo
        INNER JOIN PERSONA p ON p.PER_codigo = u.PER_codigo
        WHERE  I.EST_codigo = 5 OR C.EST_codigo = 5
        ORDER BY C.CIE_numero DESC
        OFFSET :start ROWS
        FETCH NEXT :limit ROWS ONLY";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      echo "Error obtener lista de incidencias cerradas: " . $e->getMessage();
      return null;
    }
  }

  // TODO: Contar incidencias del ultimo mes para el administrador
  public function contarCierresUltimoMesAdministrador()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as cierres_mes_actual FROM CIERRE 
              WHERE CIE_FECHA >= DATEADD(MONTH, -1, GETDATE())";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['cierres_mes_actual'];
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar cierres del ultimo mes para el administrador: " . $e->getMessage();
      return null;
    }
  }

  // TODO: Contar incidencias del ultimo mes para el usuario
  public function contarCierresUltimoMesUsuario($area)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as cierres_mes_actual FROM CIERRE c
				INNER JOIN RECEPCION r ON r.REC_numero = c.REC_numero
				LEFT JOIN INCIDENCIA i ON i.INC_numero = r.INC_numero
				INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
        WHERE CIE_FECHA >= DATEADD(MONTH, -1, GETDATE()) AND
				a.ARE_codigo = :are_codigo";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':are_codigo', $area, PDO::PARAM_INT); // Vinculamos el parámetro
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['cierres_mes_actual'];
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar cierres del ultimo mes para el administrador: " . $e->getMessage();
      return null;
    }
  }

  public function buscarCierres($area, $codigoPatrimonial, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();

    try {
      if ($conector != null) {
        $sql = "EXEC sp_ConsultarCierres :area, :codigoPatrimonial, :fechaInicio, :fechaFin";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->bindParam(':codigoPatrimonial', $estado, PDO::PARAM_STR_CHAR);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        // Ejecutar el procedimiento almacenado
        $stmt->execute();

        // Obtener los resultados
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener los cierres XD: " . $e->getMessage());
    }
  }
}
