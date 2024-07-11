<?php
// Importar el modelo IncidenciaModel.php
require 'app/Model/IncidenciaModel.php';

class IncidenciaController
{
  private $incidenciaModel;
  public function __construct()
  {
    $this->incidenciaModel = new IncidenciaModel();
  }

  // TODO: Metodo de controlador para registrar incidencias
  public function registrarIncidencia()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener los datos del formulario
      $fecha = $_POST['fecha_incidencia'] ?? null;
      $hora = $_POST['hora'] ?? null;
      $asunto =  $_POST['asunto'] ?? null;
      $descripcion = $_POST['descripcion'] ?? null;
      $documento = $_POST['documento'] ?? null;
      $codigoPatrimonial = $_POST['codigo_patrimonial'] ?? null;
      $categoria = $_POST['categoria'] ?? null;
      $area = $_POST['area'] ?? null;
      $usuario = $_POST['usuario'] ?? null;

      // Llamar al método del modelo para insertar la incidencia en la base de datos
      $insertSuccessId = $this->incidenciaModel->insertarIncidenciaAdministrador(
        $fecha,
        $hora,
        $asunto,
        $descripcion,
        $documento,
        $codigoPatrimonial,
        3,
        $categoria,
        $area,
        $usuario
      );

      if ($insertSuccessId) {
        header('Location: registro-incidencia-admin.php?INC_numero=' . $insertSuccessId);
      } else {
        echo "Error al registrar la incidencia.";
      }
    }
  }

  public function consultarIncidenciaAdministrador()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $area = $_GET['area'] ?? null;
      $codigoPatrimonial = $_GET['codigoPatrimonial'] ?? null;
      $fechaInicio = $_GET['fechaInicio'] ?? null;
      $fechaFin = $_GET['fechaFin'] ?? null;

      // Llamar al método para consultar incidencias por área y fecha
      $consultaIncidenciaAreaFecha = $this->incidenciaModel->buscarIncidenciaAdministrador($area, $codigoPatrimonial, $fechaInicio, $fechaFin);

      // Retornar el resultado de la consulta
      return $consultaIncidenciaAreaFecha;
    }
  }
}
