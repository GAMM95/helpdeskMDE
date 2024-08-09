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
  // public function guardarUsuario($username, $password, $per_codigo, $rol_codigo, $are_codigo)
  // {
  //   $conector = parent::getConexion();
  //   try {
  //     if ($conector != null) {
  //       $query = "EXEC SP_Registrar_Usuario :USU_nombre, :USU_password, :PER_codigo, :ROL_codigo, :ARE_codigo";
  //       $stmt = $conector->prepare($query);
  //       $stmt->bindParam(':USU_nombre', $username);
  //       $stmt->bindParam(':USU_password', $password);
  //       $stmt->bindParam(':PER_codigo', $per_codigo);
  //       $stmt->bindParam(':ROL_codigo', $rol_codigo);
  //       $stmt->bindParam(':ARE_codigo', $are_codigo);
  //       $stmt->execute();
  //       return true; // Registro exitoso
  //     } else {
  //       throw new Exception("Error de conexión a la base de datos.");
  //     }
  //   } catch (PDOException $e) {
  //     throw new Exception("Error al guardar usuario: " . $e->getMessage());
  //   }
  // }
  // Método para registrar un nuevo usuario
  public function guardarUsuario($username, $password, $per_codigo, $rol_codigo, $are_codigo)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        // Verificar si la persona ya tiene un usuario registrado
        $verificarPersona = "SELECT 1 FROM USUARIO WHERE PER_codigo = :PER_codigo";
        $stmtPersona = $conector->prepare($verificarPersona);
        $stmtPersona->bindParam(':PER_codigo', $per_codigo);
        $stmtPersona->execute();
        if ($stmtPersona->fetch()) {
          throw new Exception("La persona ya tiene un usuario registrado.");
        }

        // Verificar si el nombre de usuario ya existe
        $verificarUsuario = "SELECT 1 FROM USUARIO WHERE USU_nombre = :USU_nombre";
        $stmtUsuario = $conector->prepare($verificarUsuario);
        $stmtUsuario->bindParam(':USU_nombre', $username);
        $stmtUsuario->execute();
        if ($stmtUsuario->fetch()) {
          throw new Exception("El nombre de usuario ya existe.");
        }

        // Insertar el nuevo usuario
        $query = "EXEC SP_Registrar_Usuario :USU_nombre, :USU_password, :PER_codigo, :ROL_codigo, :ARE_codigo";
        $stmt = $conector->prepare($query);
        $stmt->bindParam(':USU_nombre', $username);
        $stmt->bindParam(':USU_password', $password);
        $stmt->bindParam(':PER_codigo', $per_codigo);
        $stmt->bindParam(':ROL_codigo', $rol_codigo);
        $stmt->bindParam(':ARE_codigo', $are_codigo);
        $stmt->execute();
        return true; // Registro exitoso
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al guardar usuario: " . $e->getMessage());
    }
  }

  // TODO: Metodo para actualizar datos del usuario
  public function actualizarUsuario($username, $password, $per_codigo, $rol_codigo, $are_codigo)
  {
    $conector = parent::getConexion();
    try {
      $sql = "UPDATE USUARIO SET USU_nombre = ?, USU_password = ?, PER_codigo = ?, ROL_codigo = ?, ARE_codigo = ? WHERE USU_codigo = ?";
      $stmt = $conector->prepare($sql);
      $stmt->execute([$username, $password, $per_codigo, $rol_codigo, $are_codigo]);
      return $stmt->rowCount();
    } catch (PDOException $e) {
      throw new Exception("Error al actualizar usuario: " . $e->getMessage());
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
            USU_nombre, USU_password, PER_dni, PER_nombres, PER_apellidoPaterno, PER_apellidoMaterno,
            (PER_nombres +' '+ PER_apellidoPaterno +' '+ PER_apellidoMaterno) AS Persona,
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

  // TODO: Metodo para obtener usuario por ID
  public function obtenerUsuarioPorID($codigoUsuario)
  {
    $conector = parent::getConexion();
    try {
      $sql = "SELECT * FROM USUARIO WHERE USU_codigo = ?";
      $stmt = $conector->prepare($sql);
      $stmt->execute([$codigoUsuario]);
      $registros = $stmt->fetch(PDO::FETCH_ASSOC);
      return $registros;
    } catch (PDOException $e) {
      throw new Exception("Error al obtener usuario: " . $e->getMessage());
    }
  }

  public function editarPerfilUsuario($usu_codigo, $usu_nombre, $usu_password, $per_dni, $per_nombres, $per_apellidoPaterno, $per_apellidoMaterno, $per_celular, $per_email)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        // Llamada al procedimiento almacenado para actualizar persona y usuario
        $query = "EXEC EditarPersonaYUsuario 
                      @USU_codigo = :usu_codigo,
                      @USU_nombre = :usu_nombre,
                      @USU_password = :usu_password,
                      @PER_dni = :per_dni,
                      @PER_nombres = :per_nombres,
                      @PER_apellidoPaterno = :per_apellidoPaterno,
                      @PER_apellidoMaterno = :per_apellidoMaterno,
                      @PER_celular = :per_celular,
                      @PER_email = :per_email";
        $stmt = $conector->prepare($query);
        $stmt->bindParam(':usu_codigo', $usu_codigo);
        $stmt->bindParam(':usu_nombre', $usu_nombre);
        $stmt->bindParam(':usu_password', $usu_password);
        $stmt->bindParam(':per_dni', $per_dni);
        $stmt->bindParam(':per_nombres', $per_nombres);
        $stmt->bindParam(':per_apellidoPaterno', $per_apellidoPaterno);
        $stmt->bindParam(':per_apellidoMaterno', $per_apellidoMaterno);
        $stmt->bindParam(':per_celular', $per_celular);
        $stmt->bindParam(':per_email', $per_email);
        $stmt->execute();

        // Si la actualización fue exitosa
        return true;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al actualizar el perfil del usuario: " . $e->getMessage());
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
