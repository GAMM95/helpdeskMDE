<?php

require_once 'app/Model/AreaModel.php';

class AreaController
{
  private $areaModel;

  public function __construct()
  {
    $this->areaModel = new AreaModel();
  }

  // Metodo para registrar Areas
  public function registrarArea()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $nombreArea = $_POST['nombreArea'] ?? null;

      try {
        // Validar si eciste una area que ya esta registrada
        if ($this->areaModel->validarAreaExistente($nombreArea)) {
          echo json_encode([
            'success' => false,
            'message' => 'El área ingresada ya esta registrado.'
          ]);
          exit();
        }

        // Registrar a la area
        $insertSuccessId = $this->areaModel->insertarArea($nombreArea);

        if ($insertSuccessId) {
          echo json_encode([
            'success' => true,
            'message' => 'Área registrada.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'Error al registrar area.'
          ]);
        }
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
      exit();
    } else {
      echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
      ]);
    }
  }

  // Metodo para editar Areas
  public function actualizarArea()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $codigoArea = $_POST['codArea'] ?? null;
      $nombreArea = $_POST['nombreArea'] ?? null;

      try {
        $updateSuccess = $this->areaModel->editarArea($nombreArea, $codigoArea);
        if ($updateSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Datos actualizados.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se realizaron cambios.'
          ]);
        }
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
      exit();
    } else {
      echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
      ]);
    }
  }

  // Metodo para filtrar areas por un termino
  public function filtrarAreas()
  {
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
      $terminoBusqueda = $_GET['termino'] ?? '';

      try {
        $resultados = $this->areaModel->filtrarAreas($terminoBusqueda);
        if ($resultados) {
          echo json_encode([
            'success' =>  true,
            'message' => 'Busqueda exitosa'
          ]);
        } else {
          echo json_encode([
            'success' =>  false,
            'message' => 'No se realizo busqueda'
          ]);
        }
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
    } else {
      echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
      ]);
    }
  }
}
