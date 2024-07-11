<?php
require_once 'config/conexion.php';

class IncidenciaModel extends Conexion
{

  // public function __construct()
  // {
  //   parent::__construct();
  // }

  // //TODO: Metodo para obtener incidencias por ID
  // public function obtenerIncidenciaPorId($IncNumero)
  // {
  //   $conector = parent::getConexion();
  //   try {
  //     if ($conector != null) {
  //       $sql = "SELECT * FROM  INCIDENCIA i
  //       INNER JOIN CATEGORIA c ON i.CAT_codigo = c.CAT_codigo 
  //       WHERE INC_numero = ?";
  //       $stmt = $conector->prepare($sql);
  //       $stmt->execute([$IncNumero]);
  //       $registros = $stmt->fetch(PDO::FETCH_ASSOC);
  //       return $registros;
  //     } else {
  //       throw new Exception("Error de conexión a la base de datos.");
  //     }
  //   } catch (PDOException $e) {
  //     echo "Error al obtener los registros de incidencias: " . $e->getMessage();
  //     return null;
  //   }
  // }

  // // TODO: Metodo para insertar incidencias - Administrador
  // // public function insertarIncidenciaAdministrador($fecha, $hora, $asunto, $descripcion, $documento, $codigoPatrimonial, $categoria, $area, $usuario)
  // // {
  // //   $conector = parent::getConexion();
  // //   try {
  // //     $sql = "EXEC SP_Registrar_Incidencia_Admin @INC_fecha = :fecha, @INC_hora = :hora, @INC_asunto = :asunto, @INC_descripcion = :descripcion, @INC_documento = :documento, @INC_codigoPatrimonial = :codigoPatrimonial, @CAT_codigo = :categoria, @ARE_codigo = :area, @USU_codigo = :usuario";
  // //     $stmt = $conector->prepare($sql);
  // //     $stmt->bindParam(':fecha', $fecha);
  // //     $stmt->bindParam(':hora', $hora);
  // //     $stmt->bindParam(':asunto', $asunto);
  // //     $stmt->bindParam(':descripcion', $descripcion);
  // //     $stmt->bindParam(':documento', $documento);
  // //     $stmt->bindParam(':codigoPatrimonial', $codigoPatrimonial);
  // //     $stmt->bindParam(':categoria', $categoria);
  // //     $stmt->bindParam(':area', $area);
  // //     $stmt->bindParam(':usuario', $usuario);
  // //     $success = $stmt->execute();
  // //     return $conector->lastInsertId();
  // //   } catch (PDOException $e) {
  // //     echo "Error al insertar recepcionn: " . $e->getMessage();
  // //     return false;
  // //   }
  // // }
  // public function insertarIncidenciaAdministrador(
  //   $INC_fecha,
  //   $INC_hora,
  //   $INC_asunto,
  //   $INC_descripcion,
  //   $INC_documento,
  //   $INC_codigoPatrimonial,
  //   $EST_codigo,
  //   $CAT_codigo,
  //   $ARE_codigo,
  //   $USU_codigo
  // ) {
  //   $conector = parent::getConexion();
  //   try {
  //     if ($conector != null) {
  //       $sql = "INSERT INTO INCIDENCIA (INC_fecha, INC_hora, INC_asunto, INC_descripcion, INC_documento, INC_codigoPatrimonial, EST_codigo, CAT_codigo, ARE_codigo, USU_codigo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  //       $stmt = $conector->prepare($sql);
  //       $success = $stmt->execute([
  //         $INC_fecha,
  //         $INC_hora,
  //         $INC_asunto,
  //         $INC_descripcion,
  //         $INC_documento,
  //         $INC_codigoPatrimonial,
  //         3,
  //         $CAT_codigo,
  //         $ARE_codigo,
  //         $USU_codigo
  //       ]);
  //       if ($success) {
  //         $lastId = $conector->lastInsertId();
  //         return $lastId;
  //       } else {
  //         return false;
  //       }
  //     }
  //   } catch (PDOException $e) {
  //     echo "Error al insertar la incidencia para el administrador: " . $e->getMessage();
  //     return false;
  //   }
  // }

  // // TODO: Metodo listar incidencias Administrador - FORM CONSULTAR INCIDENCIA
  // public function listarIncidenciasAdministrador()
  // {
  //   $conector = parent::getConexion();
  //   try {
  //     if ($conector != null) {
  //       $sql = "SELECT INC_numero, (CONVERT(VARCHAR(10),INC_fecha,103) + ' - '+   STUFF(RIGHT('0' + CONVERT(VarChar(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada, INC_asunto, INC_descripcion, INC_documento, INC_codigoPatrimonial
  //     , c.CAT_nombre, a.ARE_nombre, u.USU_nombre, e.EST_descripcion
  //     FROM INCIDENCIA i
  //     INNER JOIN CATEGORIA c ON c.CAT_codigo = i.CAT_codigo
  //     INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
  //     INNER JOIN USUARIO u ON u.USU_codigo = i.USU_codigo
  //     INNER JOIN ESTADO e ON e.EST_codigo = i.EST_codigo
  //     ORDER BY INC_numero DESC";
  //       $stmt = $conector->prepare($sql);
  //       $stmt->execute();
  //       $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  //       return $result;
  //     } else {
  //       throw new Exception("Error de conexión a la base de datos.");
  //     }
  //   } catch (PDOException $e) {
  //     throw new Exception("Error al listar las incidencias para el administrador: " . $e->getMessage());
  //   }
  // }

  // // TODO: Metodo listar incidencias Usuario - FORM CONSULTAR INCIDENCIA
  // public function listarIncidenciasUsuario($ARE_codigo)
  // {
  //   $conector = parent::getConexion();
  //   try {
  //     if ($conector != null) {
  //       $sql = "SELECT
  //                     I.INC_numero,
  //                     (CONVERT(VARCHAR(10), INC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada,
  //                     A.ARE_nombre,
  //                     CAT.CAT_nombre,
  //                     I.INC_asunto,
  //                     I.INC_codigoPatrimonial,
  //                     (CONVERT(VARCHAR(10), REC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
  //                     PRI.PRI_nombre,
  //                     IMP.IMP_descripcion,
  //                     (CONVERT(VARCHAR(10), CIE_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), CIE_hora, 0), 7), 6, 0, ' ')) AS fechaCierreFormateada,
  //                     O.OPE_descripcion,
  //                     U.USU_nombre,
  //                     CASE
  //                         WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
  //                         ELSE E.EST_descripcion
  //                     END AS ESTADO
  //                   FROM INCIDENCIA I
  //                   INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
  //                   INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
  //                   INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
  //                   LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
  //                   LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
  //                   LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
  //                   LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
  //                   LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
  //                   LEFT JOIN OPERATIVIDAD O ON O.OPE_codigo = C.OPE_codigo
  //                   LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
  //                   WHERE (I.EST_codigo IN (3, 4, 5) OR C.EST_codigo IN (3, 4, 5))
  //                   AND A.ARE_codigo = :are_codigo"; // Usamos el parámetro nombrado :are_codigo
  //       $stmt = $conector->prepare($sql);
  //       $stmt->bindParam(':are_codigo', $ARE_codigo, PDO::PARAM_INT); // Vinculamos el parámetro
  //       $stmt->execute();

  //       $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  //       return $result;
  //     } else {
  //       throw new Exception("Error de conexión a la base de datos.");
  //     }
  //   } catch (PDOException $e) {
  //     throw new Exception("Error al listar las incidencias para el usuario: " . $e->getMessage());
  //   }
  // }

  // // TODO: Metodo para listar incidencias registradas por el administrador
  // public function listarIncidenciasRegistroAdmin($start, $limit)
  // {
  //   $conector = parent::getConexion();
  //   try {
  //     if ($conector != null) {
  //       $sql = "SELECT INC_numero, (CONVERT(VARCHAR(10),INC_fecha,103) + ' - '+   STUFF(RIGHT('0' + CONVERT(VarChar(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada, INC_asunto, INC_descripcion, INC_documento, INC_codigoPatrimonial, c.CAT_nombre, a.ARE_nombre, u.USU_nombre
  //       FROM INCIDENCIA i
  //       INNER JOIN CATEGORIA c ON c.CAT_codigo = i.CAT_codigo
  //       INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
  //       INNER JOIN USUARIO u ON u.USU_codigo = i.USU_codigo
  //       ORDER BY i.INC_numero DESC
  //       OFFSET :start ROWS
  //       FETCH NEXT :limit ROWS ONLY";
  //       $stmt = $conector->prepare($sql);
  //       $stmt->bindParam(':start', $start, PDO::PARAM_INT);
  //       $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
  //       $stmt->execute();
  //       $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  //       return $result;
  //     } else {
  //       throw new Exception("Error de conexión a la base de datos.");
  //     }
  //   } catch (PDOException $e) {
  //     throw new Exception("Error al listar incidencias registradas por el administrador: " . $e->getMessage());
  //   }
  // }

  // // TODO: Contar el total de incidencias
  // public function contarIncidencias()
  // {
  //   $conector = $this->getConexion();
  //   $sql = "SELECT COUNT(*) as total FROM INCIDENCIA";
  //   $stmt = $conector->prepare($sql);
  //   $stmt->execute();
  //   $result = $stmt->fetch(PDO::FETCH_ASSOC);
  //   return $result['total'];
  // }

  // // TODO: Metodo para consultar incidencias 
  // public function consultarIncidencia($area, $fechaIncidencia)
  // {
  //   $conector = parent::getConexion();
  //   try {
  //     if ($conector) {
  //       $sql = "SELECT * FROM Incidencia i 
  //       INNER JOIN Categoria c ON i.CAT_codigo = c.CAT_codigo
  //       INNER JOIN Area a ON a.ARE_codigo = i.ARE_codigo
  //       WHERE (i.INC_fecha = $fechaIncidencia) OR
  //       (a.ARE_nombre = $area)";

  //       // Preparar la sentencia
  //       $stmt = $conector->prepare($sql);
  //     }
  //   } catch (PDOException $e) {
  //     throw new Exception("Error al obtener las recepciones: " . $e->getMessage());
  //   }
  // }

  // //TODO: Metodo para obtener incidencias sin recepcionar
  // public function obtenerIncidenciasSinRecepcionar($start, $limit)
  // {
  //   $conector = parent::getConexion();
  //   try {
  //     if ($conector != null) {

  //       $sql = "SELECT INC_numero, (CONVERT(VARCHAR(10),INC_fecha,103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VarChar(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada, INC_asunto, INC_descripcion, INC_documento, INC_codigoPatrimonial, c.CAT_nombre, a.ARE_nombre, u.USU_nombre
  //       FROM INCIDENCIA i
  //       INNER JOIN CATEGORIA c ON c.CAT_codigo = i.CAT_codigo
  //       INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
  //       INNER JOIN USUARIO u ON u.USU_codigo = i.USU_codigo
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

  // // TODO: Contar el total de incidencias sin recepcionar
  // public function contarIncidenciasSinRecepcionar()
  // {
  //   $conector = parent::getConexion();
  //   try {
  //     if ($conector != null) {
  //       $sql = "SELECT COUNT(*) as total FROM INCIDENCIA i
  //           WHERE i.EST_codigo = 3";
  //       $stmt = $conector->prepare($sql);
  //       $stmt->execute();
  //       $result = $stmt->fetch(PDO::FETCH_ASSOC);
  //       return $result['total'];
  //     }
  //   } catch (PDOException $e) {
  //     echo "Error al contar incidencias sin recepcionar: " . $e->getMessage();
  //     return null;
  //   }
  // }
}
