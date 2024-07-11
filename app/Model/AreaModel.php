<?php
require_once 'config/conexion.php';

class AreaModel extends Conexion
{
  protected $codigoArea;
  protected $nombreArea;

  public function __construct($codigoArea = null, $nombreArea = null)
  {
    parent::__construct();
    $this->codigoArea = $codigoArea;
    $this->nombreArea = $nombreArea;
  }

  public function registrarArea()
  {
    if ($this->nombreArea === null || trim($this->nombreArea) === '') {
      throw new Exception("El nombre de la area no puede estar vacío.");
    }
    try {
      $conector = $this->getConexion();
      $sql = "INSERT INTO AREA (ARE_nombre) VALUES (?)";
      $stmt = $conector->prepare($sql);
      $stmt->execute([$this->nombreArea]);
      return $conector->lastInsertId();
    } catch (PDOException $e) {
      throw new Exception(("Error al insertar area: " . $e->getMessage()));
    }
  }

  public function listarArea()
  {
    try {
      $conector = $this->getConexion();
      $sql = "SELECT ARE_codigo, ARE_nombre FROM AREA ORDER BY ARE_codigo ASC";
      $stmt = $conector->prepare($sql);
      $stmt->execute();
      $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $resultados;
    } catch (PDOException $e) {
      throw new Exception("Error al obtener las áreas: " . $e->getMessage());
    }
  }

  public function obtenerAreaPorId($codigoArea)
  {
    try {
      $conector = $this->getConexion();

      if ($conector != null) {
        $sql = " SELECT * FROM AREA WHERE ARE_codigo = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([$codigoArea]);
        $registros = $stmt->fetch(PDO::FETCH_ASSOC);
        return $registros;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener el área: " . $e->getMessage());
    }
  }
  // Method to edit a category
  public function editarArea()
  {
    if ($this->codigoArea === null) {
      throw new Exception("El código del area no puede ser nulo.");
    }

    if ($this->nombreArea === null || trim($this->nombreArea) === '') {
      throw new Exception("El nombre del area no puede estar vacío.");
    }

    try {
      $conector = $this->getConexion();
      $sql = "UPDATE AREA SET ARE_nombre = ? WHERE ARE_codigo = ?";
      $stmt = $conector->prepare($sql);
      $stmt->execute([$this->nombreArea, $this->codigoArea]);
      return $stmt->rowCount();
    } catch (PDOException $e) {
      throw new Exception("Error al actualizar el área: " . $e->getMessage());
    }
  }
}
