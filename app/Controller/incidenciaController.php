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

  // TODO: Metodo de controlador para registrar incidencias - ADMINISTRADOR
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
      $insertSuccessId = $this->incidenciaModel->insertarIncidenciaAdministrador($fecha, $hora, $asunto, $descripcion, $documento, $codigoPatrimonial, 3, $categoria, $area, $usuario);

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
      $insertSuccessId = $this->incidenciaModel->insertarIncidenciaUsuario($fecha, $hora, $asunto, $descripcion, $documento, $codigoPatrimonial, 3, $categoria, $area, $usuario);

      if ($insertSuccessId) {
        header('Location: registro-incidencia-user.php?INC_numero=' . $insertSuccessId);
      } else {
        echo "Error al registrar la incidencia.";
      }
    }
  }

  public function actualizarIncidenciaAdministrador()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      // Obtener y validar los parámetros
      $numeroIncidencia = $_POST['numero_incidencia'] ?? null;
      $categoria = $_POST['categoria'] ?? null;
      $area = $_POST['area'] ?? null;
      $codigoPatrimonial = $_POST['codigo_patrimonial'] ?? null;
      $asunto = $_POST['asunto'] ?? null;
      $documento = $_POST['documento'] ?? null;
      $descripcion = $_POST['descripcion'] ?? null;

      header('Content-Type: application/json');
      try {

        if (is_null($asunto) || is_null($documento)) {
          // Respuesta en caso de parámetros faltantes
          echo json_encode([
            'success' => false,
            'message' => 'Faltan parámetros requeridos.'
          ]);
          exit();
        }

        // Verificar el estado de la incidencia
        $estado = $this->incidenciaModel->obtenerEstadoIncidencia($numeroIncidencia);

        if ($estado === 3) {
          // Estado permitido para actualización
          echo json_encode([
            'success' => false,
            'message' => 'La incidencia no está Abierta y no puede ser actualizada.'
          ]);
          exit();
        }

        // Llamar al modelo para actualizar la incidencia
        $updateSuccess = $this->incidenciaModel->editarIncidenciaAdmin($numeroIncidencia, $categoria, $area, $codigoPatrimonial, $asunto, $documento, $descripcion);

        if ($updateSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Incidencia actualizada.'
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
    }
  }


  // TODO: Metodo para consultar incidencias para el administrador
  public function consultarIncidenciaAdministrador($area = NULL, $estado = null, $fechaInicio = null, $fechaFin = null)
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

  // TODO: Metodo para consultar incidencias  totales para el administrador
  public function consultarIncidenciasTotales($area = NULL, $codigoPatrimonial = null, $fechaInicio = null, $fechaFin = null)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Obtener los valores de los parámetros GET o asignar null si no existen
      $area = isset($_GET['area']) ? (int) $_GET['area'] : null;
      $codigoPatrimonial = isset($_GET['codigoPatrimonial']) ? (int) $_GET['codigoPatrimonial'] : null;
      $fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : null;
      $fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : null;
      error_log("Área: $area, Codigo patrimonial: $codigoPatrimonial, Fecha Inicio: $fechaInicio, Fecha Fin: $fechaFin");

      // Llamar al método para consultar incidencias por área, estado y fecha
      $consultaIncidencia = $this->incidenciaModel->buscarIncidenciaTotales($area, $codigoPatrimonial, $fechaInicio, $fechaFin);

      // Retornar el resultado de la consulta
      return $consultaIncidencia;
    }
  }

  // TODO: Metodo para consultar incidencias para el usuario
  public function consultarIncidenciaUsuario($area = NULL, $estado = null, $fechaInicio = null, $fechaFin = null)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Obtener los valores de los parámetros GET o asignar null si no existen
      $area = isset($_GET['codigoArea']) ? (int) $_GET['codigoArea'] : null;
      $codigoPatrimonial = isset($_GET['codigoPatrimonial']) ? $_GET['codigoPatrimonial'] : null;
      $estado = isset($_GET['estado']) ? (int) $_GET['estado'] : null;
      $fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : null;
      $fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : null;
      error_log("Área: $area, Estado: $estado, Fecha Inicio: $fechaInicio, Fecha Fin: $fechaFin");

      // Llamar al método para consultar incidencias por área, estado y fecha
      $consultaIncidencia = $this->incidenciaModel->buscarIncidenciaUsuario($area,  $codigoPatrimonial, $estado, $fechaInicio, $fechaFin);

      // Retornar el resultado de la consulta
      return $consultaIncidencia;
    }
  }
}
