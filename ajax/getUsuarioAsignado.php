<?php
require_once '../config/conexion.php';

class UsuarioAsignado extends Conexion
{

  public function __construct()
  {
    parent::__construct();
  }

  // Método para obtener datos de los usuarios asignados
  public function getUsuarioAsignado()
  {
    try {
      $conector = parent::getConexion();
      $sql = "SELECT USU_codigo,
            (PER_nombres + ' ' + PER_apellidoPaterno) AS usuarioAsignado
            FROM USUARIO u
            INNER JOIN PERSONA p ON p.PER_codigo = u.PER_codigo
            WHERE ARE_codigo = 1 AND EST_codigo = 1";
      $stmt = $conector->prepare($sql);
      $stmt->execute();
      $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $usuarios;
    } catch (PDOException $e) {

      error_log('Error en getUsuariosAsignados: ' . $e->getMessage());
      return [];
    }
  }
}

// Instanciar el modelo y obtener datos de categorías
$usuariosAsignados = new UsuarioAsignado();
$usuarios = $usuariosAsignados->getUsuarioAsignado();

// Devolver datos como JSON
header('Content-Type: application/json');
echo json_encode($usuarios);
