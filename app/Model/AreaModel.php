<?php
require_once 'config/conexion.php';

class AreaModel extends Conexion
{
  // Atributos de la clase
  protected $codigoArea;
  protected $nombreArea;

  public function __construct(
    $codigoArea = null,
    $nombreArea = null
  ) {
    parent::__construct();
    $this->codigoArea = $codigoArea;
    $this->nombreArea = $nombreArea;
  }

  // Metodo para registrar areas
  public function insertarArea($nombreArea)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "INSERT INTO AREA (ARE_nombre) VALUES (?)";
        $stmt = $conector->prepare($sql);
        $stmt->execute([$nombreArea]);
        return $conector->lastInsertId();
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException(("Error al insertar area: " . $e->getMessage()));
    }
  }

  // Metodo para listar areas
  public function listarArea($start, $limit)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT ARE_codigo, ARE_nombre FROM AREA ORDER BY ARE_codigo ASC
        OFFSET ? ROWS
        FETCH NEXT ? ROWS ONLY";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(1, $start, PDO::PARAM_INT);
        $stmt->bindParam(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultados;
      } else {
        throw new Exception("Error al conectar a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener las áreas: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para obtener areas por el ID
  public function obtenerAreaPorId()
  {
    try {
      $conector = $this->getConexion();
      if ($conector != null) {
        $sql = "SELECT * FROM AREA WHERE ARE_codigo = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([$this->codigoArea]);
        $registros = $stmt->fetch(PDO::FETCH_ASSOC);
        return $registros;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener el área: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para editar areas
  public function editarArea()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "UPDATE AREA SET ARE_nombre = ? WHERE ARE_codigo = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([
          $this->nombreArea,
          $this->codigoArea
        ]);
        return $stmt->rowCount();
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al actualizar el área: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para contar la cantidad de areas
  public function contarAreas()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "  SELECT COUNT(*) AS cantidadAreas FROM AREA";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['cantidadAreas'];
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al contar areas: " . $e->getMessage());
      return null;
    }
  }
}
