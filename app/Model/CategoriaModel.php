<?php
require_once 'config/conexion.php';

class CategoriaModel extends Conexion
{
  // Atributos de la clase
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

  // Metodo para insertar una nueva categoria
  public function insertarCategoria()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "INSERT INTO CATEGORIA (CAT_nombre) VALUES (?)";
        $stmt = $conector->prepare($sql);
        $stmt->execute([
          $this->nombreCategoria
        ]);
        return $conector->lastInsertId();
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al insertar la categoría: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para listar categorias
  public function listarCategorias()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT CAT_codigo, CAT_nombre FROM CATEGORIA 
                ORDER BY CAT_codigo ASC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener las categorías: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para obtener la categoria por el ID
  public function obtenerCategoriaPorId()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM CATEGORIA 
                WHERE CAT_codigo = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([
          $this->codigoCategoria
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener la categoría: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para editar categoria
  public function editarCategoria()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "UPDATE CATEGORIA SET CAT_nombre = ? WHERE CAT_codigo = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([
          $this->nombreCategoria,
          $this->codigoCategoria
        ]);
        return $stmt->rowCount();
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al actualizar la categoría: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para eliminar categoria
  public function eliminarCategoria()
  {

    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "DELETE FROM CATEGORIA WHERE CAT_codigo = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([
          $this->codigoCategoria
        ]);
        return $stmt->rowCount();
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al eliminar la categoría: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para filtrar categoria por termino de busqueda
  public function filtrarBusqueda($termino)
  {
    if ($termino === null || trim($termino) === '') {
      throw new Exception("El término de búsqueda no puede estar vacío.");
    }

    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM CATEGORIA WHERE CAT_nombre LIKE ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute(['%' . $termino . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al buscar categorías: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para contar la cantidad de categorias registradas
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
        throw new Exception("Error de conexión con la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al contar categorias: " . $e->getMessage());
      return null;
    }
  }
}
