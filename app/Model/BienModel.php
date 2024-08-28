<?php
// importacion de la clase conexion
require_once 'config/conexion.php';

class BienModel extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  // Metodo para validar existencia de codigo de bien
  public function validarBienExistente($codigoPatrimonial)
  {
    $conector = parent::getConexion();
    try {
      // Extraer los primeros 8 dígitos del código patrimonial
      $codigoParcial = substr($codigoPatrimonial, 0, 8);
      $sql = "SELECT COUNT(*) FROM BIEN WHERE BIE_codigoPatrimonial = ?";
      $stmt = $conector->prepare($sql);
      $stmt->execute([$codigoParcial]);

      // Obtener el conteo de coincidencias
      $count = $stmt->fetchColumn();

      // Retornar verdadero si existe al menos un registro, falso en caso contrario
      return $count > 0;
    } catch (PDOException $e) {
      throw new Exception("Error al verificar el bien: " . $e->getMessage());
    }
  }

  // Metodo para insertar el tipo de bien
  public function insertarTipoBien()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
      }
    } catch (PDOException $e) {
      throw new Exception("Error al insertar tipo de bien: " . $e->getMessage());
    }
  }
}
