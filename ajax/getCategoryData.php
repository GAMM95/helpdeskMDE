<?php
require_once '../config/conexion.php';

class CategoryModel extends Conexion
{

    public function __construct()
    {
        parent::__construct();
    }

    // Método para obtener datos de categorías
    public function getCategoryData()
    {
        try {
            $conector = parent::getConexion();
            $query = "SELECT * FROM CATEGORIA"; // Ajusta los nombres de las columnas según tu base de datos
            $stmt = $conector->prepare($query);
            $stmt->execute();
            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC); // Utiliza FETCH_ASSOC para obtener un array asociativo
            return $categorias;
        } catch (PDOException $e) {
            // Manejo de errores - Puedes registrar el error o devolver un mensaje de error genérico
            error_log('Error en getCategoryData: ' . $e->getMessage());
            return []; // Devolver un array vacío en caso de error
        }
    }
}

// Instanciar el modelo y obtener datos de categorías
$categoryModel = new CategoryModel();
$categories = $categoryModel->getCategoryData();

// Devolver datos como JSON
header('Content-Type: application/json');
echo json_encode($categories);
