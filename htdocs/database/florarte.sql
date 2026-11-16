-- FlorArte - Base de datos (MySQL / MariaDB)
--
-- CÓMO IMPORTAR
--   XAMPP:    en phpMyAdmin crea una base llamada "florarte", entra en ella y usa "Importar".
--   ByetHost: crea la base desde "MySQL Databases", abre phpMyAdmin, selecciona esa base
--             (la que tiene tu prefijo, ej. b7_12345678_florarte) y usa "Importar".
--
-- Este archivo NO crea la base de datos (en hosting gratuito no se puede): solo las tablas.
-- Al importar de nuevo se borran y recrean las tablas de FlorArte (los datos se pierden).

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS pedidos;
DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS usuarios;
SET FOREIGN_KEY_CHECKS = 1;

-- Todas las tablas usan InnoDB: es el único motor que aplica claves foráneas.

CREATE TABLE usuarios (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(120) NOT NULL,
  correo VARCHAR(190) NOT NULL,
  contrasena_hash VARCHAR(255) NOT NULL,
  rol ENUM('admin','cliente') NOT NULL DEFAULT 'cliente',
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_usuarios_correo (correo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE productos (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(120) NOT NULL,
  descripcion VARCHAR(255) NOT NULL,
  precio_base DECIMAL(10,2) NOT NULL DEFAULT 0,
  categoria VARCHAR(80) NOT NULL,
  tipo_flor ENUM('Natural','Sintética','Mixta') NOT NULL DEFAULT 'Natural',
  imagen VARCHAR(255) NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_productos_categoria (categoria),
  KEY idx_productos_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE pedidos (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  codigo VARCHAR(30) NOT NULL,
  usuario_id INT UNSIGNED NULL,
  producto_id INT UNSIGNED NULL,
  nombre_cliente VARCHAR(120) NOT NULL,
  correo VARCHAR(190) NOT NULL,
  tipo_flor ENUM('Natural','Sintética','Mixta') NOT NULL DEFAULT 'Natural',
  colores VARCHAR(255) NOT NULL,
  tamano ENUM('Pequeño','Mediano','Grande') NOT NULL DEFAULT 'Mediano',
  envoltura VARCHAR(150) NULL,
  dedicatoria TEXT NULL,
  presupuesto DECIMAL(10,2) NULL,
  estado ENUM('Pendiente','En revisión','Confirmado','Preparando','Entregado','Cancelado') NOT NULL DEFAULT 'Pendiente',
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_pedidos_codigo (codigo),
  KEY idx_pedidos_usuario (usuario_id),
  KEY idx_pedidos_producto (producto_id),
  KEY idx_pedidos_estado (estado),
  -- Pedido -> usuario (si se borra el usuario, el pedido se conserva sin dueño)
  CONSTRAINT fk_pedidos_usuario FOREIGN KEY (usuario_id)
    REFERENCES usuarios (id) ON DELETE SET NULL ON UPDATE CASCADE,
  -- Pedido -> producto (si se borra el producto, el pedido se conserva)
  CONSTRAINT fk_pedidos_producto FOREIGN KEY (producto_id)
    REFERENCES productos (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Productos de ejemplo (las imágenes están en assets/img/products/)
INSERT INTO productos (nombre, descripcion, precio_base, categoria, tipo_flor, imagen) VALUES
('Ramo Primavera','Colores suaves para alegrar el día.',80.00,'Ramos','Natural','assets/img/products/ramo-primavera.svg'),
('Rosas Clásicas','Un detalle elegante y romántico.',120.00,'Ramos','Natural','assets/img/products/rosas-clasicas.svg'),
('Flores Eternas','Un recuerdo que dura mucho más.',95.00,'Flores eternas','Sintética','assets/img/products/flores-eternas.svg'),
('Caja de Regalo Floral','Presentación especial para sorprender.',150.00,'Regalos','Mixta','assets/img/products/caja-regalo.svg'),
('Girasoles Alegres','Un ramo luminoso para dar energía.',90.00,'Ramos','Natural','assets/img/products/girasoles-alegres.svg'),
('Lavanda Seca','Aroma suave que se conserva por meses.',70.00,'Flores eternas','Sintética','assets/img/products/lavanda-seca.svg'),
('Rosa Eterna en Cúpula','Una rosa protegida bajo vidrio.',110.00,'Flores eternas','Sintética','assets/img/products/rosa-eterna.svg'),
('Cesta Campestre','Flores variadas en una cesta tejida.',135.00,'Regalos','Mixta','assets/img/products/cesta-campestre.svg');

-- Usuarios de prueba:
--   cliente@florarte.com / Cliente123!
--   admin@florarte.com   -> la contraseña del administrador se entrega aparte (no está en el repositorio)
-- Las contraseñas se guardan con bcrypt (password_hash de PHP), nunca en texto plano.
INSERT INTO usuarios (nombre, correo, contrasena_hash, rol) VALUES
('Administrador','admin@florarte.com','$2y$10$wuW/FShMpfep.SBxKZpi5eF2c13nll/mJNzqyW273ZDsIQp04vdQq','admin'),
('Cliente de prueba','cliente@florarte.com','$2y$10$JYTeQLHw/u.c4Md17fFccOM00AkVhfwEVGybDwOgHtYsRoizRp.ay','cliente');
