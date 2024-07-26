<?php
require_once 'config/conexion.php';

class CategoriaModel extends Conexion
{
  protected $codigoCategoria;
  protected $nombreCategoria;

  public function __construct(
    $codigoCategoria = null,
    $nombreCategoria = null
  ) {
    parent::__construct();
    $this->codigoCategoria = $codigoCategoria;
    $this->nombreCategoria = $nombreCategoria;
  }

  // Method to register a new category
  public function registrarCategoria()
  {
    if ($this->nombreCategoria === null || trim($this->nombreCategoria) === '') {
      throw new Exception("El nombre de la categoría no puede estar vacío.");
    }
    $conector = parent::getConexion();
    try {
      $sql = "INSERT INTO CATEGORIA (CAT_nombre) VALUES (?)";
      $stmt = $conector->prepare($sql);
      $stmt->execute([$this->nombreCategoria]);
      return $conector->lastInsertId();
    } catch (PDOException $e) {
      throw new Exception("Error al insertar la categoría: " . $e->getMessage());
    }
  }

  // Method to list all categories
  public function listarCategorias()
  {
    $conector = parent::getConexion();
    try {
      $sql = "SELECT CAT_codigo, CAT_nombre FROM CATEGORIA ORDER BY CAT_codigo ASC";
      $stmt = $conector->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener las categorías: " . $e->getMessage());
    }
  }

  // Method to get a category by its ID
  public function obtenerCategoriaPorId($codigoCategoria)
  {
    if ($codigoCategoria === null) {
      throw new Exception("El código de la categoría no puede ser nulo.");
    }

    $conector = parent::getConexion();
    try {
      $sql = "SELECT * FROM CATEGORIA WHERE CAT_codigo = ?";
      $stmt = $conector->prepare($sql);
      $stmt->execute([$codigoCategoria]);
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener la categoría: " . $e->getMessage());
    }
  }

  // Method to edit a category
  public function editarCategoria()
  {
    if ($this->codigoCategoria === null) {
      throw new Exception("El código de la categoría no puede ser nulo.");
    }

    if ($this->nombreCategoria === null || trim($this->nombreCategoria) === '') {
      throw new Exception("El nombre de la categoría no puede estar vacío.");
    }

    $conector = parent::getConexion();
    try {
      $sql = "UPDATE CATEGORIA SET CAT_nombre = ? WHERE CAT_codigo = ?";
      $stmt = $conector->prepare($sql);
      $stmt->execute([$this->nombreCategoria, $this->codigoCategoria]);
      return $stmt->rowCount();
    } catch (PDOException $e) {
      throw new Exception("Error al actualizar la categoría: " . $e->getMessage());
    }
  }

  // Method to delete a category
  public function eliminarCategoria($codigoCategoria)
  {
    if ($codigoCategoria === null) {
      throw new Exception("El código de la categoría no puede ser nulo.");
    }

    $conector = parent::getConexion();
    try {
      $conector = $this->getConexion();
      $sql = "DELETE FROM CATEGORIA WHERE CAT_codigo = ?";
      $stmt = $conector->prepare($sql);
      $stmt->execute([$codigoCategoria]);
      return $stmt->rowCount();
    } catch (PDOException $e) {
      throw new Exception("Error al eliminar la categoría: " . $e->getMessage());
    }
  }

  // Method to filter categories by a search term
  public function filtrarBusqueda($termino)
  {
    if ($termino === null || trim($termino) === '') {
      throw new Exception("El término de búsqueda no puede estar vacío.");
    }

    $conector = parent::getConexion();
    try {
      $sql = "SELECT * FROM CATEGORIA WHERE CAT_nombre LIKE ?";
      $stmt = $conector->prepare($sql);
      $stmt->execute(['%' . $termino . '%']);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al buscar categorías: " . $e->getMessage());
    }
  }

  // TODO: METODO PARA CONTAR LA CANTIDAD DE AREAS
  public function contarCategorias()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "  SELECT COUNT(*) AS cantidadCategorias FROM CATEGORIA";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['cantidadCategorias'];
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar categorias: " . $e->getMessage();
      return null;
    }
  }
}
