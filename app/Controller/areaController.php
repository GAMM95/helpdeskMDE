<?php

require_once 'app/Model/AreaModel.php';

class AreaController  
{
  private $areaModel;

  public function __construct()
  {
    $this->areaModel = new AreaModel();
  }

  public function registrarArea()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $nombre = $_POST['NombreArea'] ?? null;

      if ($nombre === null || trim($nombre) === '') {
        echo "Error: El nombre del área no puede estar vacío.";
        return;
      }

      try {
        $areaModel = new AreaModel(null, $nombre);
        $insertSuccessId = $areaModel->registrarArea();
        if ($insertSuccessId) {
          header('Location: modulo-area.php?CodArea=' . $insertSuccessId);
          exit();
        } else {
          echo "Error al registrar área";
        }
      } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
      }
    } else {
      echo "Error: Método no permitido";
    }
  }

  public function editarArea()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $codigo = $_POST['CodArea'] ?? null;
      $nombre = $_POST['NombreArea'] ?? null;

      if ($codigo === null || trim($codigo) === '') {
        echo "Error: El código del área no puede estar vacío.";
        return;
      }

      if ($nombre === null || trim($nombre) === '') {
        echo "Error: El nombre del área no puede estar vacío.";
        return;
      }

      try {
        $areaModel = new AreaModel($codigo, $nombre);
        $areaModel->editarArea();
        echo "Área actualizada correctamente.";
      } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
      }
    } else {
      echo "Error: Método no permitido";
    }
  }
}
