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

  // Metodo para obtener los estados de la incidencia
  public function obtenerEstadoIncidencia($numeroIncidencia)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT EST_codigo FROM INCIDENCIA WHERE INC_numero = :numeroIncidencia";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':numeroIncidencia', $numeroIncidencia, PDO::PARAM_INT);
        $stmt->execute(); // ejecutar la consulta
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Obtener el resultado
        return $result ? $result['EST_codigo'] : null;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener el estado de la incidencia: " . $e->getMessage());
    }
  }

  /**
   * TODO: Método para insertar una nueva incidencia en la base de datos - ADMINISTRADOR / USUARIO.
   * 
   * Este método permite registrar una nueva incidencia en el sistema. Es utilizado tanto por
   * administradores como por usuarios. La incidencia se almacena en la base de datos a través
   * de un procedimiento almacenado.
   * 
   * Retorno:
   * - @return bool: Retorna `true` si la incidencia fue registrada exitosamente, `false` en caso contrario.
   */
  public function insertarIncidencia($INC_fecha, $INC_hora, $INC_asunto, $INC_descripcion, $INC_documento, $INC_codigoPatrimonial, $CAT_codigo, $ARE_codigo, $USU_codigo)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC SP_Registrar_Incidencia :fecha, :hora, :asunto, :descripcion, :documento, :codigoPatrimonial, :categoria, :area, :usuario";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':fecha', $INC_fecha);
        $stmt->bindParam(':hora', $INC_hora);
        $stmt->bindParam(':asunto', $INC_asunto);
        $stmt->bindParam(':descripcion', $INC_descripcion);
        $stmt->bindParam(':documento', $INC_documento);
        $stmt->bindParam(':codigoPatrimonial', $INC_codigoPatrimonial);
        $stmt->bindParam(':categoria', $CAT_codigo);
        $stmt->bindParam(':area', $ARE_codigo);
        $stmt->bindParam(':usuario', $USU_codigo);
        $success = $stmt->execute(); // Ejecutar la consulta
        return $success;
      }
      return false;
    } catch (PDOException $e) {
      echo "Error al insertar la incidencia para el administrador y usuario: " . $e->getMessage();
      return false;
    }
  }


  // Metodo para actualizar incidencia - Administrador
  public function editarIncidenciaAdmin($num_incidencia, $categoria, $area, $codigoPatrimonial, $asunto, $documento, $descripcion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_ActualizarIncidencia :num_incidencia, :categoria, :area, :codigoPatrimonial, :asunto, :documento, :descripcion";
        $stmt = $conector->prepare($sql);

        // Bindear los parámetros
        $stmt->bindParam(':num_incidencia', $num_incidencia, PDO::PARAM_INT);
        $stmt->bindParam(':categoria', $categoria, PDO::PARAM_INT);
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->bindParam(':codigoPatrimonial', $codigoPatrimonial);
        $stmt->bindParam(':asunto', $asunto);
        $stmt->bindParam(':documento', $documento);
        $stmt->bindParam(':descripcion', $descripcion);

        // Ejecutar el procedimiento almacenado
        $stmt->execute();

        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      echo "Error al editar incidencia para el administrador: " . $e->getMessage();
      return false;
    }
  }

  /**
   * Método para consultar incidencias de la base de datos (Administrador - Usuario).
   * 
   * Este método permite a un administrador y usuario consultar incidencias en el sistema. 
   * La incidencia se consulta con los detalles proporcionados, incluyendo la fecha, 
   * asunto, descripción, documento adjunto, código patrimonial, código de estado, código de categoría, 
   * código de área, y el código de usuario que registra la incidencia.
   * 
   * @return int|false Retorna el ID de la incidencia recién insertada si la operación es exitosa. 
   *                   En caso de error, retorna false.
   */
  // TODO: Metodo listar incidencias Administrador - FORM CONSULTAR INCIDENCIA
  public function listarIncidenciasAdministrador()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT 
        I.INC_numero,
        INC_numero_formato,
        (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
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
        p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario,
        -- Última modificación (fecha y hora más reciente)
        MAX(COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha)) AS ultimaFecha,
        MAX(COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora)) AS ultimaHora
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
        WHERE 
        I.EST_codigo IN (3, 4) -- Solo incluir incidencias con estado 3 o 4
        AND NOT EXISTS (  -- Excluir incidencias que hayan pasado al estado 5 en la tabla CIERRE
        SELECT 1 
        FROM CIERRE C2
        WHERE C2.REC_numero = R.REC_numero
        AND C2.EST_codigo = 5
        )
        GROUP BY 
        I.INC_numero,
        INC_numero_formato,
        I.INC_fecha,
        I.INC_hora,
        I.INC_codigoPatrimonial,
        I.INC_asunto,
        I.INC_documento,
        I.INC_descripcion,
        CAT.CAT_nombre,
        A.ARE_nombre,
        C.CIE_numero,
        EC.EST_descripcion,
        E.EST_descripcion,
        p.PER_nombres,
        p.PER_apellidoPaterno
        ORDER BY 
        -- Ordenar por la última modificación primero por fecha y luego por hora
        MAX(COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha)) DESC,
        MAX(COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora)) DESC";
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
  public function listarIncidenciasAdministradorGeneral()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT
          I.INC_numero,
          I.INC_numero_formato,
          (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
          A.ARE_nombre,
          CAT.CAT_nombre,
          I.INC_asunto,
          I.INC_codigoPatrimonial,
          I.INC_documento,
          (CONVERT(VARCHAR(10), REC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
          PRI.PRI_nombre,
          IMP.IMP_descripcion,
          (CONVERT(VARCHAR(10), CIE_fecha, 103)) AS fechaCierreFormateada,
          O.CON_descripcion,
          U.USU_nombre,
          CASE
              WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
              ELSE E.EST_descripcion
          END AS Estado
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
        ORDER BY I.INC_numero DESC ";
        $stmt = $conector->prepare($sql);
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
  // TODO: Metodo listar incidencias Usuario - FORM CONSULTAR INCIDENCIA
  public function listarIncidenciasUsuario($ARE_codigo)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT
          I.INC_numero,
          I.INC_numero_formato,
          (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
          A.ARE_nombre,
          CAT.CAT_nombre,
          I.INC_asunto,
          I.INC_documento,
          I.INC_codigoPatrimonial,
          (CONVERT(VARCHAR(10), REC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
          PRI.PRI_nombre,
          IMP.IMP_descripcion,
          (CONVERT(VARCHAR(10), CIE_fecha, 103)) AS fechaCierreFormateada,
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
        AND A.ARE_codigo = :are_codigo
        ORDER BY I.INC_numero_formato DESC"; // Usamos el parámetro nombrado :are_codigo
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

  // TODO: Método para listar incidencias registradas por el administrador
  public function listarIncidenciasRegistroAdmin($start, $limit)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vista_incidencias_administrador
          ORDER BY 
          -- Extraer el año de INC_numero_formato y ordenar por año de forma descendente
          SUBSTRING(INC_numero_formato, CHARINDEX('-', INC_numero_formato) + 1, 4) DESC,
          -- Ordenar por el número de incidencia también en orden descendente
          INC_numero_formato DESC
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
          I.INC_numero_formato,
          (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
          A.ARE_nombre,
          CAT.CAT_nombre,
          I.INC_asunto,
          I.INC_documento,
          I.INC_codigoPatrimonial,
          (CONVERT(VARCHAR(10), REC_fecha, 103)) AS fechaRecepcionFormateada,
          PRI.PRI_nombre,
          IMP.IMP_descripcion,
          (CONVERT(VARCHAR(10), CIE_fecha, 103)) AS fechaCierreFormateada,
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

  public function listarIncidenciasUserFecha($area, $fecha)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        // Prepara la consulta SQL con un marcador de posición para la fecha
        $sql = "SELECT 
          I.INC_numero,
          I.INC_numero_formato,
          (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
          A.ARE_nombre,
          CAT.CAT_nombre,
          I.INC_asunto,
          I.INC_documento,
          I.INC_codigoPatrimonial,
          (CONVERT(VARCHAR(10), REC_fecha, 103)) AS fechaRecepcionFormateada,
          PRI.PRI_nombre,
          IMP.IMP_descripcion,
          (CONVERT(VARCHAR(10), CIE_fecha, 103)) AS fechaCierreFormateada,
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
      AND A.ARE_codigo = :area
      AND CAST(I.INC_fecha AS DATE) = CAST(:fechaConsulta AS DATE)
      ORDER BY I.INC_numero DESC";

        // Prepara y ejecuta la consulta
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':area', $area); // Asigna el parámetro area
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
    $sql = "SELECT COUNT(*) as total FROM vista_incidencias_administrador";
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
        $sql = "SELECT 
        I.INC_numero, 
        INC_numero_formato,
        (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada, 
        INC_codigoPatrimonial, 
        INC_asunto, 
        INC_documento, 
        INC_descripcion, 
        CAT.CAT_nombre, 
        A.ARE_nombre, 
        CASE
            WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
            ELSE E.EST_descripcion
        END AS ESTADO,
        p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario
        FROM INCIDENCIA i
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
  // public function obtenerIncidenciasSinRecepcionar($start, $limit)
  // {
  //   $conector = parent::getConexion();
  //   try {
  //     if ($conector != null) {
  //       $sql = "SELECT INC_numero, 
  //       INC_numero_formato,
  //       (CONVERT(VARCHAR(10),INC_fecha,103)) AS fechaIncidenciaFormateada, 
  //       INC_asunto, INC_descripcion, 
  //       INC_documento, INC_codigoPatrimonial, 
  //       c.CAT_nombre, a.ARE_nombre, u.USU_nombre,
  //       p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario
  //       FROM INCIDENCIA i
  //       INNER JOIN CATEGORIA c ON c.CAT_codigo = i.CAT_codigo
  //       INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
  //       INNER JOIN USUARIO u ON u.USU_codigo = i.USU_codigo
  //       INNER JOIN PERSONA p ON p.PER_codigo = u.PER_codigo
  //       WHERE i.EST_codigo = 3
  //       ORDER BY INC_numero DESC
  //       OFFSET :start ROWS
  //       FETCH NEXT :limit ROWS ONLY";
  //       $stmt = $conector->prepare($sql);
  //       $stmt->bindParam(':start', $start, PDO::PARAM_INT);
  //       $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
  //       $stmt->execute();
  //       $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
  //       return $registros;
  //     } else {
  //       echo "Error de conexión cierre Controller la base de datos.";
  //       return null;
  //     }
  //   } catch (PDOException $e) {
  //     echo "Error al obtener los registros de incidencias sin recepcionar: " . $e->getMessage();
  //     return null;
  //   }
  // }

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
  // TODO:  Metodo para consultar incidencias por area - ADMINISTRADOR
  public function buscarIncidenciaAdministrador($area, $estado, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();

    try {
      if ($conector != null) {
        $sql = "EXEC sp_ConsultarIncidencias :area, :estado, :fechaInicio, :fechaFin";

        $stmt = $conector->prepare($sql);

        // Bindear los parámetros
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
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
      throw new Exception("Error al obtener las incidencias DX: " . $e->getMessage());
    }
  }

  // TODO:  Metodo para consultar incidencias totales - ADMINISTRADOR
  public function buscarIncidenciaTotales($area, $codigoPatrimonial, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();

    try {
      if ($conector != null) {
        $sql = "EXEC sp_ConsultarIncidenciasTotales :area, :codigoPatrimonial, :fechaInicio, :fechaFin";

        $stmt = $conector->prepare($sql);

        // Bindear los parámetros
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->bindParam(':codigoPatrimonial', $codigoPatrimonial);
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
      throw new Exception("Error al obtener las incidencias DX: " . $e->getMessage());
    }
  }

  // TODO:  Metodo para consultar incidencias por area - USUARIO
  public function buscarIncidenciaUsuario($area, $codigoPatrimonial, $estado, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();

    try {
      if ($conector != null) {
        $sql = "EXEC sp_ConsultarIncidenciasUsuario :area, :codigoPatrimonial, :estado, :fechaInicio, :fechaFin";

        $stmt = $conector->prepare($sql);

        // Bindear los parámetros
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->bindParam(':codigoPatrimonial', $codigoPatrimonial);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
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
      throw new Exception("Error al obtener las incidencias  usuario DX: " . $e->getMessage());
    }
  }

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

  // TODO: Notificaiones para el administrador
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
            WHEN DATEDIFF(DAY, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) < 1 THEN 
                CAST(DATEDIFF(HOUR, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) AS VARCHAR) + ' h ' +
                CAST(DATEDIFF(MINUTE, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) % 60 AS VARCHAR) + ' min'
            ELSE 
                CAST(DATEDIFF(DAY, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) AS VARCHAR) + ' d ' +
                CAST(DATEDIFF(HOUR, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) % 24 AS VARCHAR) + ' h ' +
                CAST(DATEDIFF(MINUTE, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) % 60 AS VARCHAR) + ' min'
        END AS tiempoDesdeIncidencia
        FROM INCIDENCIA I
        INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
        INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
        INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
        LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
        INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
        WHERE I.EST_codigo NOT IN (4, 5) 
        AND A.ARE_codigo <> 1
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

  // TODO: Notificaiones para el usuario
  public function notificacionesUser($area)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT 
        I.INC_numero,
        I.INC_numero_formato,
        (CONVERT(VARCHAR(10), I.INC_fecha, 103) + ' - ' + CONVERT(VARCHAR(5), I.INC_hora, 108)) AS fechaIncidenciaFormateada,
        A.ARE_nombre AS NombreAreaIncidencia,
        I.INC_asunto,
        C.USU_codigo,
        U.USU_nombre,
        p.PER_nombres + ' ' + p.PER_apellidoPaterno AS Usuario,
        A2.ARE_nombre AS NombreAreaCierre, -- Agregamos el nombre del área del usuario de cierre
        CASE
            WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
            ELSE E.EST_descripcion
        END AS ESTADO,
        CASE
            WHEN DATEDIFF(MINUTE, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) < 60 THEN 
                CAST(DATEDIFF(MINUTE, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) AS VARCHAR) + ' min'
            WHEN DATEDIFF(DAY, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) < 1 THEN 
                CAST(DATEDIFF(HOUR, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) AS VARCHAR) + ' h ' +
                CAST(DATEDIFF(MINUTE, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) % 60 AS VARCHAR) + ' min'
            ELSE 
                CAST(DATEDIFF(DAY, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) AS VARCHAR) + ' d ' +
                CAST(DATEDIFF(HOUR, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) % 24 AS VARCHAR) + ' h ' +
                CAST(DATEDIFF(MINUTE, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) % 60 AS VARCHAR) + ' min'
        END AS tiempoDesdeIncidencia
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
        LEFT JOIN USUARIO U2 ON U2.USU_codigo = C.USU_codigo -- Relacionamos el usuario del cierre
        LEFT JOIN AREA A2 ON U2.ARE_codigo = A2.ARE_codigo -- Relacionamos el área del usuario del cierre
        INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
        WHERE (I.EST_codigo NOT IN (3, 4) OR C.EST_codigo NOT IN (3, 4))
        AND A.ARE_codigo = :area
        ORDER BY tiempoDesdeIncidencia ASC";

        // Prepara y ejecuta la consulta
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
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
