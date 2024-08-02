<?php
require_once 'config/conexion.php';

class IncidenciaModel extends Conexion
{

  public function __construct()
  {
    parent::__construct();
  }

  //TODO: Metodo para obtener incidencias por ID
  public function obtenerIncidenciaPorId($IncNumero)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM  INCIDENCIA i
        INNER JOIN CATEGORIA c ON i.CAT_codigo = c.CAT_codigo 
        WHERE INC_numero = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([$IncNumero]);
        $registros = $stmt->fetch(PDO::FETCH_ASSOC);
        return $registros;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      echo "Error al obtener los registros de incidencias: " . $e->getMessage();
      return null;
    }
  }

  // TODO: Metodo para insertar incidencias - Administrador
  public function insertarIncidenciaAdministrador($INC_fecha, $INC_hora, $INC_asunto, $INC_descripcion, $INC_documento, $INC_codigoPatrimonial,  $EST_codigo, $CAT_codigo, $ARE_codigo, $USU_codigo)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "INSERT INTO INCIDENCIA (INC_fecha, INC_hora, INC_asunto, INC_descripcion, INC_documento, INC_codigoPatrimonial, EST_codigo, CAT_codigo, ARE_codigo, USU_codigo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conector->prepare($sql);
        $success = $stmt->execute([
          $INC_fecha,
          $INC_hora,
          $INC_asunto,
          $INC_descripcion,
          $INC_documento,
          $INC_codigoPatrimonial,
          3,
          $CAT_codigo,
          $ARE_codigo,
          $USU_codigo
        ]);
        if ($success) {
          $lastId = $conector->lastInsertId();
          return $lastId;
        } else {
          return false;
        }
      }
    } catch (PDOException $e) {
      echo "Error al insertar la incidencia para el administrador: " . $e->getMessage();
      return false;
    }
  }

  // TODO: Metodo para insertar incidencias - Usuario
  public function insertarIncidenciaUsuario($INC_fecha, $INC_hora, $INC_asunto, $INC_descripcion, $INC_documento, $INC_codigoPatrimonial,  $EST_codigo, $CAT_codigo, $ARE_codigo, $USU_codigo)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "INSERT INTO INCIDENCIA (INC_fecha, INC_hora, INC_asunto, INC_descripcion, INC_documento, INC_codigoPatrimonial, EST_codigo, CAT_codigo, ARE_codigo, USU_codigo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conector->prepare($sql);
        $success = $stmt->execute([
          $INC_fecha,
          $INC_hora,
          $INC_asunto,
          $INC_descripcion,
          $INC_documento,
          $INC_codigoPatrimonial,
          3,
          $CAT_codigo,
          $ARE_codigo,
          $USU_codigo
        ]);
        if ($success) {
          $lastId = $conector->lastInsertId();
          return $lastId;
        } else {
          return false;
        }
      }
    } catch (PDOException $e) {
      echo "Error al insertar la incidencia para el usuario: " . $e->getMessage();
      return false;
    }
  }

  // TODO: Metodo listar incidencias Administrador - FORM CONSULTAR INCIDENCIA
  public function listarIncidenciasAdministrador()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT INC_numero, (CONVERT(VARCHAR(10),INC_fecha,103) + ' - '+   STUFF(RIGHT('0' + CONVERT(VarChar(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada, INC_asunto, INC_descripcion, INC_documento, INC_codigoPatrimonial
      , c.CAT_nombre, a.ARE_nombre, u.USU_nombre, e.EST_descripcion
      FROM INCIDENCIA i
      INNER JOIN CATEGORIA c ON c.CAT_codigo = i.CAT_codigo
      INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
      INNER JOIN USUARIO u ON u.USU_codigo = i.USU_codigo
      INNER JOIN ESTADO e ON e.EST_codigo = i.EST_codigo
      ORDER BY INC_numero DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar las incidencias para el administrador: " . $e->getMessage());
    }
  }

  // TODO: Metodo listar incidencias Usuario - FORM CONSULTAR INCIDENCIA
  public function listarIncidenciasUsuario($ARE_codigo)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT
          I.INC_numero,
          (CONVERT(VARCHAR(10), INC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada,
          A.ARE_nombre,
          CAT.CAT_nombre,
          I.INC_asunto,
          I.INC_codigoPatrimonial,
          (CONVERT(VARCHAR(10), REC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
          PRI.PRI_nombre,
          IMP.IMP_descripcion,
          (CONVERT(VARCHAR(10), CIE_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), CIE_hora, 0), 7), 6, 0, ' ')) AS fechaCierreFormateada,
          O.CON_descripcion,
          U.USU_nombre,
          CASE
              WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
              ELSE E.EST_descripcion
          END AS ESTADO
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
        WHERE (I.EST_codigo IN (3, 4, 5) OR C.EST_codigo IN (3, 4, 5))
        AND A.ARE_codigo = :are_codigo"; // Usamos el parámetro nombrado :are_codigo
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':are_codigo', $ARE_codigo, PDO::PARAM_INT); // Vinculamos el parámetro
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar las incidencias para el usuario: " . $e->getMessage());
    }
  }

  // TODO: Metodo para listar incidencias registradas por el administrador
  public function listarIncidenciasRegistroAdmin($start, $limit)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT INC_numero, (CONVERT(VARCHAR(10),INC_fecha,103) + ' - '+   STUFF(RIGHT('0' + CONVERT(VarChar(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada, INC_asunto, INC_descripcion, INC_documento, INC_codigoPatrimonial, c.CAT_nombre, a.ARE_nombre, u.USU_nombre,
        p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario
        FROM INCIDENCIA i
        INNER JOIN CATEGORIA c ON c.CAT_codigo = i.CAT_codigo
        INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
        INNER JOIN USUARIO u ON u.USU_codigo = i.USU_codigo
        INNER JOIN PERSONA p ON P.PER_codigo = u.USU_codigo
        ORDER BY i.INC_numero DESC
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
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar incidencias registradas por el administrador: " . $e->getMessage());
    }
  }

  public function listarIncidenciasAdminFecha($fecha)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        // Prepara la consulta SQL con un marcador de posición para la fecha
        $sql = "SELECT 
          I.INC_numero,
          (CONVERT(VARCHAR(10), INC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada,
          A.ARE_nombre,
          CAT.CAT_nombre,
          I.INC_asunto,
          I.INC_documento,
          I.INC_codigoPatrimonial,
          (CONVERT(VARCHAR(10), REC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
          PRI.PRI_nombre,
          IMP.IMP_descripcion,
          (CONVERT(VARCHAR(10), CIE_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), CIE_hora, 0), 7), 6, 0, ' ')) AS fechaCierreFormateada,
          O.CON_descripcion,
          U.USU_nombre,
          p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario,
          CASE
              WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
              ELSE E.EST_descripcion
          END AS ESTADO
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
      AND CAST(I.INC_fecha AS DATE) = CAST(:fechaConsulta AS DATE)
      ORDER BY I.INC_numero DESC";

        // Prepara y ejecuta la consulta
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':fechaConsulta', $fecha, PDO::PARAM_STR); // Asigna el parámetro de fecha
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar incidencias registradas por el administrador: " . $e->getMessage());
    }
  }



  //  TODO: Contar el total de incidencias para empaginar tabla - ADMINISTRADOR
  public function contarIncidenciasAdministrador()
  {
    $conector = $this->getConexion();
    $sql = "SELECT COUNT(*) as total FROM INCIDENCIA";
    $stmt = $conector->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
  }
  //  TODO: Contar el total de incidencias para empaginar tabla - USUARIO
  public function contarIncidenciasUsuario($ARE_codigo)
  {
    $conector = $this->getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as total FROM INCIDENCIA 
                    WHERE ARE_codigo = :are_codigo";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':are_codigo', $ARE_codigo, PDO::PARAM_INT); // Vinculamos el parámetro
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar las incidencias para el usuario: " . $e->getMessage());
    }
  }

  // TODO: Metodo para listar incidencias registradas por el usuario de un area especifica
  public function listarIncidenciasRegistroUsuario($ARE_codigo, $start, $limit)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT INC_numero, 
          (CONVERT(VARCHAR(10), INC_fecha, 103) + ' - ' + 
          STUFF(RIGHT('0' + CONVERT(VARCHAR(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada, 
          INC_asunto, INC_descripcion, INC_documento, INC_codigoPatrimonial, 
          c.CAT_nombre, a.ARE_nombre, u.USU_nombre
          FROM INCIDENCIA i
          INNER JOIN CATEGORIA c ON c.CAT_codigo = i.CAT_codigo
          INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
          INNER JOIN USUARIO u ON u.USU_codigo = i.USU_codigo
          WHERE a.ARE_codigo = :are_codigo
          ORDER BY i.INC_numero DESC
          OFFSET :start ROWS
          FETCH NEXT :limit ROWS ONLY";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':are_codigo', $ARE_codigo, PDO::PARAM_INT);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar incidencias registradas por el usuario: " . $e->getMessage());
    }
  }

  //TODO: Metodo para obtener incidencias sin recepcionar
  public function obtenerIncidenciasSinRecepcionar($start, $limit)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {

        $sql = "SELECT INC_numero, (CONVERT(VARCHAR(10),INC_fecha,103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VarChar(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada, 
        INC_asunto, INC_descripcion, 
        INC_documento, INC_codigoPatrimonial, 
        c.CAT_nombre, a.ARE_nombre, u.USU_nombre,
        p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario
        FROM INCIDENCIA i
        INNER JOIN CATEGORIA c ON c.CAT_codigo = i.CAT_codigo
        INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
        INNER JOIN USUARIO u ON u.USU_codigo = i.USU_codigo
        INNER JOIN PERSONA p ON p.PER_codigo = u.PER_codigo
        WHERE i.EST_codigo = 3
        ORDER BY INC_numero DESC
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
      echo "Error al obtener los registros de incidencias sin recepcionar: " . $e->getMessage();
      return null;
    }
  }

  // TODO: Contar el total de incidencias sin recepcionar
  public function contarIncidenciasSinRecepcionar()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as total FROM INCIDENCIA i
            WHERE i.EST_codigo = 3";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
      }
    } catch (PDOException $e) {
      echo "Error al contar incidencias sin recepcionar: " . $e->getMessage();
      return null;
    }
  }

  // METODOS PARA EL PANEL INICIO

  // TODO: Contar incidencias del ultimo mes para el administrador
  public function contarIncidenciasUltimoMesAdministrador()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as incidencias_mes_actual
        FROM INCIDENCIA 
        WHERE INC_FECHA >= DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1)";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['incidencias_mes_actual'];
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar incidencias del ultimo mes para el administrador: " . $e->getMessage();
      return null;
    }
  }

  // METODO PARA CONTAR LOS PENDIENTES EN EL MES ACTUAL PARA EL ADMINISTRADOR
  public function contarPendientesUltimoMesAdministrador()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as pendientes_mes_actual FROM INCIDENCIA 
              WHERE INC_FECHA >= DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1)
              AND EST_codigo = 3";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['pendientes_mes_actual'];
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar incidencias del ultimo mes para el administrador: " . $e->getMessage();
      return null;
    }
  }

  // TODO: Contar incidencias del ultimo mes para el administrador
  public function contarIncidenciasUltimoMesUsuario($area)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as incidencias_mes_actual FROM INCIDENCIA i
        INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
        WHERE INC_FECHA >= DATEADD(MONTH, -1, GETDATE()) AND 
        a.ARE_codigo = :are_codigo";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':are_codigo', $area, PDO::PARAM_INT); // Vinculamos el parámetro
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['incidencias_mes_actual'];
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar incidencias del ultimo mes para el administrador: " . $e->getMessage();
      return null;
    }
  }

  // METODO PARA CONTAR LOS PENDIENTES EN EL MES ACTUAL PARA EL USUARIO
  public function contarPendientesUltimoMesUsuario($area)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as pendientes_mes_actual FROM INCIDENCIA i
                INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
                WHERE INC_FECHA >= DATEADD(MONTH, -1, GETDATE())
                AND EST_codigo = 3 AND
                a.ARE_codigo = :are_codigo";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':are_codigo', $area, PDO::PARAM_INT); // Vinculamos el parámetro
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['pendientes_mes_actual'];
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar incidencias del ultimo mes para el administrador: " . $e->getMessage();
      return null;
    }
  }

  // METODOS PARA CONSULTAS

  // TODO:  Metodo para consultar incidencias por area
  public function consultarIncidenciaAreaCodigoPatrimonial($area, $codigoPatrimonial)
  {
    $conector = parent::getConexion();
  }



  public function buscarIncidenciaAdministrador($codigoArea, $codigoPatrimonial, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion(); // Asumiendo que getConexion() devuelve la conexión PDO

    try {
      if ($conector != null) {
        // Construir la consulta SQL con parámetros
        $sql = "
                  DECLARE @codigoPatrimonial CHAR(12) = :codigoPatrimonial;
                  DECLARE @fechaInicio DATE = :fechaInicio;
                  DECLARE @fechaFin DATE = :fechaFin;
                  DECLARE @areaCodigo INT = :areaCodigo;
  
                  SELECT 
                      INC_numero, 
                      (CONVERT(VARCHAR(10), INC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada, 
                      INC_asunto, 
                      INC_descripcion, 
                      INC_documento, 
                      INC_codigoPatrimonial, 
                      c.CAT_nombre, 
                      a.ARE_nombre, 
                      u.USU_nombre, 
                      e.EST_descripcion
                  FROM INCIDENCIA i
                  INNER JOIN CATEGORIA c ON c.CAT_codigo = i.CAT_codigo
                  INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
                  INNER JOIN USUARIO u ON u.USU_codigo = i.USU_codigo
                  INNER JOIN ESTADO e ON e.EST_codigo = i.EST_codigo
                  WHERE 
                      (@codigoPatrimonial = '' OR INC_codigoPatrimonial = @codigoPatrimonial) AND
                      (@fechaInicio IS NULL OR INC_fecha >= @fechaInicio) AND
                      (@fechaFin IS NULL OR INC_fecha <= @fechaFin) AND
                      (@areaCodigo IS NULL OR a.ARE_codigo = @areaCodigo);
              ";

        // Preparar la consulta
        $stmt = $conector->prepare($sql);

        // Asignar valores a los parámetros
        $stmt->bindParam(':codigoPatrimonial', $codigoPatrimonial, PDO::PARAM_STR);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        $stmt->bindParam(':areaCodigo', $codigoArea, PDO::PARAM_INT);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado como un arreglo asociativo
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retornar el resultado
        return $result;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener las incidencias: " . $e->getMessage());
    }
  }



  // // public function buscarIncidenciaAdministrador($codigoArea, $codigoPatrimonial, $fechaInicio, $fechaFin)
  // // {
  // //   $conector = parent::getConexion(); // Asumiendo que getConexion() devuelve la conexión PDO

  // //   try {
  // //     if ($conector != null) {
  // //       Construir la consulta SQL con parámetros
  // //       $sql = "SELECT 
  // //               INC_numero, 
  // //               CONVERT(VARCHAR(10), INC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), INC_hora, 0), 7), 6, 0, ' ') AS fechaIncidenciaFormateada, 
  // //               INC_asunto, 
  // //               INC_descripcion, 
  // //               INC_documento, 
  // //               INC_codigoPatrimonial, 
  // //               c.CAT_nombre, 
  // //               a.ARE_nombre, 
  // //               u.USU_nombre, 
  // //               e.EST_descripcion
  // //           FROM INCIDENCIA i
  // //           INNER JOIN CATEGORIA c ON c.CAT_codigo = i.CAT_codigo
  // //           INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
  // //           INNER JOIN USUARIO u ON u.USU_codigo = i.USU_codigo
  // //           INNER JOIN ESTADO e ON e.EST_codigo = i.EST_codigo
  // //           WHERE 
  // //               (:codigoPatrimonial IS NULL OR INC_codigoPatrimonial = :codigoPatrimonial) AND
  // //               (:fechaInicio IS NULL OR INC_fecha >= :fechaInicio) AND
  // //               (:fechaFin IS NULL OR INC_fecha <= :fechaFin) AND
  // //               (:areaCodigo IS NULL OR a.ARE_codigo = :areaCodigo)";

  // //       Preparar la consulta
  // //       $stmt = $conector->prepare($sql);

  // //       Asignar valores a los parámetros
  // //       $stmt->bindParam(':codigoPatrimonial', $codigoPatrimonial);
  // //       $stmt->bindParam(':fechaInicio', $fechaInicio);
  // //       $stmt->bindParam(':fechaFin', $fechaFin);
  // //       $stmt->bindParam(':areaCodigo', $codigoArea);

  // //       Ejecutar la consulta
  // //       $stmt->execute();

  // //       Obtener el resultado como un arreglo asociativo
  // //       $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // //       Retornar el resultado
  // //       return $result;
  // //     } else {
  // //       throw new Exception("Error de conexión con la base de datos.");
  // //     }
  // //   } catch (PDOException $e) {
  // //     throw new Exception("Error al obtener las incidencias: " . $e->getMessage());
  // //   }
  // // }

  // METODO PARA CONTAR LA CANTIDAD DE AREAS
  public function contarIncidencias()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) AS cantidadIncidencias FROM INCIDENCIA";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['cantidadIncidencias'];
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar inciencias: " . $e->getMessage();
      return null;
    }
  }


  // METODO PARA CONTAR LA CANTIDAD DE AREAS
  public function areasConMasIncidencias()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT TOP 1 a.ARE_nombre AS areaMasIncidencia, COUNT(*) AS Incidencias
                    FROM INCIDENCIA i
                    INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
                    WHERE i.INC_fecha >= DATEADD(MONTH, -1, GETDATE()) 
                    GROUP BY a.ARE_nombre
                    ORDER BY Incidencias DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
          return $result['areaMasIncidencia'];
        } else {
          return "No hay &aacute;reas con incidencias en el último mes.";
        }
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar incidencias: " . $e->getMessage();
      return null;
    }
  }

  public function notificacionesAdmin()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT 
          I.INC_numero,
          (CONVERT(VARCHAR(10), I.INC_fecha, 103) + ' - ' + CONVERT(VARCHAR(5), I.INC_hora, 108)) AS fechaIncidenciaFormateada,
          A.ARE_nombre,
          I.INC_asunto,
          U.USU_nombre,
          p.PER_nombres + ' ' + p.PER_apellidoPaterno AS Usuario,
          I.EST_codigo,
          CASE
              WHEN DATEDIFF(MINUTE, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) < 60 THEN 
                  CAST(DATEDIFF(MINUTE, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) AS VARCHAR) + ' min'
              ELSE 
                  CAST(DATEDIFF(HOUR, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) AS VARCHAR) + ' h ' +
                  CAST(DATEDIFF(MINUTE, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) % 60 AS VARCHAR) + ' min'
          END AS tiempoDesdeIncidencia
      FROM INCIDENCIA I
      INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
      INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
      INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
      LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
      INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
      WHERE I.EST_codigo NOT IN (4, 5) AND A.ARE_codigo <>1
      AND CAST(I.INC_fecha AS DATE) = CAST(GETDATE() AS DATE)
      ORDER BY tiempoDesdeIncidencia ASC";

      // ORDER BY I.INC_numero DESC";

        // Prepara y ejecuta la consulta
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar notificaciones: " . $e->getMessage());
    }
  }
}
