<?php
require_once 'app/Model/BienModel.php';

class BienController
{
  private $bienModel;

  public function __construct()
  {
    $this->bienModel = new BienModel();
  }

  // Metodo para registrar el tipo de bien
  public function registrarTipoBien()
  {
    if ($_SERVER["REQUEST_METHOD"] === 'POST') {
      $codigoIdentificador = $_POST['codigoIdentificador'] ?? null;
      $nombreBien = $_POST['nombreTipoBien'] ?? null;

      try {
        // Validar si la persona tiene un usuario
        if ($this->bienModel->validarBienExistente($codigoIdentificador)) {
          echo json_encode([
            'success' => false,
            'message' => 'El codigo identificador ingresado ya está registrado.'
          ]);
          exit();
        }

        // Insertar tipo de bien
        $insertSuccess = $this->bienModel->insertarTipoBien($codigoIdentificador, $nombreBien);

        if ($insertSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Tipo de bien registrado exitosamente.',
            'BIE_codigo' => $insertSuccess
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'Error al registrar el tipo de bien'
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

  // Metodo para actualizar el tipo de bien
  public function actualizarTipoBien()
  {
    header('Content-Type: application/json'); // Asegúrate de tener este encabezado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $codigoIdentificador = $_POST['codigoIdentificador'] ?? null;
      $nombreBien = $_POST['nombreTipoBien'] ?? null;
      $codigoBien = $_POST['codBien'] ?? null;
      try {
        // Llamar al metodo para actualizar el tipo de bien
        $updateSuccess = $this->bienModel->editarTipoBien($codigoIdentificador, $nombreBien, $codigoBien);

        if ($updateSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Tipo de bien actualizado.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se realizó ninguna actualización.'
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
    exit();
  }
}
