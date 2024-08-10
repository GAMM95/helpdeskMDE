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

  // TODO: Metodo de controlador para registrar incidencias - USUARIO
  public function registrarIncidenciaUsuario()
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
      $area = $_POST['codigoArea'] ?? null;
      $usuario = $_POST['codigoUsuario'] ?? null;

      // Llamar al método del modelo para insertar la incidencia en la base de datos
      $insertSuccessId = $this->incidenciaModel->insertarIncidenciaUsuario(
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
        header('Location: registro-incidencia-user.php?INC_numero=' . $insertSuccessId);
      } else {
        echo "Error al registrar la incidencia.";
      }
    }
  }

  // public function consultarIncidenciaUsuario()
  // {
  //   if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  //     // Obtener los valores de los parámetros GET o asignar null si no existen
  //     $area = $_GET['area'];
  //     $estado = $_GET['estado'];
  //     $fechaInicio = $_GET['fechaInicio'];
  //     $fechaFin = $_GET['fechaFin'];

  //     // Llamar al método para consultar incidencias por área, estado y fecha
  //     $consultaIncidencia = $this->incidenciaModel->buscarIncidenciaAdministrador($area, $estado, $fechaInicio, $fechaFin);

  //     // Retornar el resultado de la consulta
  //     return $consultaIncidencia;
  //   }
  // }

  public function consultarIncidenciaAdministrador()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Obtener los valores de los parámetros GET o asignar null si no existen
      $area = isset($_GET['area']) ? (int) $_GET['area'] : null;
      $estado = isset($_GET['estado']) ? (int) $_GET['estado'] : null;
      $fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : null;
      $fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : null;
      error_log("Área: $area, Estado: $estado, Fecha Inicio: $fechaInicio, Fecha Fin: $fechaFin");

      // Llamar al método para consultar incidencias por área, estado y fecha
      $consultaIncidencia = $this->incidenciaModel->buscarIncidenciaAdministrador($area, $estado, $fechaInicio, $fechaFin);

      // Retornar el resultado de la consulta
      return $consultaIncidencia;
    }
  }
}
