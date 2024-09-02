<?php
require_once 'config/conexion.php';

class BienModel extends Conexion
{

  public function __construct()
  {
    parent::__construct();
  }

  // Metodo para obtener areas por el ID
  public function obtenerTipoBienPorID($codigoBien)
  {
    try {
      $conector = $this->getConexion();
      if ($conector != null) {
        $sql = "SELECT * FROM BIEN WHERE BIE_codigo = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([$codigoBien]);
        $registros = $stmt->fetch(PDO::FETCH_ASSOC);
        return $registros;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener el tipo de bien: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para validar existencia de codigo de bien
  public function validarBienExistente($codigoIdentificador)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        // Extraer los primeros 8 dígitos del código patrimonial
        $codigoParcial = substr($codigoIdentificador, 0, 8);
        $sql = "SELECT COUNT(*) FROM BIEN WHERE BIE_codigoPatrimonial = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([$codigoParcial]);
        // Obtener el conteo de coincidencias
        $count = $stmt->fetchColumn();
        return $count > 0;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al verificar el bien: " . $e->getMessage());
    }
  }

  // Metodo para insertar el tipo de bien
  public function insertarTipoBien($codigoIdentificador, $nombreTipoBien)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "INSERT INTO BIEN (BIE_codigoPatrimonial, BIE_nombre) VALUES (?, ?)";
        $stmt = $conector->prepare($sql);
        $stmt->execute([$codigoIdentificador, $nombreTipoBien]);
        return $conector->lastInsertId();
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al insertar tipo de bien: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para editar el tipo de bien
  public function editarTipoBien($codigoIdentificador, $nombreTipoBien, $codigoBien)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "UPDATE BIEN SET BIE_codigoPatrimonial = ? , BIE_nombre = ?
                WHERE BIE_codigo = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute($codigoIdentificador, $nombreTipoBien, $codigoBien);
        return $stmt->rowCount();
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al editar el tipo de bien: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para listar los bienes
  public function listarBienes()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM BIEN
                WHERE BIE_codigo <> 1";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al listar bienes: " . $e->getMessage());
      return null;
    }
  }
}