<?php
require_once '../config/conexion.php';

class PersonaModel
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    public function getPersonaData()
    {
        $query = "SELECT PER_codigo, (PER_nombres + ' ' + PER_apellidoPaterno + ' ' + PER_apellidoMaterno) AS persona FROM PERSONA ORDER BY persona ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }
}

$personaModel = new PersonaModel();
$personas = $personaModel->getPersonaData();

header('Content-Type: application/json');
echo json_encode($personas);