create database coneccion;
use coneccion;
-- Tabla de usuarios
CREATE TABLE  usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL
);

CREATE TABLE facturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rec VARCHAR(10) NULL,
    fecha DATE NOT NULL,
    cliente VARCHAR(100) NOT NULL,
    total decimal(10,2) not null
    -- Agrega más campos si vas a guardar detalles
);
drop table facturas;
select * from Facturas;

INSERT INTO productos (nombre, precio) VALUES 
('Refrescos', 50.00),
('Bollitos', 25.00),
('Jugos Naturales', 60.00),
('Empanadas', 40.00);

delete from productos;

-- Insertar usuario de prueba (contraseña: "admin123")
INSERT IGNORE INTO usuarios (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

select * from usuarios
