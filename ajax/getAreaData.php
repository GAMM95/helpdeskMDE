<?php
require_once '../config/conexion.php';

class AreaModel
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    public function getAreaData()
    {
        $query = "SELECT * FROM AREA";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        return $resultado;
    }
}

$areaModel = new AreaModel();
$areas = $areaModel->getAreaData();

header('Content-Type: application/json');
echo json_encode($areas);