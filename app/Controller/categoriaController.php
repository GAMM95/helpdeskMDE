<?php

require_once 'app/Model/CategoriaModel.php';

class CategoriaController
{
  private $categoriaModel;

  public function __construct()
  {
    $this->categoriaModel = new CategoriaModel();
  }

  // Metodo para registrar la categoria
  public function registrarCategoria()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $nombre = $_POST['NombreCategoria'] ?? null;

      if ($nombre === null || trim($nombre) === '') {
        echo "Error: El nombre de la categoría no puede estar vacío.";
        return;
      }

      try {
        $categoriaModel = new CategoriaModel(null, $nombre);
        $insertSuccessId = $categoriaModel->insertarCategoria();
        if ($insertSuccessId) {
          header('Location: modulo-categoria.php?CodCategoria=' . $insertSuccessId);
          exit();
        } else {
          echo "Error al registrar categoría";
        }
      } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
      }
    } else {
      echo "Error: Método no permitido";
    }
  }

  public function editarCategoria()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $codigo = $_POST['CodCategoria'] ?? null;
      $nombre = $_POST['NombreCategoria'] ?? null;

      if ($codigo === null || trim($codigo) === '') {
        echo "Error: El código de la categoría no puede estar vacío.";
        return;
      }

      if ($nombre === null || trim($nombre) === '') {
        echo "Error: El nombre de la categoría no puede estar vacío.";
        return;
      }

      try {
        $categoriaModel = new CategoriaModel($codigo, $nombre);
        $categoriaModel->editarCategoria();
        echo "Categoría actualizada correctamente.";
      } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
      }
    } else {
      echo "Error: Método no permitido";
    }
  }


  public function eliminarCategoria()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $codigo = $_POST['CodCategoria'] ?? null;

      if ($codigo === null || trim($codigo) === '') {
        echo "Error: El código de la categoría no puede estar vacío.";
        return;
      }

      try {
        $this->categoriaModel->eliminarCategoria($codigo);
        header("Location: modulo-categoria.php");
        exit();
      } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
      }
    } else {
      echo "Error: Método no permitido";
    }
  }

  public function listarCategorias()
  {
    try {
      $categorias = $this->categoriaModel->listarCategorias();
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
    }
  }

  public function filtrarCategorias()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $termino = $_POST['terminoBusqueda'] ?? null;

      if ($termino === null || trim($termino) === '') {
        echo "Error: El término de búsqueda no puede estar vacío.";
        return;
      }

      try {
        $categorias = $this->categoriaModel->filtrarBusqueda($termino);
      } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
      }
    } else {
      echo "Error: Método no permitido";
    }
  }
}
