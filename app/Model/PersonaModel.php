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



  // Método para validar la existencia de un DNI
  public function validarDniExistente($dni)
  {
    $conector = parent::getConexion();
    try {
      $sql = "SELECT COUNT(*) FROM PERSONA WHERE PER_dni = ?";
      $stmt = $conector->prepare($sql);
      $stmt->execute([$dni]);
      $count = $stmt->fetchColumn();
      return $count > 0;
    } catch (PDOException $e) {
      throw new Exception("Error al verificar el DNI: " . $e->getMessage());
    }
  }

  // TODO: Método para registrar nueva persona
  public function registrarPersona($dni, $nombres, $apellidoPaterno, $apellidoMaterno, $celular, $email)
  {
    $conector = parent::getConexion();
    try {
      // Primero validamos la existencia del DNI
      if ($this->validarDniExistente($dni)) {
        throw new Exception("El DNI ya está registrado.");
      }

      // Si el DNI no existe, procedemos a registrar la nueva persona
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
    } catch (Exception $e) {
      throw new Exception("Error al registrar nueva persona: " . $e->getMessage());
    }
  }

  // TODO: Método para obtener una persona por ID
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

  // TODO: Método para actualizar datos de la persona
  public function actualizarPersona($dni, $nombres, $apellidoPaterno, $apellidoMaterno, $celular, $email, $codigoPersona)
  {
    $conector = parent::getConexion();
    try {
      $sql = "UPDATE PERSONA SET PER_dni = ?, PER_nombres = ?, PER_apellidoPaterno = ?, 
              PER_apellidoMaterno = ?, PER_celular = ?, PER_email = ? WHERE PER_codigo = ?";
      $stmt = $conector->prepare($sql);
      $stmt->execute([
        $dni,
        $nombres,
        $apellidoPaterno,
        $apellidoMaterno,
        $celular,
        $email,
        $codigoPersona
      ]);
      return $stmt->rowCount();
    } catch (PDOException $e) {
      throw new Exception("Error al actualizar la persona: " . $e->getMessage());
    }
  }

  // TODO: Metodo para obtener la lista de personas
  public function listarTrabajadores($start, $limit)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT PER_codigo, PER_dni,
                (PER_nombres + ' ' + PER_apellidoPaterno + ' ' + PER_apellidoMaterno) AS persona,
                PER_celular, PER_email
                FROM PERSONA
                ORDER BY PER_codigo DESC
                OFFSET ? ROWS
                FETCH NEXT ? ROWS ONLY";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(1, $start, PDO::PARAM_INT);
        $stmt->bindParam(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $registros;
      } else {
        echo "Error de conexion a la base de datos";
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener las personas: " . $e->getMessage());
      return null;
    }
  }

  // TODO: contar personas para filtrar tabla
  public function contarTrabajadores()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as total FROM PERSONA";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
      } else {
        echo "Error de conexion con la base de datos";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar trabajadores: " . $e->getMessage();
      return null;
    }
  }

  // TODO: Método para filtrar personas por término de búsqueda
  public function filtrarPersonas($terminoBusqueda)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT PER_codigo, PER_dni,
                (PER_nombres + ' ' + PER_apellidoPaterno + ' ' + PER_apellidoMaterno) AS persona,
                PER_celular, PER_email
                FROM PERSONA
                WHERE (PER_nombres + ' ' + PER_apellidoPaterno + ' ' + PER_apellidoMaterno) LIKE :terminoBusqueda
                OR PER_dni LIKE :terminoBusqueda
                OR PER_celular LIKE :terminoBusqueda
                OR PER_email LIKE :terminoBusqueda";
        $stmt = $conector->prepare($sql);
        $terminoBusqueda = "%$terminoBusqueda%";
        $stmt->bindParam(':terminoBusqueda', $terminoBusqueda, PDO::PARAM_STR);
        $stmt->execute();
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $registros;
      } else {
        echo "Error de conexion a la base de datos";
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al filtrar personas: " . $e->getMessage());
      return null;
    }
  }
}
