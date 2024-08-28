-- Empresa		 : Municipalidad Distrital de La Esperanza
-- Producto		 : Sistema de Gestion de Incidencias Informaticas
-- Software		 : Sistema de Incidencias Informaticas
-- DBMS			 : SQL Server
-- Base de datos : SISTEMA_INCIDENCIAS
-- Responsable	 : Subgerente de informatica y Sistemas - SGIS
--				   jhonatanmm.1995@gmail.com
-- Repositorio	 : https://github.com/GAMM95/helpdeskMDE/tree/main/config/sql
-- Creado por	 : Jhonatan Mantilla Miñano
--		         : 16 de mayo del 2024

-------------------------------------------------------------------------------------------------------
  -- CREACION DE LA BASE DE DATOS
-------------------------------------------------------------------------------------------------------
USE master;
GO

IF EXISTS (SELECT name FROM sys.databases WHERE name = 'SISTEMA_INCIDENCIAS')
BEGIN
    DROP DATABASE SISTEMA_INCIDENCIAS;
END
GO

CREATE DATABASE SISTEMA_INCIDENCIAS;
GO

USE SISTEMA_INCIDENCIAS;
GO


-------------------------------------------------------------------------------------------------------
  -- CREACION DE LAS TABLAS
-------------------------------------------------------------------------------------------------------
-- CREACION DE LA TABLA ROL
CREATE TABLE ROL (
	ROL_codigo SMALLINT IDENTITY(1, 1) PRIMARY KEY,
	ROL_nombre VARCHAR(20)
);
GO

-- CREACION DE LA TABLA PERSONA
CREATE TABLE PERSONA (
	PER_codigo SMALLINT IDENTITY(1, 1) PRIMARY KEY,
	PER_dni CHAR(8) UNIQUE NOT NULL,
	PER_nombres VARCHAR(20) NOT NULL,
	PER_apellidoPaterno VARCHAR(15) NOT NULL,
	PER_apellidoMaterno VARCHAR(15) NOT NULL,
	PER_celular CHAR(9) NULL,
	PER_email VARCHAR(45) NULL,
);
GO

-- CREACION DE LA TABLA AREA
CREATE TABLE AREA (
	ARE_codigo SMALLINT IDENTITY(1, 1) PRIMARY KEY,
	ARE_nombre VARCHAR(100) UNIQUE NOT NULL
);
GO

-- CREACION DE LA TABLA ESTADO
CREATE TABLE ESTADO (
	EST_codigo SMALLINT IDENTITY(1, 1) PRIMARY KEY,
	EST_descripcion VARCHAR(20)
);
GO

-- CREACION DE LA TABLA USUARIO
CREATE TABLE USUARIO (
	USU_codigo SMALLINT IDENTITY(1, 1) PRIMARY KEY,
	USU_nombre VARCHAR(20) UNIQUE NOT NULL,
	USU_password VARCHAR(10) NOT NULL,
	PER_codigo SMALLINT NOT NULL,
	ROL_codigo SMALLINT NOT NULL,
	ARE_codigo SMALLINT NOT NULL,
	EST_codigo SMALLINT NOT NULL,
	CONSTRAINT FK_USUARIO_PERSONA FOREIGN KEY (PER_codigo) REFERENCES PERSONA(PER_codigo),
	CONSTRAINT FK_USUARIO_ROL FOREIGN KEY (ROL_codigo) REFERENCES ROL(ROL_codigo),
	CONSTRAINT FK_USUARIO_AREA FOREIGN KEY (ARE_codigo) REFERENCES AREA(ARE_codigo),
	CONSTRAINT FK_USUARIO_ESTADO FOREIGN KEY (EST_codigo) REFERENCES ESTADO(EST_codigo)
);
GO

 -- CREACION DE LA TABLA PRIORIDAD
CREATE TABLE PRIORIDAD(
	PRI_codigo SMALLINT IDENTITY(1, 1),
	PRI_nombre VARCHAR(15) NOT NULL,
	CONSTRAINT pk_PRI_codigo PRIMARY KEY(PRI_codigo),
	CONSTRAINT uq_PRI_descripcion UNIQUE (PRI_nombre)
);
GO

-- CREACION DE LA TABLA CATEGORIA
CREATE TABLE CATEGORIA (
	CAT_codigo SMALLINT IDENTITY(1, 1),
	CAT_nombre VARCHAR(60) NOT NULL,
	CONSTRAINT pk_CAT_codigo PRIMARY KEY(CAT_codigo),
	CONSTRAINT uq_CAT_nombre UNIQUE (CAT_nombre)
);
GO

-- CREACION DE TABLA IMPACTO
CREATE TABLE IMPACTO (
	IMP_codigo SMALLINT IDENTITY(1, 1),
	IMP_descripcion VARCHAR(20) NOT NULL,
	CONSTRAINT pk_impacto PRIMARY KEY (IMP_codigo)
);
GO

-- CREACIÓN DE LA TABLA BIEN
CREATE TABLE BIEN (
    BIE_codigo SMALLINT IDENTITY(1,1),
    BIE_codigoPatrimonial VARCHAR(12) NULL,
    BIE_nombre VARCHAR(50) NULL,
    CONSTRAINT pk_bien PRIMARY KEY (BIE_codigo),
    CONSTRAINT uk_bie_codigoPatrimonial UNIQUE (BIE_codigoPatrimonial)  -- Único para asegurar la integridad
);
GO

-- CREACIÓN DE LA TABLA INCIDENCIA
CREATE TABLE INCIDENCIA (
    INC_numero SMALLINT NOT NULL,
    INC_numero_formato VARCHAR(20) NULL,
    INC_fecha DATE NOT NULL,
    INC_hora TIME NOT NULL,
    INC_asunto VARCHAR(200) NOT NULL,
    INC_descripcion VARCHAR(500) NULL,
    INC_documento VARCHAR(100) NOT NULL,
    INC_codigoPatrimonial CHAR(12) NULL, 
    EST_codigo SMALLINT NOT NULL,
    CAT_codigo SMALLINT NOT NULL,
    ARE_codigo SMALLINT NOT NULL,
    USU_codigo SMALLINT NOT NULL,
    CONSTRAINT pk_incidencia PRIMARY KEY (INC_numero),
    CONSTRAINT fk_estado_incidencia FOREIGN KEY (EST_codigo) REFERENCES ESTADO (EST_codigo),
    CONSTRAINT fk_categoria_incidencia FOREIGN KEY (CAT_codigo) REFERENCES CATEGORIA (CAT_codigo),
    CONSTRAINT fk_area_incidencia FOREIGN KEY (ARE_codigo) REFERENCES AREA (ARE_codigo),
    CONSTRAINT fk_usuario_incidencia FOREIGN KEY (USU_codigo) REFERENCES USUARIO (USU_codigo)
);
GO

-- CREACION DE TABLA RECEPCION
CREATE TABLE RECEPCION (
	REC_numero SMALLINT IDENTITY(1, 1),
	REC_fecha DATE NOT NULL,
	REC_hora TIME(7) NOT NULL,
	INC_numero SMALLINT NOT NULL,
	PRI_codigo SMALLINT NOT NULL,
	IMP_codigo SMALLINT NOT NULL,
	USU_codigo SMALLINT NOT NULL,
	EST_codigo SMALLINT NOT NULL,
	CONSTRAINT pk_recepcion PRIMARY KEY (REC_numero),
	CONSTRAINT fk_incidencia_recepcion FOREIGN KEY (INC_numero) REFERENCES INCIDENCIA (INC_numero),
	CONSTRAINT fk_prioridad_recepcion FOREIGN KEY (PRI_codigo) REFERENCES PRIORIDAD (PRI_codigo),
	CONSTRAINT fk_impacto_recepcion FOREIGN KEY (IMP_codigo) REFERENCES IMPACTO (IMP_codigo),
	CONSTRAINT fk_usuario_recepcion FOREIGN KEY (USU_codigo) REFERENCES USUARIO (USU_codigo),
	CONSTRAINT fk_estado_recepcion FOREIGN KEY (EST_codigo) REFERENCES ESTADO (EST_codigo),
);
GO

-- CREACION DE LA TABLA CONDICION
CREATE TABLE CONDICION (
	CON_codigo SMALLINT IDENTITY(1,1) NOT NULL,
	CON_descripcion VARCHAR(20) NOT NULL,
	CONSTRAINT pk_operatividad PRIMARY KEY (CON_codigo)
);
GO

-- CREACION DE LA TABLA CIERRE
CREATE TABLE CIERRE(
	CIE_numero SMALLINT IDENTITY(1,1) NOT NULL,
	CIE_fecha DATE NOT NULL,
	CIE_hora TIME NOT NULL,
	CIE_diagnostico VARCHAR(200) NULL,
	CIE_documento VARCHAR(500) NOT NULL,
	CIE_asunto VARCHAR(200) NOT NULL,
	CIE_recomendaciones VARCHAR(500) NULL,
	CON_codigo SMALLINT NOT NULL,
	EST_codigo SMALLINT NOT NULL,
	REC_numero SMALLINT NOT NULL,
	USU_codigo SMALLINT NOT NULL,
	CONSTRAINT pk_cierre PRIMARY KEY (CIE_numero),
	CONSTRAINT fk_condicion_cierre FOREIGN KEY (CON_codigo) 
	REFERENCES CONDICION (CON_codigo),
	CONSTRAINT fk_recepcion_cierre FOREIGN KEY (REC_numero) 
	REFERENCES RECEPCION (REC_numero),
	CONSTRAINT fk_codigo_cierre FOREIGN KEY (USU_codigo) 
	REFERENCES USUARIO (USU_codigo),
	CONSTRAINT fk_estado_cierre FOREIGN KEY (EST_codigo) 
	REFERENCES ESTADO (EST_codigo),
);
GO

-------------------------------------------------------------------------------------------------------
  -- VOLCADO DE DATOS PARA LAS TABLAS CREADAS
-------------------------------------------------------------------------------------------------------

-- VOLCADO DE DATOS PARA LA TABLA ROL
INSERT INTO ROL (ROL_nombre) VALUES ('Administrador');
INSERT INTO ROL (ROL_nombre) VALUES ('Soporte');
INSERT INTO ROL (ROL_nombre) VALUES ('Usuario');
GO

-- VOLCADO DE DATOS PARA LA TABLA PERSONA
INSERT INTO PERSONA (PER_dni, PER_nombres, PER_apellidoPaterno, PER_apellidoMaterno, PER_email, PER_celular)
VALUES ('70555743', 'Jhonatan', 'Mantilla', 'Miñano', 'jhonatanmm.1995@gmail.com', '950212909');
GO

-- VOLCADO DE DATOS PARA LA TABLA AREA
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE INFORMÁTICA Y SISTEMAS');
INSERT INTO AREA(ARE_nombre) VALUES ('GERENCIA MUNICIPAL');
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE CONTABILIDAD');
INSERT INTO AREA(ARE_nombre) VALUES ('ALCALDÍA');
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE TESORERÍA');
INSERT INTO AREA(ARE_nombre) VALUES ('SECCIÓN DE ALMACÉN');
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE ABASTECIMIENTO Y CONTROL PATRIMONIAL');
INSERT INTO AREA(ARE_nombre) VALUES ('UNIDAD DE CONTROL PATRIMONIAL');
INSERT INTO AREA(ARE_nombre) VALUES ('CAJA GENERAL');
INSERT INTO AREA(ARE_nombre) VALUES ('GERENCIA DE RECURSOS HUMANOS');
INSERT INTO AREA(ARE_nombre) VALUES ('GERENCIA DE DESARROLLO ECONÓMICO LOCAL');
INSERT INTO AREA(ARE_nombre) VALUES ('ÁREA DE LIQUIDACIÓN DE OBRAS');
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE HABILITACIONES URBANAS Y CATASTRO');
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE ESCALAFÓN');
INSERT INTO AREA(ARE_nombre) VALUES ('SECRETARÍA GENERAL');
INSERT INTO AREA(ARE_nombre) VALUES ('PROVALE - PROGRAMA DE VASO DE LECHE');
INSERT INTO AREA(ARE_nombre) VALUES ('DEMUNA');
INSERT INTO AREA(ARE_nombre) VALUES ('OMAPED');
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE SALUD');
INSERT INTO AREA(ARE_nombre) VALUES ('GERENCIA DE ADMINISTRACIÓN TRIBUTARIA');
INSERT INTO AREA(ARE_nombre) VALUES ('SERVICIO SOCIAL');
INSERT INTO AREA(ARE_nombre) VALUES ('UNIDAD DE RELACIONES PÚBLICAS Y COMUNICACIONES');
INSERT INTO AREA(ARE_nombre) VALUES ('GERENCIA DE GESTIÓN AMBIENTAL');
INSERT INTO AREA(ARE_nombre) VALUES ('GERENCIA DE ASESORÍA JURÍDICA');
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE PLANIFICACIÓN Y MODERNIZACIÓN INSTITUCIONAL');
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE GESTIÓN Y DESARROLLO DE RECURSOS HUMANOS');
INSERT INTO AREA(ARE_nombre) VALUES ('GERENCIA DE DESARROLLO SOCIAL');
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE EDUCACIÓN');
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE PROGRAMAS SOCIALES');
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE LICENCIAS');
INSERT INTO AREA(ARE_nombre) VALUES ('POLICÍA MUNICIPAL');
INSERT INTO AREA(ARE_nombre) VALUES ('REGISTRO CIVIL');
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE MANTENIMIENTO DE OBRAS PÚBLICAS');
INSERT INTO AREA(ARE_nombre) VALUES ('GERENCIA DE DESARROLLO URBANO');
INSERT INTO AREA(ARE_nombre) VALUES ('EJECUTORÍA COACTIVA');
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE ESTUDIOS Y PROYECTOS');
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE OBRAS');
INSERT INTO AREA(ARE_nombre) VALUES ('PROCURADORÍA PÚBLICA MUNICIPAL');
INSERT INTO AREA(ARE_nombre) VALUES ('GERENCIA DE ADMINISTRACIÓN Y FINANZAS');
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE DEFENSA CIVIL');
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE JUVENTUD, DEPORTE Y CULTURA');
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE ÁREAS VERDES');
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE SEGURIDAD CIUDADANA');
INSERT INTO AREA(ARE_nombre) VALUES ('ÓRGANO DE CONTROL INSTITUCIONAL - OCI');
INSERT INTO AREA(ARE_nombre) VALUES ('UNIDAD LOCAL DE EMPADRONAMIENTO - ULE');
INSERT INTO AREA(ARE_nombre) VALUES ('UNIDAD DE ATENCIÓN AL USUARIO Y TRÁMITE DOCUMENTARIO');
INSERT INTO AREA(ARE_nombre) VALUES ('GERENCIA DE SEGURIDAD CIUDADANA');
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE ABASTECIMIENTO');
INSERT INTO AREA(ARE_nombre) VALUES ('PARTICIPACION VECINAL');
INSERT INTO AREA(ARE_nombre) VALUES ('GERENCIA DE PLANEAMIENTO, PRESUPUESTO Y MODERNIZACIÓN');
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE TRANSPORTE, TRÁNSITO Y SEGURIDAD VIAL');
INSERT INTO AREA(ARE_nombre) VALUES ('ARCHIVO CENTRAL');
INSERT INTO AREA(ARE_nombre) VALUES ('EQUIPO MECÁNICO Y MAESTRANZA');
INSERT INTO AREA(ARE_nombre) VALUES ('SUBGERENCIA DE LIMPIEZA PÚBLICA');
INSERT INTO AREA(ARE_nombre) VALUES ('BIENESTAR SOCIAL');
INSERT INTO AREA(ARE_nombre) VALUES ('ORIENTACION TRIBUTARIA');
GO

-- VOLCADO DE DATOS PARA LA TABLA ESTADO
INSERT INTO ESTADO (EST_descripcion) VALUES ('ACTIVO');
INSERT INTO ESTADO (EST_descripcion) VALUES ('INACTIVO');
INSERT INTO ESTADO (EST_descripcion) VALUES ('ABIERTO');
INSERT INTO ESTADO (EST_descripcion) VALUES ('RECEPCIONADO');
INSERT INTO ESTADO (EST_descripcion) VALUES ('CERRADO');
GO

-- VOLCADO DE DATOS PARA LA TABLA USUARIO
INSERT INTO USUARIO (USU_nombre, USU_password, PER_codigo, ROL_codigo, ARE_codigo, EST_codigo)
VALUES ('GAMM95', '123456', 1, 2, 1, 1);
GO

-- VOLCADO DE DATOS PARA LA TABLA PRIORIDAD
INSERT INTO PRIORIDAD (PRI_nombre) VALUES ('BAJA');
INSERT INTO PRIORIDAD (PRI_nombre) VALUES ('MEDIA');
INSERT INTO PRIORIDAD (PRI_nombre) VALUES ('ALTA');
INSERT INTO PRIORIDAD (PRI_nombre) VALUES ('CRÍTICO');
GO

-- VOLCADO DE DATOS PARA LA TABLA CATEGORIA
INSERT INTO CATEGORIA (CAT_nombre) VALUES ('RED INACCESIBLE');
INSERT INTO CATEGORIA (CAT_nombre) VALUES ('ASISTENCIA TÉCNICA');
INSERT INTO CATEGORIA (CAT_nombre) VALUES ('GENERACIÓN DE USUARIO');
INSERT INTO CATEGORIA (CAT_nombre) VALUES ('FALLO DE EQUIPO DE CÓMPUTO');
INSERT INTO CATEGORIA (CAT_nombre) VALUES ('INACCESIBILIDAD A IMPRESORA');
INSERT INTO CATEGORIA (CAT_nombre) VALUES ('CORREO CORPORATIVO');
INSERT INTO CATEGORIA (CAT_nombre) VALUES ('REPORTE DE SISTEMAS INFORMÁTICOS');
INSERT INTO CATEGORIA (CAT_nombre) VALUES ('OTROS');
INSERT INTO CATEGORIA (CAT_nombre) VALUES ('INACCESIBILIDAD A SISTEMAS INFORMÁTICOS');
GO

-- VOLCADO DE DATOS PARA LA TABLA IMPACTO
INSERT INTO IMPACTO (IMP_descripcion) VALUES ('BAJO');
INSERT INTO IMPACTO (IMP_descripcion) VALUES ('MEDIO');
INSERT INTO IMPACTO (IMP_descripcion) VALUES ('ALTO');
GO

-- VOLCADO DE DATOS PARA LA TABLA BIEN
INSERT INTO BIEN (BIE_codigoPatrimonial, BIE_nombre) VALUES ('','SIN CODIGO');
INSERT INTO BIEN (BIE_codigoPatrimonial, BIE_nombre) VALUES ('74089950','CPU');
INSERT INTO BIEN (BIE_codigoPatrimonial, BIE_nombre) VALUES ('74088187','MONITOR');
INSERT INTO BIEN (BIE_codigoPatrimonial, BIE_nombre) VALUES ('74089500','TECLADO');
INSERT INTO BIEN (BIE_codigoPatrimonial, BIE_nombre) VALUES ('74088600','MOUSE');
INSERT INTO BIEN (BIE_codigoPatrimonial, BIE_nombre) VALUES ('46225215','ESTABILIZADOR');
GO

-- VOLCADO DE DATOS PARA LA TABLA CONDICION
INSERT INTO CONDICION (CON_descripcion) VALUES ('OPERATIVO');
INSERT INTO CONDICION (CON_descripcion) VALUES ('INOPERATIVO');
INSERT INTO CONDICION (CON_descripcion) VALUES ('SOLUCIONADO');
INSERT INTO CONDICION (CON_descripcion) VALUES ('NO SOLUCIONADO');
go

-------------------------------------------------------------------------------------------------------
  -- FUNCIONES Y TRIGGERS
-------------------------------------------------------------------------------------------------------
-- FUNCION PARA GENERAR EL NUMERO DE INCIDENCIA 0000-AÑO-MDE
CREATE FUNCTION dbo.GenerarNumeroIncidencia()
RETURNS VARCHAR(20)
AS
BEGIN
    DECLARE @numero INT;
    DECLARE @año_actual CHAR(4);
    DECLARE @formato VARCHAR(20);
    DECLARE @resultado VARCHAR(20);

    -- Obtener el año actual
    SET @año_actual = YEAR(GETDATE());

    -- Obtener el último número de incidencia del año actual
    SELECT @numero = ISNULL(MAX(CAST(SUBSTRING(INC_numero_formato, 1, CHARINDEX('-', INC_numero_formato) - 1) AS INT)), 0) + 1
    FROM INCIDENCIA
    WHERE SUBSTRING(INC_numero_formato, CHARINDEX('-', INC_numero_formato) + 1, 4) = @año_actual;

    -- Generar el formato con el número actual
    SET @formato = RIGHT('0000' + CAST(@numero AS VARCHAR(4)), 4) + '-' + @año_actual + '-MDE';
    SET @resultado = @formato;

    RETURN @resultado;
END;
GO

-- TRIGGER PARA ACTUALIZAR EL NUMERO DE INCIDENCIA FORMATEADO "INC_numero_formato"
CREATE TRIGGER trg_incrementar_inc_numero
ON INCIDENCIA
INSTEAD OF INSERT
AS
BEGIN
	DECLARE @ultimo_numero SMALLINT;
    
	-- Obtener el último número de incidencia
	SELECT @ultimo_numero = ISNULL(MAX(INC_numero), 0) FROM INCIDENCIA;
    
	-- Insertar el nuevo registro con INC_numero incrementado en 1
	INSERT INTO INCIDENCIA (INC_numero, INC_numero_formato, INC_fecha, INC_hora, INC_asunto, INC_descripcion, INC_documento, INC_codigoPatrimonial, EST_codigo, CAT_codigo, ARE_codigo, USU_codigo)
	SELECT @ultimo_numero + 1, INC_numero_formato, INC_fecha, INC_hora, INC_asunto, INC_descripcion, INC_documento, INC_codigoPatrimonial, EST_codigo, CAT_codigo, ARE_codigo, USU_codigo
	FROM inserted;
END;
GO

-- Trigger para actualizar el número del formato
CREATE TRIGGER trg_UpdateNumeroFormato
ON INCIDENCIA
AFTER INSERT
AS
BEGIN
    UPDATE INCIDENCIA
    SET INC_numero_formato = dbo.GenerarNumeroIncidencia()
    FROM INCIDENCIA i
    INNER JOIN inserted ins
    ON i.INC_numero = ins.INC_numero
    WHERE i.INC_numero_formato IS NULL; -- Solo actualiza si el formato es NULL
END;
GO



-------------------------------------------------------------------------------------------------------
  -- PROCEDIMIENTOS ALMACENADOS
-------------------------------------------------------------------------------------------------------

-- PROCEDIMIENTO ALMACENADO PARA INICIAR SESION
CREATE PROCEDURE SP_Usuario_login(
	@USU_usuario VARCHAR(20),
	@USU_password VARCHAR(10)
) 
AS 
BEGIN
	SET NOCOUNT ON;
	SELECT u.USU_nombre, u.USU_password, p.PER_nombres, p.PER_apellidoPaterno, r.ROL_codigo, r.ROL_nombre, a.ARE_codigo, a.ARE_nombre, u.EST_codigo
	FROM USUARIO u
	INNER JOIN PERSONA p ON p.PER_codigo = u.PER_codigo
	INNER JOIN ROL r ON r.ROL_codigo = u.ROL_codigo
	INNER JOIN AREA a ON a.ARE_codigo = u.ARE_codigo
	WHERE u.USU_nombre = @USU_usuario AND u.USU_password = @USU_password;
END;
GO


-- PROCEDIMIENTO ALMACENADO PARA ACTUALIZAR DATOS PERSONALES Y USUARIO
CREATE PROCEDURE EditarPersonaYUsuario
  @USU_codigo SMALLINT,
  @USU_nombre VARCHAR(20),
  @USU_password VARCHAR(10),
  @PER_dni CHAR(8),
  @PER_nombres VARCHAR(20),
  @PER_apellidoPaterno VARCHAR(15),
  @PER_apellidoMaterno VARCHAR(15),
  @PER_celular CHAR(9),
  @PER_email VARCHAR(45)
AS
BEGIN
  BEGIN TRY
    BEGIN TRANSACTION; -- Inicia una transacción para asegurar la consistencia de los datos
      -- Actualiza los datos del usuario
      UPDATE USUARIO
      SET 
          USU_nombre = @USU_nombre,
          USU_password = @USU_password
      WHERE USU_codigo = @USU_codigo;

      -- Actualiza los datos de la persona vinculada al usuario
      UPDATE PERSONA
      SET 
          PER_dni = @PER_dni,
          PER_nombres = @PER_nombres,
          PER_apellidoPaterno = @PER_apellidoPaterno,
          PER_apellidoMaterno = @PER_apellidoMaterno,
          PER_celular = @PER_celular,
          PER_email = @PER_email
      WHERE PER_codigo = (
          SELECT PER_codigo 
          FROM USUARIO 
          WHERE USU_codigo = @USU_codigo
      );
    COMMIT TRANSACTION; -- Confirma la transacción si todo está correcto
  END TRY
  BEGIN CATCH   
    ROLLBACK TRANSACTION; -- Si hay un error, deshace la transacción
    -- Manejo de errores: muestra el error en la salida
    DECLARE @ErrorMessage NVARCHAR(4000);
    DECLARE @ErrorSeverity INT;
    DECLARE @ErrorState INT;

    SELECT 
        @ErrorMessage = ERROR_MESSAGE(),
        @ErrorSeverity = ERROR_SEVERITY(),
        @ErrorState = ERROR_STATE();

    RAISERROR (@ErrorMessage, @ErrorSeverity, @ErrorState);
  END CATCH
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA REGISTRAR USUARIO
CREATE PROCEDURE SP_Registrar_Usuario ( 
	@USU_nombre VARCHAR(20),
	@USU_password VARCHAR(10),
	@PER_codigo SMALLINT,
	@ROL_codigo SMALLINT,
	@ARE_codigo SMALLINT)
AS
BEGIN 
	-- Verificar si la persona ya tiene un usuario registrado
	IF EXISTS (SELECT 1 FROM USUARIO WHERE PER_codigo = @PER_codigo)
	BEGIN
		-- Si la persona ya tiene un usuario, retornar un mensaje de error o un código de error
		RAISERROR('La persona ya tiene un usuario registrado.', 16, 1);
		RETURN;
	END

	-- Insertar el nuevo usuario con EST_codigo siempre igual a 1
	INSERT INTO USUARIO (USU_nombre, USU_password, PER_codigo, ROL_codigo, ARE_codigo, EST_codigo)
	VALUES (@USU_nombre, @USU_password, @PER_codigo, @ROL_codigo, @ARE_codigo, 1);
END;
GO


-- PROCEDIMIENTO ALMACENADO PARA REGISTRAR INCIDENCIA - ADMINISTRADOR / USUARIO
CREATE PROCEDURE SP_Registrar_Incidencia
  @INC_fecha DATE,
  @INC_hora TIME,
  @INC_asunto VARCHAR(200),
  @INC_descripcion VARCHAR(500),
  @INC_documento VARCHAR(100),
  @INC_codigoPatrimonial CHAR(12) = NULL,
  @CAT_codigo SMALLINT,
  @ARE_codigo SMALLINT,
  @USU_codigo SMALLINT
AS 
BEGIN 
  -- Variables para el número de incidencia
  DECLARE @numero_formato VARCHAR(20);

  -- Generar el número de incidencia formateado
  SET @numero_formato = dbo.GenerarNumeroIncidencia();

  -- Insertar la nueva incidencia
  INSERT INTO INCIDENCIA (INC_fecha, INC_hora, INC_asunto, INC_descripcion, INC_documento, INC_codigoPatrimonial, EST_codigo, CAT_codigo, ARE_codigo,  USU_codigo, INC_numero_formato)
  VALUES (@INC_fecha, @INC_hora, @INC_asunto, @INC_descripcion, @INC_documento, @INC_codigoPatrimonial, 3, @CAT_codigo, @ARE_codigo, @USU_codigo, @numero_formato);
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA ACTUALIZAR INCIDENCIA
CREATE PROCEDURE sp_ActualizarIncidencia
  @INC_numero SMALLINT,
  @CAT_codigo SMALLINT,
  @ARE_codigo SMALLINT,
  @INC_codigoPatrimonial CHAR(12),
  @INC_asunto VARCHAR(200),
  @INC_documento VARCHAR(100),
  @INC_descripcion VARCHAR(500)
AS
BEGIN
  -- Actualizar el registro en la tabla INCIDENCIA solo si el estado es 3
  UPDATE INCIDENCIA
  SET CAT_codigo = @CAT_codigo,
      ARE_codigo = @ARE_codigo,
      INC_codigoPatrimonial = @INC_codigoPatrimonial,
      INC_asunto = @INC_asunto,
      INC_documento = @INC_documento,
      INC_descripcion = @INC_descripcion
  WHERE INC_numero = @INC_numero
  AND EST_codigo = 3;
END;
GO 


-- PROCEDIMIENTO ALMACENADO PARA INSERTAR LA RECEPCION Y ACTUALIZAR ESTADO DE INCIDENCIA
CREATE PROCEDURE sp_InsertarRecepcionActualizarIncidencia(
	@REC_fecha DATE,
	@REC_hora TIME,
	@INC_numero INT,
	@PRI_codigo INT,
	@IMP_codigo INT,
	@USU_codigo INT
)
AS BEGIN
SET
	NOCOUNT ON;
	BEGIN TRY BEGIN TRANSACTION;

	-- Insertar la nueva recepción
	INSERT INTO RECEPCION (REC_fecha, REC_hora, INC_numero, PRI_codigo, IMP_codigo, USU_codigo, EST_codigo )
	VALUES (@REC_fecha, @REC_hora, @INC_numero, @PRI_codigo, @IMP_codigo, @USU_codigo, 4);
	
	-- Actualizar el estado de la incidencia
	UPDATE INCIDENCIA SET EST_codigo = 4
	WHERE INC_numero = @INC_numero;

	COMMIT TRANSACTION;
	END TRY BEGIN CATCH ROLLBACK TRANSACTION;
	THROW;
	END CATCH
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA ACTUALIZAR RECEPCION
CREATE PROCEDURE sp_ActualizarRecepcion
  @REC_numero SMALLINT,
  @PRI_codigo SMALLINT,
  @IMP_codigo SMALLINT
AS
BEGIN
  -- Actualizar el registro en la tabla RECEPCION solo si el estado es 4
  UPDATE RECEPCION
  SET PRI_codigo = @PRI_codigo,
      IMP_codigo = @IMP_codigo
  WHERE REC_numero = @REC_numero
	AND EST_codigo = 4;
END;
GO 

-- PROCEDIMIENTO ALMAENADO PARA INSERTAR CIERRES Y ACTUALIZAR ESTADO DE RECEPCION
CREATE PROCEDURE sp_InsertarCierreActualizarRecepcion
  @CIE_fecha DATE,
  @CIE_hora TIME,
  @CIE_diagnostico VARCHAR(200),
  @CIE_documento VARCHAR(500),
  @CIE_asunto VARCHAR(200),
  @CIE_recomendaciones VARCHAR(500),
  @CON_codigo SMALLINT,
  @REC_numero SMALLINT,
  @USU_codigo SMALLINT
AS BEGIN
SET 
	NOCOUNT ON;
  BEGIN TRY 
    BEGIN TRANSACTION;

    -- Insertar el nuevo cierre
    INSERT INTO CIERRE (CIE_fecha, CIE_hora, CIE_diagnostico, CIE_documento, CIE_asunto, CIE_recomendaciones, CON_codigo, REC_numero, USU_codigo, EST_codigo)
    VALUES (@CIE_fecha, @CIE_hora , @CIE_diagnostico, @CIE_documento, @CIE_asunto, @CIE_recomendaciones, @CON_codigo, @REC_numero, @USU_codigo, 5);
    
    -- Actualizar el estado de la recepcion
    UPDATE RECEPCION SET EST_codigo = 5
    WHERE REC_numero = @REC_numero;

    COMMIT TRANSACTION;
  END TRY 
  BEGIN CATCH 
    ROLLBACK TRANSACTION;
    THROW;
  END CATCH
END;
GO

-- PROCEDIMIENTO ALMANCENADO PARA CONSULTAR INCIDENCIAS - ADMINISTRADOR
CREATE PROCEDURE sp_ConsultarIncidencias
  @area INT,
  @estado INT,
  @fechaInicio DATE,
  @fechaFin DATE
AS
BEGIN
  SELECT 
    I.INC_numero,
    INC_numero_formato,
    (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
    I.INC_codigoPatrimonial,
    I.INC_asunto,
    I.INC_documento,
    I.INC_descripcion,
    CAT.CAT_nombre,
    A.ARE_nombre,
    CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS ESTADO,
    p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario
  FROM INCIDENCIA I
  INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
  INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
  INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
  LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
  LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
  LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
  LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
  LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
  LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
  LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
  INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
  WHERE 
    I.EST_codigo IN (3, 4) -- Solo incluir incidencias con estado 3 o 4
    AND NOT EXISTS (  -- Excluir incidencias que hayan pasado al estado 5 en la tabla CIERRE
        SELECT 1 
        FROM CIERRE C2
        WHERE C2.REC_numero = R.REC_numero
        AND C2.EST_codigo = 5
    )
  AND 
    (@estado IS NULL OR e.EST_codigo = @estado) AND  -- Solo filtra por estado si @estado no es NULL
    (@fechaInicio IS NULL OR INC_fecha >= @fechaInicio) AND  -- Filtra por fecha de inicio si @fechaInicio no es NULL
    (@fechaFin IS NULL OR INC_fecha <= @fechaFin) AND        -- Filtra por fecha de fin si @fechaFin no es NULL
    (@area IS NULL OR a.ARE_codigo = @area)     -- Solo filtra por área si @areaCodigo no es NULL
  ORDER BY 
    -- Extraer el año de INC_numero_formato y ordenar por año de forma descendente
    SUBSTRING(INC_numero_formato, CHARINDEX('-', INC_numero_formato) + 1, 4) DESC,
    -- Ordenar por el número de incidencia también en orden descendente
    I.INC_numero_formato DESC;
END
GO

-- PROCEDIMIENTO ALMACENADO PARA CONSULTAR INCIDENCIAS TOTALES - ADMINISTRADOR
CREATE PROCEDURE sp_ConsultarIncidenciasTotales
  @area INT = NULL,
  @codigoPatrimonial CHAR(12) = NULL,
  @fechaInicio DATE = NULL,
  @fechaFin DATE = NULL
AS
BEGIN
  SELECT 
    I.INC_numero,
    INC_numero_formato,
    (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
    I.INC_codigoPatrimonial,
    I.INC_asunto,
    I.INC_documento,
    I.INC_descripcion,
    PRI.PRI_nombre,
    CAT.CAT_nombre,
    A.ARE_nombre,
    CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS Estado,
    p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario,
    -- Última modificación (fecha y hora más reciente)
    MAX(COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha)) AS ultimaFecha,
    MAX(COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora)) AS ultimaHora
  FROM INCIDENCIA I
  INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
  INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
  INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
  LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
  LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
  LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
  LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
  LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
  LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
  LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
  INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
  WHERE 
    I.EST_codigo IN (3, 4, 5) 
    AND 
    (@codigoPatrimonial IS NULL OR I.INC_codigoPatrimonial = @codigoPatrimonial)
    AND (@fechaInicio IS NULL OR I.INC_fecha >= @fechaInicio)
    AND (@fechaFin IS NULL OR I.INC_fecha <= @fechaFin)
    AND (@area IS NULL OR A.ARE_codigo = @area)
  GROUP BY 
    I.INC_numero,
    INC_numero_formato,
    I.INC_fecha,
    I.INC_hora,
    I.INC_codigoPatrimonial,
    I.INC_asunto,
    I.INC_documento,
    I.INC_descripcion,
    CAT.CAT_nombre,
    A.ARE_nombre,
    C.CIE_numero,
    EC.EST_descripcion,
    E.EST_descripcion,
    PRI.PRI_nombre,
    p.PER_nombres,
    p.PER_apellidoPaterno
  ORDER BY 
    MAX(COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha)) DESC,
    MAX(COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora)) DESC;
END
GO

-- PROCEDIMIENTO ALMANCENADO PARA CONSULTAR INCIDENCIAS - USUARIO
CREATE PROCEDURE sp_ConsultarIncidenciasUsuario
  @area INT,
  @codigoPatrimonial CHAR(12),
  @estado INT,
  @fechaInicio DATE,
  @fechaFin DATE
AS
BEGIN
  SELECT 
    I.INC_numero,
    I.INC_numero_formato,
    (CONVERT(VARCHAR(10), I.INC_fecha, 103)) AS fechaIncidenciaFormateada,
    I.INC_codigoPatrimonial,
    I.INC_asunto,
    I.INC_documento,
    I.INC_descripcion,
    (CONVERT(VARCHAR(10), R.REC_fecha, 103)) AS fechaRecepcionFormateada,
    PRI.PRI_nombre,
    O.CON_descripcion,
	  (CONVERT(VARCHAR(10), C.CIE_fecha, 103)) AS fechaCierreFormateada,
    CAT.CAT_nombre,
    A.ARE_nombre,
    CASE
      WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
      ELSE E.EST_descripcion
    END AS ESTADO,
    p.PER_nombres + ' ' + p.PER_apellidoPaterno AS Usuario
  FROM INCIDENCIA I
  INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
  INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
  INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
  LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
  LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
  LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
  LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
  LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
  LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
  LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
  INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
  WHERE 
    (@codigoPatrimonial IS NULL OR I.INC_codigoPatrimonial = @codigoPatrimonial) AND
    (@estado IS NULL OR 
        (EC.EST_codigo = @estado OR 
        (I.EST_codigo = @estado AND C.CIE_numero IS NULL))) AND
    (@fechaInicio IS NULL OR I.INC_fecha >= @fechaInicio) AND
    (@fechaFin IS NULL OR I.INC_fecha <= @fechaFin) AND
    (@area IS NULL OR A.ARE_codigo = @area)
    AND (I.EST_codigo IN (3, 4, 5) OR EC.EST_codigo IN (3, 4, 5))
  ORDER BY 
    SUBSTRING(I.INC_numero_formato, CHARINDEX('-', I.INC_numero_formato) + 1, 4) DESC,
    I.INC_numero_formato DESC;
END
GO

-- PROCEDIMIENTO ALMANCENADO PARA CONSULTAR CIERRES - ADMINISTRADOR
CREATE PROCEDURE sp_ConsultarCierres
  @area INT,
  @codigoPatrimonial CHAR(12),
  @fechaInicio DATE,
  @fechaFin DATE
AS
BEGIN
  SELECT
    I.INC_numero,
    INC_numero_formato,
    (CONVERT(VARCHAR(10),INC_fecha,103) + ' - '+   STUFF(RIGHT('0' + CONVERT(VarChar(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada,
    A.ARE_nombre,
    CAT.CAT_nombre,
    I.INC_asunto,
    I.INC_documento,
    PRI.PRI_nombre,
    I.INC_codigoPatrimonial,
    (CONVERT(VARCHAR(10),CIE_fecha,103)) AS fechaCierreFormateada,
    C.CIE_asunto,
    C.CIE_documento,
    O.CON_descripcion,
    PER_nombres + ' ' + PER_apellidoPaterno AS Usuario,
    CASE
    WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
      ELSE E.EST_descripcion
    END AS Estado
  FROM RECEPCION R
  INNER JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
  RIGHT JOIN INCIDENCIA I ON R.INC_numero = I.INC_numero
  INNER JOIN  AREA A ON I.ARE_codigo = A.ARE_codigo
  INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
  INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
  LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
  LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
  INNER JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
  INNER JOIN USUARIO U ON U.USU_codigo = C.USU_codigo
  INNER JOIN PERSONA p ON p.PER_codigo = u.PER_codigo
  WHERE  I.EST_codigo = 5 OR C.EST_codigo = 5 AND
    (@codigoPatrimonial IS NULL OR I.INC_codigoPatrimonial = @codigoPatrimonial) AND  -- Solo filtra por estado si @estado no es NULL
    (@fechaInicio IS NULL OR CIE_fecha >= @fechaInicio) AND  -- Filtra por fecha de inicio si @fechaInicio no es NULL
    (@fechaFin IS NULL OR CIE_fecha <= @fechaFin) AND        -- Filtra por fecha de fin si @fechaFin no es NULL
    (@area IS NULL OR a.ARE_codigo = @area)     -- Solo filtra por área si @areaCodigo no es NULL
  ORDER BY C.CIE_numero DESC
END
GO


-------------------------------------------------------------------------------------------------------
  -- VISTAS
-------------------------------------------------------------------------------------------------------
--Vista para listar las nuevas incidencias para el administrador
CREATE VIEW vista_incidencias_administrador AS
SELECT 
  I.INC_numero,
  I.INC_numero_formato,
  (CONVERT(VARCHAR(10), I.INC_fecha, 103)) AS fechaIncidenciaFormateada,
  I.INC_codigoPatrimonial,
  I.INC_asunto,
  I.INC_documento,
  I.INC_descripcion,
  CAT.CAT_nombre,
  A.ARE_nombre,
  CASE
      WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
      ELSE E.EST_descripcion
  END AS ESTADO,
  p.PER_nombres + ' ' + p.PER_apellidoPaterno AS Usuario
FROM 
  INCIDENCIA I
  INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
  INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
  INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
  LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
  LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
  LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
  LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
  LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
  LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
  LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
  INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
WHERE 
    (I.EST_codigo IN (3) OR C.EST_codigo IN (3));
GO

--Vista para listar las incidencias recepionadas para el administrador
CREATE VIEW vista_recepciones AS
SELECT 
    I.INC_numero,
    I.INC_numero_formato,
    (CONVERT(VARCHAR(10), I.INC_fecha, 103)) AS fechaIncidenciaFormateada,
    I.INC_codigoPatrimonial,
    I.INC_asunto,
    I.INC_documento,
    I.INC_descripcion,
	R.REC_numero,
    (CONVERT(VARCHAR(10), R.REC_fecha, 103)) AS fechaRecepcionFormateada,
    PRI.PRI_nombre,
	IMP.IMP_descripcion,
    O.CON_descripcion,
    CAT.CAT_nombre,
    A.ARE_nombre,
    CASE
      WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
      ELSE E.EST_descripcion
    END AS ESTADO,
    p.PER_nombres + ' ' + p.PER_apellidoPaterno AS UsuarioIncidente,
    pR.PER_nombres + ' ' + pR.PER_apellidoPaterno AS UsuarioRecepcion
FROM INCIDENCIA I
INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
LEFT JOIN USUARIO uR ON uR.USU_codigo = R.USU_codigo -- Relación para obtener el usuario de la recepción
LEFT JOIN PERSONA pR ON pR.PER_codigo = uR.PER_codigo -- Relación para obtener la persona del usuario de la recepción
LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
WHERE 
    (EC.EST_codigo = 4 OR 
    (I.EST_codigo = 4 AND C.CIE_numero IS NULL))
    AND (I.EST_codigo IN (3, 4, 5) OR EC.EST_codigo IN (3, 4, 5));
GO

--Vista para listar las incidencias totales para el administrador
CREATE VIEW vista_incidencias_totales_administrador AS
SELECT
    I.INC_numero,
    I.INC_numero_formato,
    (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
    A.ARE_nombre,
    CAT.CAT_nombre,
    I.INC_asunto,
    I.INC_codigoPatrimonial,
    I.INC_documento,
    (CONVERT(VARCHAR(10), REC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
    PRI.PRI_nombre,
    IMP.IMP_descripcion,
    (CONVERT(VARCHAR(10), CIE_fecha, 103)) AS fechaCierreFormateada,
    O.CON_descripcion,
    U.USU_nombre,
    CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS Estado
FROM INCIDENCIA I
INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
WHERE (I.EST_codigo IN (3, 4, 5) OR C.EST_codigo IN (3, 4, 5));
GO

-- Vista para listar incidencias pendientes de cierre
CREATE VIEW vista_incidencias_pendientes AS
SELECT 
    I.INC_numero,
    INC_numero_formato,
    (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
    I.INC_codigoPatrimonial,
    I.INC_asunto,
    I.INC_documento,
    I.INC_descripcion,
    CAT.CAT_nombre,
    A.ARE_nombre,
    CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS ESTADO,
    p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario,
    -- Última modificación (fecha y hora más reciente)
    MAX(COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha)) AS ultimaFecha,
    MAX(COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora)) AS ultimaHora
FROM INCIDENCIA I
INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
WHERE 
    I.EST_codigo IN (3, 4) -- Solo incluir incidencias con estado 3 o 4
    AND NOT EXISTS (  -- Excluir incidencias que hayan pasado al estado 5 en la tabla CIERRE
        SELECT 1 
        FROM CIERRE C2
        WHERE C2.REC_numero = R.REC_numero
        AND C2.EST_codigo = 5
    )
GROUP BY 
    I.INC_numero,
    INC_numero_formato,
    I.INC_fecha,
    I.INC_hora,
    I.INC_codigoPatrimonial,
    I.INC_asunto,
    I.INC_documento,
    I.INC_descripcion,
    CAT.CAT_nombre,
    A.ARE_nombre,
    C.CIE_numero,
    EC.EST_descripcion,
    E.EST_descripcion,
    p.PER_nombres,
    p.PER_apellidoPaterno;
GO

--Vista para listar las nuevas incidencias para el usuario
CREATE VIEW vista_incidencias_usuario AS 
SELECT
    I.INC_numero,
    I.INC_numero_formato,
    (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
	A.ARE_codigo,
    A.ARE_nombre,
    CAT.CAT_nombre,
    I.INC_asunto,
    I.INC_documento,
	I.INC_descripcion,
    I.INC_codigoPatrimonial,
    U.USU_nombre,
    p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario,
	E.EST_descripcion AS ESTADO
FROM INCIDENCIA I
INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
WHERE I.EST_codigo IN (3);

--Vista para listar las incidencias totales para el usuario en la consulta
CREATE VIEW vista_incidencias_totales_usuario AS
SELECT
    I.INC_numero,
    I.INC_numero_formato,
    (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
	A.ARE_codigo,
    A.ARE_nombre,
    CAT.CAT_nombre,
    I.INC_asunto,
    I.INC_documento,
    I.INC_codigoPatrimonial,
    (CONVERT(VARCHAR(10), REC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
    PRI.PRI_nombre,
    IMP.IMP_descripcion,
    (CONVERT(VARCHAR(10), CIE_fecha, 103)) AS fechaCierreFormateada,
    O.CON_descripcion,
    U.USU_nombre,
    p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario,
    CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS ESTADO
FROM INCIDENCIA I
INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
WHERE I.EST_codigo IN (3, 4, 5) OR C.EST_codigo IN (3, 4, 5);
GO

-- Vista para listar incidencias por fecha para el administrador
CREATE VIEW vista_incidencias_fecha_admin AS
SELECT 
    I.INC_numero,
    I.INC_numero_formato,
	I.INC_fecha,
    (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
    A.ARE_nombre,
    CAT.CAT_nombre,
    I.INC_asunto,
    I.INC_documento,
    I.INC_codigoPatrimonial,
    (CONVERT(VARCHAR(10), REC_fecha, 103)) AS fechaRecepcionFormateada,
    PRI.PRI_nombre,
    IMP.IMP_descripcion,
    (CONVERT(VARCHAR(10), CIE_fecha, 103)) AS fechaCierreFormateada,
    O.CON_descripcion,
    U.USU_nombre,
    p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario,
    CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS ESTADO
FROM INCIDENCIA I
INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
WHERE I.EST_codigo IN (3, 4, 5) OR C.EST_codigo IN (3, 4, 5);


-- Vista para listar incidencias por fecha para el usuario
CREATE VIEW vista_incidencias_fecha_user AS
SELECT 
	I.INC_numero,
    I.INC_numero_formato,
	I.INC_fecha,
    (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
	A.ARE_codigo,
    A.ARE_nombre,
    CAT.CAT_nombre,
    I.INC_asunto,
    I.INC_documento,
    I.INC_codigoPatrimonial,
    (CONVERT(VARCHAR(10), REC_fecha, 103)) AS fechaRecepcionFormateada,
    PRI.PRI_nombre,
    IMP.IMP_descripcion,
    (CONVERT(VARCHAR(10), CIE_fecha, 103)) AS fechaCierreFormateada,
    O.CON_descripcion,
    U.USU_nombre,
    p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario,
    CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS ESTADO
FROM INCIDENCIA I
INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
WHERE (I.EST_codigo IN (3, 4, 5) OR C.EST_codigo IN (3, 4, 5));


