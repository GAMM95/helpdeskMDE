<?php
require_once 'config/conexion.php';

class UsuarioModel extends Conexion
{
  protected $username;
  protected $password;

  public function __construct($username = null, $password = null)
  {
    parent::__construct();
    $this->username = $username;
    $this->password = $password;
  }

  // TODO: Metodo para iniciar sesion
  public function iniciarSesion()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $query = "EXEC SP_Usuario_login :username, :password";
        $stmt = $conector->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->execute();

        $resultado = $stmt->fetch();

        if ($resultado) {
          if ($resultado['EST_codigo'] == 1) { // Verificar si el usuario está activo
            session_start();
            $_SESSION['nombreDePersona'] = $resultado['PER_nombres'] . ' ' . $resultado['PER_apellidoPaterno'];
            $_SESSION['area'] = $resultado['ARE_nombre'];
            $_SESSION['codigoArea'] = $resultado['ARE_codigo'];
            $informacionUsuario = $this->obtenerInformacionUsuario($this->username, $this->password);
            $codigo = $informacionUsuario['codigo'];
            $usuario = $informacionUsuario['usuario'];
            $_SESSION['codigoUsuario'] = $codigo;
            $_SESSION['usuario'] = $usuario;
            $_SESSION['rol'] = $this->obtenerRolPorId($this->username); // Guardar rol en la sesión

            // Log de inicio de sesión
            $logData = "------- START LOGIN LOGS ---------" . PHP_EOL;
            $logData .=
              "Nombre de Persona: " . $_SESSION['nombreDePersona'] .
              ", Rol: " .  $_SESSION['rol'] .
              ", Codigo Area: " . $_SESSION['codigoArea'] .
              ", Área: " . $_SESSION['area'] .
              ", Código de Usuario: " . $codigo .
              ", Usuario: " . $usuario . PHP_EOL;
            file_put_contents('logs/log.txt', $logData, FILE_APPEND);
            return true;
          } else {
            // Redirigir con mensaje de error si el usuario está inactivo
            header("Location: index.php?state=inactive");
            exit();
          }
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al iniciar sesión: " . $e->getMessage());
    }
  }

  // TODO: Metodo para obtener la informacion del usuario logueado
  private function obtenerInformacionUsuario($username, $password)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $consulta = "SELECT USU_codigo as codigo, USU_nombre as usuario 
        FROM USUARIO u 
        WHERE USU_nombre = :username AND USU_password = :password";
        $stmt = $conector->prepare($consulta);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        $fila = $stmt->fetch();

        if ($fila) {
          return $fila;
        } else {
          return null;
        }
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener información del usuario: " . $e->getMessage());
    }
  }

  // TODO: Metodo para obtener el id del usuario logueado
  public function obtenerRolPorId($username)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $consulta = "SELECT ROL_nombre as rol
          FROM USUARIO u
          INNER JOIN ROL r ON r.ROL_codigo = u.ROL_codigo 
          WHERE USU_nombre = :username";
        $stmt = $conector->prepare($consulta);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $fila = $stmt->fetch();

        if ($fila) {
          return $fila['rol'];
        } else {
          return null;
        }
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener el rol del usuario: " . $e->getMessage());
    }
  }

  // TODO: Método para registrar un nuevo usuario
  public function registrarUsuario($per_codigo, $rol_codigo, $are_codigo)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $query = "EXEC SP_Registrar_Usuario :USU_nombre, :USU_password, :PER_codigo, :ROL_codigo, :ARE_codigo";
        $stmt = $conector->prepare($query);
        $stmt->bindParam(':USU_nombre', $this->username);
        $stmt->bindParam(':USU_password', $this->password);
        $stmt->bindParam(':PER_codigo', $per_codigo);
        $stmt->bindParam(':ROL_codigo', $rol_codigo);
        $stmt->bindParam(':ARE_codigo', $are_codigo);
        $stmt->execute();
        return true; // Registro exitoso
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al registrar usuario: " . $e->getMessage());
    }
  }

  // TODO: Metodo para listar todos los usuarios registrados
  public function listarUsuarios($start, $limit)
  {
    try {
      $conector = parent::getConexion();
      if ($conector != null) {
        $sql = "SELECT USU_codigo, (p.PER_nombres + ' ' + p.PER_apellidoPaterno + ' '+ p.PER_apellidoMaterno) as persona, 
        a.ARE_nombre , USU_nombre, USU_password, r.ROL_nombre, e.EST_descripcion 
        FROM USUARIO u
        INNER JOIN PERSONA p on p.PER_codigo = u.PER_codigo
        INNER JOIN AREA a on a.ARE_codigo = u.ARE_codigo
        INNER JOIN ESTADO e on e.EST_codigo = u.EST_codigo
        INNER JOIN ROL r ON r.ROL_codigo = u.ROL_codigo
        ORDER BY USU_codigo DESC
        OFFSET :start ROWS
        FETCH NEXT :limit ROWS ONLY";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception('Error de conexion en la base de datos');
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar usuarios: " . $e->getMessage());
    }
  }

  // TODO: Contar cantidad de usuarios para la pantalla de inicio del administrador y empaginar tabla Usuarios
  public function contarUsuarios()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as usuarios_total FROM USUARIO";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['usuarios_total'];
      } else {
        throw new Exception('Error de conexion en la base de datos');
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar usuarios: " . $e->getMessage());
    }
  }

  // TODO: Metodo para setear datos personales del usuario logueado
  public function setearDatosUsuario($user_id)
  {
    $conector = parent::getConexion();
    $sql = "SELECT 
            USU_nombre, (PER_nombres +' '+PER_apellidoPaterno +' '+ PER_apellidoMaterno) AS Persona,
            ROL_nombre, ARE_nombre, PER_celular, PER_email
            FROM USUARIO u
            INNER JOIN PERSONA p ON p.PER_codigo = u.PER_codigo
            INNER JOIN ROL r ON r.ROL_codigo = u.ROL_codigo
            INNER JOIN AREA a ON a.ARE_codigo = u.ARE_codigo
            WHERE u.USU_codigo = :user_id";
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    try {
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      return $user;
    } catch (PDOException $e) {
      echo "Error al setear datos personales del usuario: " . $e->getMessage();
      return null;
    }
  }


  // Método para habilitar usuarios
  public function habilitarUsuario($USU_codigo)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "UPDATE USUARIO SET EST_codigo = 2 
        WHERE USU_codigo = :USU_codigo AND EST_codigo = 1";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':USU_codigo', $USU_codigo, PDO::PARAM_INT);
        $stmt->execute();
      }
    } catch (PDOException $e) {
      throw new Exception("Error al habilitar usuario: " . $e->getMessage());
    }
  }

  // METODO PARA DESHABILITAR USUARIO
  public function deshabilitarUsuario($USU_codigo)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "UPDATE USUARIO SET EST_codigo = 1
        WHERE USU_codigo = :USU_codigo AND EST_codigo = 2";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':USU_codigo', $USU_codigo, PDO::PARAM_INT);
        $stmt->execute();
      }
    } catch (PDOException $e) {
      throw new Exception("Error al habilitar usuario: " . $e->getMessage());
    }
  }
}
