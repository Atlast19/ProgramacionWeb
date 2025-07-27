-- Crear base de datos
CREATE DATABASE IF NOT EXISTS reporte_incidencias;
USE reporte_incidencias;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    correo VARCHAR(100) UNIQUE,
    tipo_usuario ENUM('reportero', 'validador', 'admin') NOT NULL,
    autenticado_por ENUM('gmail', 'office', 'tradicional') NOT NULL,
    contraseña VARCHAR(255) NULL
);

-- Tabla de provincias
CREATE TABLE provincias (
    id_provincia INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- Tabla de municipios
CREATE TABLE municipios (
    id_municipio INT AUTO_INCREMENT PRIMARY KEY,
    id_provincia INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_provincia) REFERENCES provincias(id_provincia)
);

-- Tabla de barrios
CREATE TABLE barrios (
    id_barrio INT AUTO_INCREMENT PRIMARY KEY,
    id_municipio INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_municipio) REFERENCES municipios(id_municipio)
);

-- Tabla de tipos de incidencias
CREATE TABLE tipos_incidencias (
    id_tipo INT AUTO_INCREMENT PRIMARY KEY,
    nombre_tipo VARCHAR(100) NOT NULL
);

-- Tabla de incidencias
CREATE TABLE incidencias (
    id_incidencia INT AUTO_INCREMENT PRIMARY KEY,
    fecha_ocurrencia DATE NOT NULL,
    titulo VARCHAR(150),
    descripcion TEXT,
    muertos INT DEFAULT 0,
    heridos INT DEFAULT 0,
    perdida_estimada DECIMAL(12,2) DEFAULT 0.00,
    link_redes TEXT,
    foto_url TEXT,
    latitud DECIMAL(10,8),
    longitud DECIMAL(11,8),
    id_provincia INT,
    id_municipio INT,
    id_barrio INT,
    id_reportero INT,
    validado BOOLEAN DEFAULT FALSE,
    combinada_con INT NULL,
    FOREIGN KEY (id_provincia) REFERENCES provincias(id_provincia),
    FOREIGN KEY (id_municipio) REFERENCES municipios(id_municipio),
    FOREIGN KEY (id_barrio) REFERENCES barrios(id_barrio),
    FOREIGN KEY (id_reportero) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (combinada_con) REFERENCES incidencias(id_incidencia)
);

-- Relación N:N entre incidencias y tipos
CREATE TABLE incidencia_tipo (
    id_incidencia INT,
    id_tipo INT,
    PRIMARY KEY (id_incidencia, id_tipo),
    FOREIGN KEY (id_incidencia) REFERENCES incidencias(id_incidencia),
    FOREIGN KEY (id_tipo) REFERENCES tipos_incidencias(id_tipo)
);

-- Tabla de comentarios
CREATE TABLE comentarios (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    id_incidencia INT,
    id_usuario INT,
    comentario TEXT,
    fecha_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_incidencia) REFERENCES incidencias(id_incidencia),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- Tabla de correcciones sugeridas
CREATE TABLE correcciones (
    id_correccion INT AUTO_INCREMENT PRIMARY KEY,
    id_incidencia INT,
    id_usuario INT,
    muertos INT NULL,
    heridos INT NULL,
    perdida_estimada DECIMAL(12,2) NULL,
    id_provincia INT NULL,
    id_municipio INT NULL,
    latitud DECIMAL(10,8) NULL,
    longitud DECIMAL(11,8) NULL,
    estado ENUM('pendiente', 'aprobada', 'rechazada') DEFAULT 'pendiente',
    FOREIGN KEY (id_incidencia) REFERENCES incidencias(id_incidencia),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_provincia) REFERENCES provincias(id_provincia),
    FOREIGN KEY (id_municipio) REFERENCES municipios(id_municipio)
);

-- ✅ NUEVO: Tabla para historial de validaciones
CREATE TABLE validaciones (
    id_validacion INT AUTO_INCREMENT PRIMARY KEY,
    id_incidencia INT NOT NULL,
    id_validador INT NOT NULL,
    fecha_validacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    accion ENUM('validar', 'combinar') NOT NULL,
    notas TEXT,
    FOREIGN KEY (id_incidencia) REFERENCES incidencias(id_incidencia),
    FOREIGN KEY (id_validador) REFERENCES usuarios(id_usuario)
);

-- ✅ NUEVO: Tabla para múltiples imágenes por incidencia
CREATE TABLE imagenes_incidencia (
    id_imagen INT AUTO_INCREMENT PRIMARY KEY,
    id_incidencia INT NOT NULL,
    url TEXT NOT NULL,
    FOREIGN KEY (id_incidencia) REFERENCES incidencias(id_incidencia)
);