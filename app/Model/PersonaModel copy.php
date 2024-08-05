<?php
// Importamos las credenciales y la clase de conexión
require_once 'config/conexion.php';

class PersonaModel extends Conexion
{
  protected $codigoPersona;
  protected $dni;
  protected $nombres;
  protected $apellidoPaterno;
  protected $apellidoMaterno;
  protected $email;
  protected $celular;

  public function __construct(
    $codigoPersona = null,
    $dni = null,
    $nombres = null,
    $apellidoPaterno = null,
    $apellidoMaterno = null,
    $email = null,
    $celular = null
  ) {
    parent::__construct();
    $this->codigoPersona = $codigoPersona;
    $this->dni = $dni;
    $this->nombres = $nombres;
    $this->apellidoPaterno = $apellidoPaterno;
    $this->apellidoMaterno = $apellidoMaterno;
    $this->email = $email;
    $this->celular = $celular;
  }

  // Método para listar personas
  public function listarPersona()
  {
    $conector = $this->getConexion();
    try {
      $sql = "SELECT * FROM PERSONA";
      $stmt = $conector->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener las personas: " . $e->getMessage());
    }
  }

  public function registrarPersona($dni, $nombres, $apellidoPaterno, $apellidoMaterno, $celular, $email)
  {
    $conector = parent::getConexion();
    try {
      $sql = "INSERT INTO PERSONA (PER_DNI, PER_nombres, PER_apellidoPaterno, 
                PER_apellidoMaterno, PER_celular, PER_email) VALUES (?, ?, ?, ?, ?, ?)";
      $stmt = $conector->prepare($sql);
      $stmt->execute([
        $dni,
        $nombres,
        $apellidoPaterno,
        $apellidoMaterno,
        $celular,
        $email
      ]);
      return $conector->lastInsertId();
    } catch (PDOException $e) {
      throw new Exception("Error al registrar nueva persona: " . $e->getMessage());
    }
  }

  // Método para obtener una persona por ID
  public function obtenerPersonaPorId($codigoPersona)
  {
    $conector = parent::getConexion();
    try {
      $sql = "SELECT * FROM PERSONA WHERE PER_codigo = ?";
      $stmt = $conector->prepare($sql);
      $stmt->execute([$codigoPersona]);
      $registros = $stmt->fetch(PDO::FETCH_ASSOC);
      return $registros;
    } catch (PDOException $e) {
      throw new Exception("Error al obtener la persona: " . $e->getMessage());
    }
  }

  // Método para actualizar datos de la persona
  public function actualizarPersona()
  {
    $conector = parent::getConexion();
    try {
      $sql = "UPDATE PERSONA SET PER_dni = ?, PER_nombres = ?, PER_apellidoPaterno = ?, 
                PER_apellidoMaterno = ?, PER_celular = ?, PER_email = ? WHERE PER_codigo = ?";
      $stmt = $conector->prepare($sql);
      $stmt->execute([
        $this->dni,
        $this->nombres,
        $this->apellidoPaterno,
        $this->apellidoMaterno,
        $this->celular,
        $this->email,
        $this->codigoPersona
      ]);
      return $stmt->rowCount();
    } catch (PDOException $e) {
      throw new Exception("Error al actualizar la persona: " . $e->getMessage());
    }
  }
}
