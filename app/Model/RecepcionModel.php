<?php
// Importamos las credenciales y la clase de conexión
require_once 'config/conexion.php';

class RecepcionModel extends Conexion
{
  private $conexion;

  public function __construct()
  {
    parent::__construct();
  }

  //TODO: Metodo para obtener recepcion por ID
  public function obtenerRecepcionPorId($RecNumero)
  {
    $conector = parent::getConexion();
    try {
      $sql = "SELECT * FROM  RECEPCION r
      WHERE REC_numero = ?";
      $stmt = $conector->prepare($sql);
      $stmt->execute([$RecNumero]);
      $registros = $stmt->fetch(PDO::FETCH_ASSOC);
      return $registros;
    } catch (PDOException $e) {
      echo "Error al obtener los registros de incidencias: " . $e->getMessage();
      return null;
    }
  }

  //TODO: Metodo para insertar Recepcion
  public function insertarRecepcion($fecha, $hora, $incidencia, $prioridad, $impacto, $usuario)
  {
    $conector = parent::getConexion();
    try {
      $sql = "EXEC sp_InsertarRecepcionActualizarIncidencia @REC_fecha = :fecha, @REC_hora = :hora, @INC_numero = :incidencia, @PRI_codigo = :prioridad, @IMP_codigo = :impacto, @USU_codigo = :usuario";
      $stmt = $conector->prepare($sql);
      $stmt->bindParam(':fecha', $fecha);
      $stmt->bindParam(':hora', $hora);
      $stmt->bindParam(':incidencia', $incidencia);
      $stmt->bindParam(':prioridad', $prioridad);
      $stmt->bindParam(':impacto', $impacto);
      $stmt->bindParam(':usuario', $usuario);
      $stmt->execute();
      return $conector->lastInsertId();
    } catch (PDOException $e) {
      echo "Error al insertar recepcionn: " . $e->getMessage();
      return false;
    }
  }

  // TODO: Metodo listar recepciones Administrador - FORM CONSULTAR RECEPCION
  public function listarRecepcionesAdministrador()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT REC_numero, (CONVERT(VARCHAR(10),REC_fecha,103) + ' - '+   STUFF(RIGHT('0' + CONVERT(VarChar(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada, a.ARE_nombre, i.INC_codigoPatrimonial, c.CAT_nombre, INC_asunto,P.PRI_nombre, Imp.IMP_descripcion, u.USU_nombre
            FROM RECEPCION r
            INNER JOIN INCIDENCIA i ON r.INC_numero = i.INC_numero
            INNER JOIN CATEGORIA c ON c.CAT_codigo = i.CAT_codigo
            INNER JOIN PRIORIDAD p ON r.PRI_codigo = P.PRI_codigo
            INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
            INNER JOIN USUARIO u ON u.USU_codigo = r.USU_codigo
            INNER JOIN IMPACTO Imp ON r.IMP_codigo = Imp.IMP_codigo 
            WHERE r.EST_codigo = 4
            ORDER BY R.REC_numero";
        $stmt = $conector->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar las recepciones para el admministrador: " . $e->getMessage());
    }
  }

  //TODO: Metodo para obtener recepciones sin cerrar
  public function obtenerRecepcionesSinCerrar($start, $limit)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {

        $sql = "SELECT i.INC_numero, REC_numero, 
        (CONVERT(VARCHAR(10),REC_fecha,103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VarChar(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
        a.ARE_nombre, i.INC_codigoPatrimonial, INC_asunto, INC_documento, p.PRI_nombre, imp.IMP_descripcion, u.USU_nombre,
        PER_nombres + ' ' + PER_apellidoPaterno AS Usuario
        FROM RECEPCION r 
        INNER JOIN INCIDENCIA i ON i.INC_numero = r.INC_numero
        INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
        INNER JOIN PRIORIDAD p ON p.PRI_codigo = r.PRI_codigo
        INNER JOIN IMPACTO imp ON imp.IMP_codigo = r.IMP_codigo
        INNER JOIN USUARIO u ON u.USU_codigo = r.USU_codigo
        INNER JOIN PERSONA per ON per.PER_codigo = u.PER_codigo
        WHERE r.EST_codigo = 4
        ORDER BY REC_numero DESC
        OFFSET :start ROWS
        FETCH NEXT :limit ROWS ONLY";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $registros;
      } else {
        echo "Error de conexión cierre Controller la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al obtener las recepciones sin cerrar: " . $e->getMessage();
      return null;
    }
  }

  // TODO: Metodo para listar incidencias recepciondas
  public function listarRecepciones()
  {
    $conector = parent::getConexion();
    if ($conector != null) {
      try {
        $sql = "SELECT REC_numero, i.INC_numero, 
        (CONVERT(VARCHAR(10),REC_fecha,103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VarChar(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
        a.ARE_nombre, INC_codigoPatrimonial, c.CAT_nombre, p.PRI_nombre, imp.IMP_descripcion, u.USU_nombre,
        PER_nombres + ' ' + PER_apellidoPaterno AS Usuario
        FROM RECEPCION r
        INNER JOIN INCIDENCIA i ON i.INC_numero = r.INC_numero
        INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
        INNER JOIN CATEGORIA c ON c.CAT_codigo = i.CAT_codigo
        INNER JOIN PRIORIDAD p ON p.PRI_codigo = r.PRI_codigo
        INNER JOIN IMPACTO imp ON imp.IMP_codigo = r.IMP_codigo
        INNER JOIN USUARIO u ON u.USU_codigo = r.USU_codigo
        INNER JOIN PERSONA per ON per.PER_codigo = u.PER_codigo
        ORDER BY  i.INC_numero DESC
";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $registros;
      } catch (PDOException $e) {
        // Manejar cualquier excepción o error que pueda surgir al ejecutar la consulta
        echo "Error al listar recepciones: " . $e->getMessage();
        return null;
      }
    } else {
      echo "Error de conexión cierre Controller la base de datos.";
      return null;
    }
  }

  // TODO: Contar el total de recepciones sin cerrar
  public function contarRecepcionesSinCerrar()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as total FROM RECEPCION r
      WHERE r.EST_codigo = 4";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al contar recepciones sin cerrar: " . $e->getMessage());
    }
  }


  public function BuscarRecepcionesRegistradas()
  {
    try {
      $conn = $this->conexion->getConexion();

      if ($conn != null) {
        $sql = "SELECT R.REC_codigo, R.INC_numero, I.INC_codigoPatrimonial AS codigo_patrimonial, I.INC_estado, 
                P.PRI_nombre AS prioridad, R.REC_fecha, Imp.IMP_nombre AS impacto, C.CAT_nombre
          FROM Recepcion R
          INNER JOIN Incidencia I ON R.INC_numero = I.INC_numero
          INNER JOIN Prioridad P ON R.PRI_codigo = P.PRI_codigo
          INNER JOIN Impacto Imp ON R.IMP_codigo = Imp.IMP_codigo 
		      inner join CATEGORIA c on i.CAT_codigo = c.CAT_codigo

          WHERE I.INC_estado != 3 and R.REC_codigo like '1%'
          ORDER BY R.REC_codigo";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener las recepciones: " . $e->getMessage());
    }
  }


  // TODO: Contar recepcions del ultimo mes para el administrador
  public function contarRecepcionesUltimoMesAdministrador()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as recepciones_mes_actual FROM RECEPCION 
        WHERE REC_FECHA >= DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1)
        AND EST_codigo = 4";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['recepciones_mes_actual'];
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar recepciones del ultimo mes para el administrador: " . $e->getMessage();
      return null;
    }
  }

  // TODO: Contar recepcions del ultimo mes para el administrador
  public function contarRecepcionesUltimoMesUsuario($area)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as recepciones_mes_actual FROM RECEPCION r
				        INNER JOIN INCIDENCIA i ON i.INC_numero = r.INC_numero
                INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
                WHERE REC_FECHA >= DATEADD(MONTH, -1, GETDATE()) AND r.EST_codigo = 4 AND
                a.ARE_codigo = :are_codigo";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':are_codigo', $area, PDO::PARAM_INT); // Vinculamos el parámetro
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['recepciones_mes_actual'];
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar recepciones del ultimo mes para el administrador: " . $e->getMessage();
      return null;
    }
  }
}
