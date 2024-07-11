<?php
require_once '../config/conexion.php';

class OperatividadModel extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  //TODO: Metodo para cargar operatividad
  public function getOperatividadData()
  {
    $conector = parent::getConexion();
    $query = "SELECT * FROM OPERATIVIDAD 
      ORDER BY OPE_codigo";
    $stmt = $conector->prepare($query);
    $stmt->execute();
    $resultado = $stmt->fetchAll(); 
    return $resultado;
  }
}

$operatividadModel = new OperatividadModel();
$operatividades = $operatividadModel->getOperatividadData();

header('Content-Type: application/json');
echo json_encode($operatividades);
