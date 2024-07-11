<?php
require 'app/Model/CierreModel.php';
class cierreController
{
  private $cierreModel;

  public function __construct()
  {
    $this->cierreModel = new CierreModel();
  }

  //TODO: Metodo controller para registrar cierre
  public function registrarCierre()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $fecha = $_POST['fecha_cierre'] ?? null;
      $hora = $_POST['hora'] ?? null;
      $diagnostico = $_POST['diagnostico'] ?? null;
      $documento = $_POST['documento'] ?? null;
      $asunto = $_POST['asunto'] ?? null;
      $solucion = $_POST['solucion'] ?? null;
      $recomendaciones = $_POST['recomendaciones'] ?? null;
      $operatividad = $_POST['operatividad'] ?? null;
      $recepcion = $_POST['recepcion'] ?? null;
      $usuario = $_POST['usuario'] ?? null;

      // Verificar que la fecha no es nula
      if ($fecha === null || $fecha === '') {
        echo "Error: La fecha es un campo obligatorio.";
        return;
      }
      
      // Llamar al método del modelo para insertar el cierre en la base de datos
      $insertSuccess = $this->cierreModel->insertarCierre(
        $fecha,
        $hora,
        $diagnostico,
        $documento,
        $asunto,
        $solucion,
        $recomendaciones,
        $operatividad,
        $recepcion,
        $usuario
      );
      if ($insertSuccess) {
        header('Location: registro-cierre-admin.php?CIE_numero=' . $insertSuccess);
      } else {
        echo "Error al registrar cierre.";
      }
    } else {
      echo "Error: Método no permitido.";
    }
  }
}
