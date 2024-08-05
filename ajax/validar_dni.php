<?php
require_once 'app/Model/PersonaModel.php';

header('Content-Type: application/json');

if (isset($_POST['dni'])) {
  $dni = $_POST['dni'];

  // Crear una instancia del modelo y verificar el DNI
  $personaModel = new PersonaModel();
  $existe = $personaModel->validarDniExistente($dni);

  // Responder con un JSON
  echo json_encode(['existe' => $existe]);
  exit();
} else {
  // Si no se recibe DNI, responder con falso
  echo json_encode(['existe' => false]);
  exit();
}
