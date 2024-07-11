<?php
require_once '../config/conexion.php';


$db = (new Conexion())->getConexion();
$query = "SELECT TOP 1 * FROM CIERRE ORDER BY CIE_codigo DESC";

$stmt = $db->prepare($query);
$stmt->execute();
$resultado = $stmt->fetch();
if (!$resultado) {
    $resultado = array('CIE_codigo' => 1);
}
else {
    $resultado = array('CIE_codigo' => $resultado['CIE_codigo'] + 1);
}
header('Content-Type: application/json');
echo json_encode($resultado);