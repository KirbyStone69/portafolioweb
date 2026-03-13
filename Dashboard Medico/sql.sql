-- Base de datos de la clinica
DROP DATABASE IF EXISTS clinica_db;
CREATE DATABASE clinica_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE clinica_db;

-- Tabla de especialidades
CREATE TABLE Especialidades (
    IdEspecialidad INT AUTO_INCREMENT PRIMARY KEY,
    NombreEspecialidad VARCHAR(100) NOT NULL,
    Descripcion VARCHAR(250)
);

-- Tabla de Control_Pacientes
CREATE TABLE Control_Pacientes (
    IdPaciente INT AUTO_INCREMENT PRIMARY KEY,
    NombreCompleto VARCHAR(150) NOT NULL,
    CURP VARCHAR(18) UNIQUE,
    FechaNacimiento DATE,
    Sexo CHAR(1),
    Telefono VARCHAR(20),
    CorreoElectronico VARCHAR(100),
    Direccion VARCHAR(250),
    ContactoEmergencia VARCHAR(150),
    TelefonoEmergencia VARCHAR(20),
    Alergias VARCHAR(250),
    AntecedentesMedicos TEXT,
    FechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
    Estatus BIT DEFAULT 1
);

-- Tabla de Control_Medicos
CREATE TABLE Control_Medicos (
    IdMedico INT AUTO_INCREMENT PRIMARY KEY,
    NombreCompleto VARCHAR(150) NOT NULL,
    CedulaProfesional VARCHAR(50) UNIQUE,
    EspecialidadId INT,
    Telefono VARCHAR(20),
    CorreoElectronico VARCHAR(100),
    HorarioAtencion TEXT COMMENT 'JSON con horarios por dia',
    FechaIngreso DATETIME DEFAULT CURRENT_TIMESTAMP,
    Estatus BIT DEFAULT 1,
    FOREIGN KEY (EspecialidadId) REFERENCES Especialidades(IdEspecialidad) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Tabla: Control_Agenda
CREATE TABLE Control_Agenda (
    IdCita INT AUTO_INCREMENT PRIMARY KEY,
    IdPaciente INT NOT NULL,
    IdMedico INT NOT NULL,
    FechaCita DATETIME NOT NULL,
    MotivoConsulta VARCHAR(250),
    EstadoCita VARCHAR(20) DEFAULT 'Programada' COMMENT 'Programada, Completada, Cancelada',
    Observaciones VARCHAR(250),
    FechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IdPaciente) REFERENCES Control_Pacientes(IdPaciente) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (IdMedico) REFERENCES Control_Medicos(IdMedico) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tabla de expedientes
CREATE TABLE Expediente_Clinico (
    IdExpediente INT AUTO_INCREMENT PRIMARY KEY,
    IdPaciente INT NOT NULL,
    IdMedico INT NOT NULL,
    IdCita INT COMMENT 'Opcional - vincula con la cita que genero este expediente',
    FechaConsulta DATETIME DEFAULT CURRENT_TIMESTAMP,
    Sintomas TEXT,
    Diagnostico TEXT,
    Tratamiento TEXT,
    RecetaMedica TEXT,
    NotasAdicionales TEXT,
    ProximaCita DATETIME,
    FOREIGN KEY (IdPaciente) REFERENCES Control_Pacientes(IdPaciente) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (IdMedico) REFERENCES Control_Medicos(IdMedico) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (IdCita) REFERENCES Control_Agenda(IdCita) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Tabla Tarifas
CREATE TABLE Gestor_Tarifas (
    IdTarifa INT AUTO_INCREMENT PRIMARY KEY,
    DescripcionServicio VARCHAR(150) NOT NULL,
    CostoBase DECIMAL(10,2) NOT NULL,
    EspecialidadId INT,
    Estatus BIT DEFAULT 1,
    FOREIGN KEY (EspecialidadId) REFERENCES Especialidades(IdEspecialidad) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Tabla: pagos
CREATE TABLE Gestor_Pagos (
    IdPago INT AUTO_INCREMENT PRIMARY KEY,
    IdCita INT NOT NULL,
    IdPaciente INT NOT NULL,
    Monto DECIMAL(10,2) NOT NULL,
    MetodoPago VARCHAR(50) COMMENT 'Efectivo, Tarjeta, Transferencia',
    FechaPago DATETIME DEFAULT CURRENT_TIMESTAMP,
    Referencia VARCHAR(100),
    EstatusPago VARCHAR(20) DEFAULT 'Pendiente' COMMENT 'Pendiente, Pagado, Cancelado',
    FOREIGN KEY (IdCita) REFERENCES Control_Agenda(IdCita) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (IdPaciente) REFERENCES Control_Pacientes(IdPaciente) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tabla de usuarios
CREATE TABLE Usuarios_Sistema (
    IdUsuario INT AUTO_INCREMENT PRIMARY KEY,
    Usuario VARCHAR(50) UNIQUE NOT NULL,
    Contrasena VARCHAR(100) NOT NULL,
    Rol VARCHAR(50) NOT NULL COMMENT 'Admin, Medico, Recepcionista',
    IdMedico INT COMMENT 'Solo para usuarios con rol Medico',
    NombreCompleto VARCHAR(150),
    Telefono VARCHAR(20),
    CorreoElectronico VARCHAR(100),
    Activo BIT DEFAULT 1,
    UltimoAcceso DATETIME,
    FOREIGN KEY (IdMedico) REFERENCES Control_Medicos(IdMedico) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Tabla relacion recepcionista-medico (muchos a muchos)
CREATE TABLE Recepcionista_Medico (
    IdRelacion INT AUTO_INCREMENT PRIMARY KEY,
    IdRecepcionista INT NOT NULL COMMENT 'IdUsuario con rol Recepcionista',
    IdMedico INT NOT NULL,
    FechaAsignacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IdRecepcionista) REFERENCES Usuarios_Sistema(IdUsuario) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (IdMedico) REFERENCES Control_Medicos(IdMedico) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY unique_asignacion (IdRecepcionista, IdMedico)
);

-- Tabla de bitacora
CREATE TABLE Bitacora_Acceso (
    IdBitacora INT AUTO_INCREMENT PRIMARY KEY,
    IdUsuario INT NOT NULL,
    FechaAcceso DATETIME DEFAULT CURRENT_TIMESTAMP,
    AccionRealizada VARCHAR(250) COMMENT 'Insertar, Actualizar, Eliminar, Ver',
    Modulo VARCHAR(100) COMMENT 'Especialidades, Medicos, Usuarios, etc',
    FOREIGN KEY (IdUsuario) REFERENCES Usuarios_Sistema(IdUsuario) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tabla: Reportes
CREATE TABLE Reportes (
    IdReporte INT AUTO_INCREMENT PRIMARY KEY,
    TipoReporte VARCHAR(50) NOT NULL COMMENT 'Citas, Pacientes, Ingresos, etc',
    IdPaciente INT,
    IdMedico INT,
    FechaGeneracion DATETIME DEFAULT CURRENT_TIMESTAMP,
    RutaArchivo VARCHAR(250),
    Descripcion VARCHAR(250),
    GeneradoPor VARCHAR(100),
    FOREIGN KEY (IdPaciente) REFERENCES Control_Pacientes(IdPaciente) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (IdMedico) REFERENCES Control_Medicos(IdMedico) ON DELETE SET NULL ON UPDATE CASCADE
);