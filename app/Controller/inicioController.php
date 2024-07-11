<?php
class InicioController extends Conexion
{
  private $incidenciasModel;
  private $recepcionModel;
  private $cierreModel;

  public function __construct()
  {
    $this->incidenciasModel = new IncidenciaModel();
    $this->recepcionModel = new RecepcionModel();
    $this->cierreModel = new CierreModel();
  }

  public function mostrarCantidadesAdministrador()
  {
    try {
      $cantidadIncidenciasMesActualAdmin = $this->incidenciasModel->contarIncidenciasUltimoMesAdministrador();
      $cantidadPendientessMesActualAdmin = $this->incidenciasModel->contarPendientesUltimoMesAdministrador();
      $cantidadRecepcionesMesActualAdmin = $this->recepcionModel->contarRecepcionesUltimoMesAdministrador();
      $cantidadCierresMesActualAdmin = $this->cierreModel->contarCierresUltimoMesAdministrador();

      return [
        'incidencias_mes_actual' => $cantidadIncidenciasMesActualAdmin,
        'pendientes_mes_actual' => $cantidadPendientessMesActualAdmin,
        'recepciones_mes_actual' => $cantidadRecepcionesMesActualAdmin,
        'cierres_mes_actual' => $cantidadCierresMesActualAdmin
      ];
    } catch (Exception $e) {
      throw new Exception('Error al obtener las cantidades: ' . $e->getMessage());
    }
  }

  public function mostrarCantidadesUsuario($area)
  {
    try {
      $cantidadIncidenciasMesActual = $this->incidenciasModel->contarIncidenciasUltimoMesUsuario($area);
      $cantidadPendientesMesActual = $this->incidenciasModel->contarPendientesUltimoMesUsuario($area);
      $cantidadRecepcionesMesActual = $this->recepcionModel->contarRecepcionesUltimoMesUsuario($area);
      $cantidadCierresMesActual = $this->cierreModel->contarCierresUltimoMesUsuario($area);

      return [
        'incidencias_mes_actual' => $cantidadIncidenciasMesActual,
        'pendientes_mes_actual' => $cantidadPendientesMesActual,
        'recepciones_mes_actual' => $cantidadRecepcionesMesActual,
        'cierres_mes_actual' => $cantidadCierresMesActual
      ];
    } catch (Exception $e) {
      throw new Exception('Error al obtener las cantidades: ' . $e->getMessage());
    }
  }
}
