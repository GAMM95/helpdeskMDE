<?php
require_once 'config/conexion.php';

class BienModel extends Conexion
{
  // Atributos
  protected $codigoIdentificador;
  protected $nombreTipoBien;

  public function __construct(
    $codigoIdentificador = null,
    $nombreTipoBien = null
  ) {
    parent::__construct();
    $this->codigoIdentificador = $codigoIdentificador;
    $this->nombreTipoBien = $nombreTipoBien;
  }

  // Metodo para validar existencia de codigo de bien
  public function validarBienExistente()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        // Extraer los primeros 8 dígitos del código patrimonial
        $codigoParcial = substr($this->codigoIdentificador, 0, 8);
        $sql = "SELECT COUNT(*) FROM BIEN WHERE BIE_codigoPatrimonial = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([$codigoParcial]);
        // Obtener el conteo de coincidencias
        $count = $stmt->fetchColumn();
        return $count > 0;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al verificar el bien: " . $e->getMessage());
    }
  }

  // Metodo para insertar el tipo de bien
  public function insertarTipoBien()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "";
      } else {
        throw new Exception("Error de conexion a la base de datos");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al insertar tipo de bien: " . $e->getMessage());
    }
  }

  // Metodo para editar el tipo de bien
  public function editarTipoBien()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "";
      } else {
        throw new Exception("Error de conexion a la base de datos");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al editar el tipo de bien: " . $e->getMessage());
    }
  }
}
